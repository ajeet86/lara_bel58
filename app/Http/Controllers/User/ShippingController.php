<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use Session;
use App\Order;
use App\OrderItem;
use App\ShippingInfo;
use App\User;
use App\Book;
use App\ShippingDetail;
use App\CancelOrder;
use PDF;
use Mail;

class ShippingController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function shipping(Request $request) {
        $countries = DB::table('countries')->where('country_id', '=', 'US')
                ->orderBy('country_name', 'ASC')->get();
        $states = DB::table('states')->where('country_id', '=', 'US')->get();
        if(!empty(session('cart'))){
            $sellers = array_unique(array_column(session('cart'), 'seller_id'));
            $cart_by_seller = array();
            foreach(session('cart') as $key => $value){ 
                $cart_by_seller[$sellers[array_search($value['seller_id'], $sellers)]][] = $value ; 
            }
        }
        if (isset($_POST['shippingInfo'])) {
            $this->validate($request, [
                'firstName' => 'required|string|min:2|max:50',
                'lastName' => 'required|string|min:2|max:50',
                'email' => 'required|email|max:150',
                'address' => 'required|max:225',
                'apartment' => 'required|max:100',
                'city' => 'required|string|max:225',
                'country' => 'required',
                'state' => 'required',
                'tele' => 'required|numeric',
                'pin_code' => 'required|max:7',
            ]);
            //trigger exception in a "try" block
            try {
                ini_set('soap.wsdl_cache_enabled', 0);
                ini_set('soap.wsdl_cache_ttl', 0);
                foreach($cart_by_seller as $seller_id=>$item_info){
                    $fedex_rate_req[] = app('App\Http\Controllers\User\FedexController')->fedex_rate_request($request, $seller_id, $item_info);
                }
                $shipping_rate = 0;
                foreach($fedex_rate_req as $fedex_rate){
                    
                    if ($fedex_rate->Notifications[0]->Severity == 'ERROR') {
                        $fedx_err_msg = $fedex_rate->Notifications[0]->Message;
                    } elseif ($fedex_rate->Notifications[0]->Severity == 'WARNING' && ($fedex_rate->Notifications[0]->Code == '835' || $fedex_rate->Notifications[0]->Code == '820')) {
                        $fedx_err_msg = $fedex_rate->Notifications[0]->Message;
                    } elseif ($fedex_rate->Notifications[0]->Severity == 'WARNING' && $fedex_rate->Notifications[0]->Code == '556') {
                        $fedx_err_msg = $fedex_rate->Notifications[0]->Message;
                    } else {
                        $fedx_err_msg = '';
                    }
                    if ($fedx_err_msg != '') {
                        Session::flash('fedx_err_msg', $fedx_err_msg);
                    }
                    if (!empty($fedex_rate->RateReplyDetails)){
                        $rate_leng = sizeof($fedex_rate->RateReplyDetails);
                        $rated_sp_leng = sizeof($fedex_rate->RateReplyDetails[$rate_leng-1]->RatedShipmentDetails);
                        $service_type = $fedex_rate->RateReplyDetails[$rate_leng-1]->ServiceType;
                        $shipping_rate = $shipping_rate + $fedex_rate->RateReplyDetails[$rate_leng-1]->RatedShipmentDetails[$rated_sp_leng-1]->ShipmentRateDetail->TotalNetCharge->Amount;
                        $shipping_currency = $fedex_rate->RateReplyDetails[$rate_leng-1]->RatedShipmentDetails[$rated_sp_leng-1]->ShipmentRateDetail->TotalNetCharge->Currency;
                    }
                }
                if ($fedx_err_msg != '') {
                    return view('user.shipping', compact('countries', 'states'));
                } else {
                    $request->session()->put('shipping_amount', $shipping_rate);
                    $request->session()->put('shipping_type', $service_type);
                    $request->session()->put('shipping_currency', $shipping_currency);
                }
            }
            //catch exception
            catch (SoapFault $e) {
                Log::error($e->getMessage());
                //echo 'Message: ' .$e->getMessage();
            }
            
            $data = $request->all();
            if (Auth::check()) {
                $id = Auth::user()->id;
                $shipping_infos = ShippingInfo::updateOrCreate([
                            'user_id' => $id], ['email' => $data['email'],
                            'first_name' => $data['firstName'],
                            'last_name' => $data['lastName'],
                            'address' => $data['address'],
                            'apartment' => $data['apartment'],
                            'country' => $data['country'],
                            'state' => $data['state'],
                            'city' => $data['city'],
                            'pin_code' => $data['pin_code'],
                            'phone_number' => $data['tele'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                ]);

                if ($shipping_infos->id != '') {
                    $success = 'inserted';
                    $shipping_info = array(
                        'user_id' => $id,
                        'shipping_info_id' => $shipping_infos->id,
                        'first_name' => $data['firstName'],
                        'last_name' => $data['lastName'],
                        'address' => $data['address'],
                        'apartment' => $data['apartment'],
                        'country' => $data['country'],
                        'state' => $data['state'],
                        'city' => $data['city'],
                        'pin_code' => $data['pin_code'],
                        'phone_number' => $data['tele'],
                        'email' => $data['email']
                    );
                    return view('user.checkout', compact('shipping_info', 'fedex_rate_req'));
                    //......................//
                } else {
                    Session::flash('error', 'Data not inserted!');
                    return view('user.shipping');
                }
            } else {
                return redirect('/login');
            }
        }
        if (Auth::check()) {

            // The user is logged in and fill previous shipping details on shipping page...
            $id = Auth::user()->id;
            $shipping_details = DB::table('shipping_infos')
                    ->where('user_id', '=', $id)
                    ->first();

            return view('user.shipping', compact('shipping_details', 'countries',
                    'states'));
        }
        return view('user.shipping', compact('countries', 'states'));
    }
    
