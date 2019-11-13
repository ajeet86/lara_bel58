<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
/** All Paypal Details class * */
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\Sale;
use PayPal\Api\RefundRequest;
use Redirect;
use Session;
use URL;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\User;
use App\Order;
use App\OrderItem;
use App\ShippingInfo;
use PDF;
use Mail;
use Response;
use Exception;
use Carbon\Carbon;

class PaymentController extends Controller {

    private $_api_context;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

        /** PayPal api context * */
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'], $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function index() {
        return view('paywithpaypal');
    }

    public function payWithpaypal(Request $request) {
        //echo '<pre>';print_r(session('cart'));die;
        $randomNo = mt_rand(100000, mt_getrandmax());
        $orderId = $randomNo . '' . time();
        $total_amount = 0;

        if (session('cart') && session('shipping_amount')) {
            //echo '<pre>';print_r(session('cart'));die;
            foreach (session('cart') as $id => $details) {
                $total_amount = ($details['price'] * $details['quantity']) + $total_amount;
            }
            $total_amount = $total_amount + session('shipping_amount');
        } else {
            return redirect('/all_book_list');
        }

        /* Inser order detail in table */
        $orderValue = ['order_no' => $orderId, 'user_id' => Auth::user()->id,
            'amount' => $total_amount, 'shipping_rate' => session('shipping_amount'), 'shipping_type' => session('shipping_type'), 'shipping_id' => $request->get('shipping_id'),
            'created_at' => date('Y-m-d H:i:s')];
        $lastOrderId = DB::table('orders')->insertGetId($orderValue);

        /* Inser Items dtail in table */
        if (session('cart')) {
            //echo '<pre>';print_r(session('cart'));die;
            foreach (session('cart') as $id => $details) {
                $itemsValue [] = ['order_id' => $lastOrderId,
                    'book_id' => $id, 'item_name' => $details['name'],
                    'amount' => $details['price'], 'quantity' => $details['quantity'],
                    'image' => $details['photo'], 'created_at' => date('Y-m-d H:i:s')];
            }
            $insertItem = DB::table('order_items')->insert($itemsValue);
        }

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();

        $item_1->setName('Item 1') /** item name * */
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($total_amount);/** unit price * */
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));


        $amount = new Amount();
        $amount->setCurrency('USD')
                ->setTotal($total_amount);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Your transaction descriptionss ||' . $lastOrderId . '||' . $orderId);

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('/status')) /** Specify return URL * */
                ->setCancelUrl(URL::to('/status'));

        $payment = new Payment();
        $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
        /** dd($payment->create($this->_api_context));exit; * */
        try {
            session()->forget('cart');
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {

            if (\Config::get('app.debug')) {

                \Session::put('error', 'Connection timeout');
                return Redirect::to('/');
            } else {

                \Session::put('error', 'Some error occur, sorry for inconvenient');
                return Redirect::to('/');
            }
        }

        foreach ($payment->getLinks() as $link) {

            if ($link->getRel() == 'approval_url') {

                $redirect_url = $link->getHref();
                break;
            }
        }



        /** add payment ID to session * */
        Session::put('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)) {

            /** redirect to paypal * */
            return Redirect::away($redirect_url);
        }



        \Session::put('error', 'Unknown error occurred');
        return Redirect::to('/orders');
    }

    public function getPaymentStatus() {
        /** Get the payment ID before session clear * */
        $payment_id = Session::get('paypal_payment_id');

        /** clear the session payment ID * */
        Session::forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {

            \Session::put('error', 'Payment failed');
            return Redirect::to('/orders');
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        /*         * Execute the payment * */
        $result = $payment->execute($execution, $this->_api_context);

        try {
            if ($result->getState() == 'approved') {
                $oderParam = print_r($result, true);
                $transaction = $result->transactions;
                $description = $transaction['0']->description;
                $explode = explode('||', $description);
                $paidAmount = $transaction['0']->amount->total;
                $saleId = $transaction[0]->related_resources[0]->sale->id;
                if (isset($explode[1]) && isset($explode[2])) {
                    $updateValue = ['paid_amount' => $paidAmount, 'status' => 'success', 'paypal_parameter' => $oderParam, 'sale_id' => $saleId];
                    // , 'paypal_parameter' => $oderParam
                    $updateOrderId = DB::table('orders')
                            ->where([
                                'id' => $explode[1],
                                'order_no' => $explode[2]
                            ])
                            ->update($updateValue);
                }
                // get data from open_shipment_request API and insert to shipping_details table
                //trigger exception in a "try" block
                //try {
                $order_details = DB::table('order_items')
                        ->join('books', 'books.id', '=', 'order_items.book_id')
                        ->select('books.user_id', 'order_items.book_id', 'order_items.item_name', 'order_items.amount', 'order_items.quantity')
                        ->where('order_id', $explode[1])
                        ->get();

                $sellers = array_unique(array_column($order_details->toArray(), 'user_id'));
                /* $no_of_pieces = 0;
                  foreach ($order_details as $items) {
                  $no_of_pieces = $items->quantity + $no_of_pieces;
                  $unit_amount = $items->amount;
                  $item_name[] = $items->item_name;
                  }
                  $itemname = array_unique($item_name);
                  $itemname = implode("','", $itemname);
                  // send item details to Fedex CustomsClearanceDetail object
                  $item_data = array('amount_to_pay' => $paidAmount, 'no_of_pieces' => $no_of_pieces, 'unit_amount' => $unit_amount, 'item_name' => $itemname); */
                foreach ($sellers as $seller) {
                    $fedex_open_shipment_req = app('App\Http\Controllers\User\FedexController')->fedex_open_shipment_req($seller);
                    if ($fedex_open_shipment_req[0]->Notifications[0]->Message == 'Success') {
                        DB::table('shipping_details')->insert([
                            [ 'order_id' => $explode[1],
                                'seller_id' => $seller,
                                'job_id' => $fedex_open_shipment_req[0]->JobId,
                                'carrier_code' => $fedex_open_shipment_req[0]->CompletedShipmentDetail->CarrierCode,
                                'shipment_tracking_no' => $fedex_open_shipment_req[0]->CompletedShipmentDetail->MasterTrackingId->TrackingNumber,
                                'pkg_tracking_no' => $fedex_open_shipment_req[0]->CompletedShipmentDetail->CompletedPackageDetails[0]->TrackingIds[0]->TrackingNumber,
                                'fedx_response' => serialize($fedex_open_shipment_req)
                            ],
                        ]);
                    }
                }
                //}//catch exception
                //catch (Exception $e) {
                //  echo 'Message: ' . $e->getMessage();
                //}
                if (Auth::check()) {
                    /*                     * ****************send pdf */
                    $id = Auth::user()->id;
                    $users = DB::table('users')
                            ->where('id', '=', $id)
                            ->first();
                    //print_r($explode[1]);die;
                    $order_id = $explode[1];
                    $id = Auth::id();
                    $userProfile = User::find($id);
                    $shipping_info = '';

                    //DB::enableQueryLog();
                    $order_details = DB::table('orders')
                            ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                            //->leftJoin('shipping_information','shipping_information.id', '=', 'orders.shipping_id')					
                            ->where('orders.user_id', '=', $id)
                            ->where('orders.id', '=', $order_id)
                            ->orderBy('orders.id', 'DESC')
                            ->get();
                    //dd(DB::getQueryLog());
                    //echo '<pre>';print_r($order_details);die;

                    $total = 0;
                    foreach ($order_details as $order_detail) {
                        $total = $total + ($order_detail->amount * $order_detail->quantity);
                        DB::table('books')
                                ->where('id', $order_detail->book_id)
                                ->decrement('quantity', $order_detail->quantity);
                        $seller_email = DB::table('users')->select('users.id', 'users.email')
                                ->leftJoin('books', 'users.id', '=', 'books.user_id')
                                ->where('books.id', '=', $order_detail->book_id)
                                ->first();
                        $seller_emails[$seller_email->id] = $seller_email->email;
                    }
                    $shipping_info = DB::table('orders')
                            ->leftJoin('shipping_infos', 'shipping_infos.id', '=', 'orders.shipping_id')
                            ->where('orders.user_id', '=', $id)
                            ->where('orders.id', '=', $order_id)
                            ->orderBy('orders.id', 'DESC')
                            ->first();
                    $name = mt_rand() . '-' . $order_id . '.pdf';
                    $pdfFilePath = public_path() . '/pdf/' . $name;

                    $path = PDF::loadView('user.invoice', compact('order_details', 'userProfile', 'shipping_info', 'total'), [], [
                                'format' => 'A5-L'
                            ])->save($pdfFilePath);


                    $data = [
                        'email_address' => $users->email,
                        'subject' => 'Payment success',
                        'name' => $users->name,
                    ];

                    Mail::send('user.paymentMail', $data, function($message) use($data, $pdfFilePath) {
                        $message->from('arun.pratap914@gmail.com', 'Schoolshark');
                        $message->to($data['email_address']);
                        /* if ($data['cc'] != null) {
                          $message->cc($data['cc']);
                          } */
                        $message->subject($data['subject']);

                        //Full path with the pdf name

                        $message->attach($pdfFilePath);
                    });

                    $admin_data = [
                        'sender' => 'Admin',
                        'user_name' => $users->name
                    ];
                    Mail::send('user.ack_paymentMail', $admin_data, function($message) use($pdfFilePath) {
                        $message->to('arun.pratap914@gmail.com');
                        $message->subject('An order has be placed');
                        $message->attach($pdfFilePath);
                    });
                    //mail to sender and recipient
                    $user_data = [
                        'sender' => 'User',
                        'user_name' => $users->name,
                        'link' => url('/my_sells')
                    ];
                    foreach ($seller_emails as $email) {
                        // send confirmation mail to user
                        Mail::send('user.ack_paymentMail', $user_data, function($message) use($email) {
                            $message->to($email);
                            $message->subject('An order has be placed');
                        });
                    }
                    /*                     * * send pdf end ** */
                }
                session()->flash('success', 'Payment success');

                return Redirect::to('/orders');
            }
            session()->flash('error', 'Payment failed');
            return Redirect::to('/orders');
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            echo $ex;
            exit;
        }
    }

    public function paymentRefund(Request $request) {
        // get data from request
        $orderId = $request->ord_id; // ord_id;
        $refundAmount = $request->refund_amt; // refund amount
        // get order information
        $order = DB::table('orders')->select('order_no', 'user_id', 'sale_id', 'paid_amount')->where('id', $orderId)->first();
        $saleId = $order->sale_id; // sale id; 
        $userId = $order->user_id; // user id
        $paidAmt = $order->paid_amount; // paid amount
        $ord_no = $order->order_no; // order number
        // get user info
        $user = DB::table('users')->select('name', 'email')->where('id', $userId)->first();
        $order_details = DB::table('refund_amounts')
                ->where('order_id', '=', $orderId)
                ->sum('refund_amount');
        if ($refundAmount > $paidAmt - $order_details) {
            return Response::json(['message' => 'You has exceed your refund quota limit!!']);
            exit;
        }
        if ($saleId == '' || $refundAmount == '' || $orderId == '') {
            // Redirect back
            return Redirect::to('admin/order_details/' + $orderId);
        }
        try {
            $sale = Sale::get($saleId, $this->_api_context);

            $amt = new Amount();
            $amt->setCurrency('USD')
                    ->setTotal($refundAmount);
            // ### Refund object
            $refundRequest = new RefundRequest();
            $refundRequest->setAmount($amt);
            // ###Sale
            $sale = new Sale();
            $sale->setId($saleId);
            try {
                // Refund the sale
                $refundedSale = $sale->refundSale($refundRequest, $this->_api_context);
                if ($refundedSale->state == 'completed') {
                    $updateValue = [
                        'order_id' => $orderId,
                        'refund_amount' => $refundedSale->amount->total,
                        'refund_transaction_fee' => $refundedSale->refund_from_transaction_fee->value,
                        'result_parameter' => print_r($refundedSale, true),
                        'transaction_id' => $refundedSale->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    DB::table('refund_amounts')->insert($updateValue);
                    //mail to sender and recipient
                    $user_data = [
                        'email_address' => $user->email,
                        'subject' => 'Refund successfully',
                        'name' => $user->name,
                        'order_no' => $ord_no,
                        'refund_amt' => $refundAmount
                    ];
                    // send confirmation mail to user
                    Mail::send('admin.emails.refundPayment', $user_data, function($message) use($user_data) {
                        $message->from('arun.pratap914@gmail.com', 'School Shark');
                        $message->to($user_data['email_address']);
                        /* if ($user_data['cc'] != null) {
                          $message->cc($user_data['cc']);
                          } */
                        $message->subject($user_data['subject']);
                    });
                    Mail::send('admin.emails.refundConfirmation', $user_data, function($message) use($user_data) {
                        $message->to('arun.pratap914@gmail.com');
                        /* if ($user_data['cc'] != null) {
                          $message->cc($user_data['cc']);
                          } */
                        $message->subject($user_data['subject']);
                    });
                    // success message here
                    return Response::json(['success' => 'Amount Refund Successfully']);
                }
                return Response::json(['message' => 'Amount Refund Failed']);
                // Error message Here with redirect URL;
            } catch (Exception $ex) {
                return $ex->getMessage();
                exit(1);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
            //ResultPrinter::printError("Refund Sale", "Sale", null, $saleId, $ex);
            exit(1);
        }
    }

    public function paymentToSeller(Request $request) {
        $seller_id = $request->seller_id;
        $order_items_id = $request->order_dtls_id;
        $message_to_seller = $request->message_to_seller;
        $user_paypal_id = DB::table('users')->select('paypal_email')->where('id', $seller_id)->first();
        $amt_pay_to_seller = $request->amt_pay_to_seller;
        //$user_paypal_id->paypal_email;
        $payouts = new \PayPal\Api\Payout();
        $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();

        $senderBatchHeader->setSenderBatchId(uniqid())
                ->setEmailSubject("You have a Payout!");

        $senderItem = new \PayPal\Api\PayoutItem();
        $senderItem->setRecipientType('Email')
                ->setNote('Thanks for your patronage!')
                ->setReceiver($user_paypal_id->paypal_email)
                ->setSenderItemId($order_items_id)
                ->setAmount(new \PayPal\Api\Currency('{
                                "value" :' . $amt_pay_to_seller . ',
                                "currency" : "USD"
                            }'));
        $payouts->setSenderBatchHeader($senderBatchHeader)
                ->addItem($senderItem);
        $request = clone $payouts;
        try {
            $output = $payouts->create([], $this->_api_context);
            if (isset($output->batch_header->payout_batch_id)) {
                DB::table('seller_payments')->insert([
                    [ 'ord_detls_id' => $order_items_id,
                        'seller_id' => $seller_id,
                        'message' => $message_to_seller,
                        'amount' => $amt_pay_to_seller,
                        'result_parameter' => $output,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ],
                ]);
                $updatePaymentStatus = DB::table('order_items')
                        ->where([
                            'id' => $order_items_id
                        ])
                        ->update(['paymt_to_seller_status' => 1]);
                return Response::json(['success' => 'Amount Paid to seller Successfully']);
            }
        } catch (Exception $ex) {
            //return  $ex->getData();
            return $ex->getMessage();
            exit(1);
        }
        //echo $output->getBatchHeader()->getPayoutBatchId();
        //echo "<pre>";
    }

}