    public function buy_now($id) {
        $book = Book::findOrFail($id);

        if (!$book) {

            abort(404);
        }

        $cart = session()->get('cart');

        $price_book = '';

        if ($book->perc_listing_price == '') {
            $price_book = $book->perc_org_price;
        } else {
            $price_book = $book->perc_listing_price;
        }

        $book_images = DB::table('book_images')->where('book_id', $id)->first();

//echo "<pre>"; print_r($book_images); die;
        // if cart is empty then this the first product
        if (!$cart) {
            $cart = [
                $id => [
                    "name" => $book->title,
                    "quantity" => 1,
                    "price" => $price_book,
                    "photo" => isset($book_images->image) ? $book_images->image : '',
                    "weight" => $book->weight,
                    "seller_id" => $book->user_id,
                ]
            ];


            session()->put('cart', $cart);
        }
        if(Auth::guest()){
            return redirect('/login');
        } else {
            return redirect('/shipping');
        }
    }
    /**
     * Display user order list.
     *
     * @return \Illuminate\Http\Response
     */
    public function myorderlist() {
        $id = Auth::user()->id;
        $orders = Order::with(['orderItems'=> function($query) use ($id) {
                        return $query->join('books','order_items.book_id','=','books.id')
                                ->join('users','books.user_id','=','users.id')
                                ->select('order_items.id', 'order_items.order_id',
                                        'order_items.book_id', 'order_items.item_name',
                                        'order_items.amount', 'order_items.quantity',
                                        'order_items.confirm_pkg_status',
                                        'order_items.image', 'users.name');
                    }])
                ->select('id', 'order_no', 'amount', 'shipping_rate',
                'paid_amount', 'status', 'created_at')
                ->where('user_id', $id)->orderBy('id', 'DESC')->get();
        return view('user.orders', compact('orders'));
    }

    /**
     * Display user order details by specific id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function orderDetails($id) {
        $ship_id = Order::select('shipping_id')->where('id', $id)->first();
        $order_details = ShippingInfo::with(['orders' => function($query) use ($id) {
                        return $query->with(['orderItems' => function($query) use ($id) {
                        return $query->join('books','order_items.book_id','=','books.id')
                                ->join('users','books.user_id','=','users.id')
                                ->select('order_items.id', 'order_items.order_id',
                                        'order_items.book_id', 'order_items.item_name',
                                        'order_items.amount', 'order_items.quantity',
                                        'order_items.image', 'users.name');
                        }, 'shippingDetails'=> function($query){
                            return $query->select('pkg_tracking_no', 'order_id');
                        }])
                        ->select('id', 'shipping_id', 'order_no',
                                'amount', 'shipping_rate', 'paid_amount', 'status',
                                'created_at')
                        ->where('id', $id);
                    }])->where('id', $ship_id->shipping_id)->first();
        return view('user.order_details', compact('order_details'));
    }

    /**
     * Display generated pdf to user.
     *
     * @param  int  $order_id
     * @return \Illuminate\Http\Response
     */
    public function generate_pdf($order_id) {
        $id = Auth::id();
        $userProfile = User::find($id);
        $shipping_info = '';
        $order_details = DB::table('orders')
                ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.user_id', '=', $id)
                ->where('orders.id', '=', $order_id)
                ->orderBy('orders.id', 'DESC')
                ->get();

        $total = 0;
        foreach ($order_details as $order_detail) {
            $total = $total + ($order_detail->amount * $order_detail->quantity);
        }

        $shipping_info = DB::table('orders')
                        ->select('orders.id', 'orders.order_no', 'orders.paid_amount', 'orders.shipping_rate', 'orders.amount',
                        'orders.created_at', 'shipping_infos.first_name',
                        'shipping_infos.last_name', 'shipping_infos.apartment',
                        'shipping_infos.address', 'shipping_infos.city',
                        'shipping_infos.state', 'shipping_infos.country',
                        'shipping_infos.pin_code')
                ->leftJoin('shipping_infos', 'shipping_infos.id', '=', 'orders.shipping_id')
                ->where('orders.user_id', '=', $id)
                ->where('orders.id', '=', $order_id)
                ->orderBy('orders.id', 'DESC')
                ->first();
        $pdf = PDF::loadView('user.invoice', compact('order_details', 'userProfile', 'shipping_info', 'total'));
        return $pdf->stream('document.pdf');
    }
    
    public function seller_orders(){
        $id = Auth::User()->id;
        $orders = Order::with(['orderItems'=> function($query) use ($id) {
                        return $query->join('books','order_items.book_id','=','books.id')
                                ->where('books.user_id', '=', $id)
                                ->select('order_items.id', 'order_items.order_id',
                                        'order_items.book_id', 'order_items.item_name',
                                        'order_items.amount', 'order_items.quantity',
                                        'order_items.image');
                        //return $query->where('seller_id', '=', $id);
                    }])
                    ->whereHas('orderItems', function ($query) use ($id){
                        return $query->join('books','order_items.book_id','=','books.id')->where('books.user_id', '=', $id);
                       //return $query->where('seller_id', '=', $id);
                    })->leftJoin('users', 'users.id', '=', 'orders.user_id')
                            ->select('orders.id', 'orders.order_no', 'orders.amount',
                'orders.paid_amount', 'orders.status', 'orders.created_at',
                                    'users.name as buyer_name')
                            ->orderBy('orders.id', 'DESC')->paginate(4);
        return view('user.my_sell_list', compact('orders'));
    }
    
    public function sells_detail($id){
        $user_id = Auth::User()->id;
        $ship_id = Order::select('shipping_id')->where('id', $id)->first();
        $order_details = ShippingInfo::with(['orders' => function($query) use ($id, $user_id) {
                            return $query->with(['orderItems'=> function($query) use ($user_id) {
                            return $query->join('books','order_items.book_id','=','books.id')
                                ->where('books.user_id', '=', $user_id)
                                ->select('order_items.id', 'order_items.order_id',
                                        'order_items.book_id', 'order_items.item_name',
                                        'order_items.amount', 'order_items.quantity',
                                        'order_items.image');
                            }])->leftJoin('users', 'users.id', '=', 'orders.user_id')
                            ->select('orders.id', 'orders.shipping_id',
                                    'orders.order_no', 'orders.status', 'orders.cancel_status',
                                    'orders.created_at', 'users.name as buyer_name')
                            ->where('orders.id', $id);
                    }])->where('id', $ship_id->shipping_id)->first();
        return view('user.sell_details', compact('order_details'));
    }
    
    public function generate_seller_pdf($ord_id){
        $user_id = Auth::id();
        $ship_id = Order::select('shipping_id')->where('id', $ord_id)->first();
        $order_details = ShippingInfo::with(['orders' => function($query) use ($ord_id, $user_id) {
                            return $query->with(['orderItems'=> function($query) use ($user_id) {
                            return $query->join('books','order_items.book_id','=','books.id')
                                ->where('books.user_id', '=', $user_id)
                                ->select('order_items.id', 'order_items.order_id',
                                        'order_items.book_id', 'order_items.item_name',
                                        'order_items.amount', 'order_items.quantity',
                                        'order_items.image');
                            //return $query->where('seller_id', '=', $id);
                            }])
                            ->select('id', 'shipping_id', 'order_no', 'status',
                                    'created_at')
                            ->where('id', $ord_id);
                    }])->where('id', $ship_id->shipping_id)->first();
        $pdf = PDF::loadView('user.seller_invoice', compact('order_details'));
        return $pdf->stream('sell_order_invoice.pdf');
    }
	
    public function check_list(){
        $user_id = Auth::User()->id;
        $my_book_id = Book::select('id')->where('user_id', $user_id)->get()->toArray();
        $my_book_ids = array_column($my_book_id, 'id');
        $cart = session('cart');
        $cart_ids = array_keys($cart);
        $result = array_intersect($my_book_ids, $cart_ids);
        if(!empty($result)){
            $msg = "Sorry!! You cann't buy your own item. First delete your own item from cart list then preceed to checkout.";
            return redirect()->back()
			->with('message', $msg)
			->with('my_items', $result);
        } else {
            return redirect('/shipping');
        }
    }
    // Cancellation Request
    public function cancelForm(Request $request){
        $order_no = Order::select('order_no')->findOrFail($request['order-id']);
        $cancel_details = DB::table('cancel_orders')							
                ->where('cancel_orders.order_id', '=', $request['order-id'])
                ->where('cancel_orders.user_id', '=', $request['user-id'])
                ->first();
        $seller_info = DB::table('order_items')							
                ->join('books','order_items.book_id','=','books.id')
                ->join('users','books.user_id','=','users.id')
                ->select('books.user_id as seller_id', 'books.id as book_id', 'users.email as email_id')
                ->where('order_items.order_id', '=', $request['order-id'])
                ->get();
        $seller_emails = array_unique(array_column($seller_info->toArray(), 'email_id', 'seller_id'));
        $sellers = array_unique(array_column($seller_info->toArray(), 'seller_id'));
        $seller_items = array();
        foreach($seller_info as $value){ 
            $seller_items[$sellers[array_search($value->seller_id, $sellers)]][] = $value->book_id ; 
        }
	/*Inser cancel detail in table*/
        if(!empty($cancel_details) > 0){
            return redirect()->back()->with('error', 'Cancel request has been already created for this order.');
        } else {
            $cancelValue = [
                    'user_id' => $request['user-id'],
                    'order_id' => $request['order-id'] , 
                    'shipping_amount' => $request['shipping-amount'] , 
                    'amount'=> $request['product-total'], 
                    'cancel_reason'=> $request['cancelreason'], 
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
            ];
            $lastcancelId = DB::table('cancel_orders')->insertGetId($cancelValue);

            $order_table = DB::table('orders')->where('id', '=', $request['order-id'])->update(['cancel_status'=>'1']);
            foreach($seller_emails as $seller_email){
                $data = [
                            'email_address' => $request['user-email'],
                            'cc' => 'arun.pratap914@gmail.com',
                            'subject' => 'Order cancel',
                            'order_id' => $order_no->order_no,
                            'user-id' => $request['user-id'],
                            'client_message' => $request['cancelreason'],
                            'seller_email' => $seller_email
                        ];

                Mail::send('user.cancelItem', $data, function($message) use($data) {
                $message->from($data['email_address'], 'Schoolshark');
                $message->to($data['seller_email']);
                if($data['cc'] != null){
                    $message->cc($data['cc']);
                }
                $message->subject($data['subject']);
            //Full path with the pdf name
            });
        }
            return redirect()->back()->with('success', 'Email sent successfully!');
        }
    }
    // Confirm the package
    public function confirm_package($ord_dtls_id){
        DB::table('order_items')->where('id', '=', $ord_dtls_id)->update(['confirm_pkg_status' => 1]);
        return response()->json(['success' => 'You confirmed Successfully']);
    }
}
        