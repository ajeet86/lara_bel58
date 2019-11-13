<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Response;
use Redirect;
use Auth;
use Illuminate\Support\Facades\Input;
use DB;
use App\Order;
//use App\OrderItem;
use App\ShippingInfo;

class OrderController extends Controller
{
    /**
     * Class constructor.
     *
     * 
     */
    public function __construct() {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin.order_list');
    }
    public function order_cancel_request_list()
    {
        return view('admin.cancel_order_list');
    }
    
    /**
     * Fetch all the orders from database table.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchOrders() {

        $start = Input::get('iDisplayStart');      // Offset
        $length = Input::get('iDisplayLength');     // Limit
        $sSearch = Input::get('sSearch');            // Search string
        $col = Input::get('iSortCol_0');         // Column number for sorting
        $sortType = Input::get('sSortDir_0');         // Sort type
        $where = '';

        // Datatable column number to table column name mapping
        $arr = array(
            0 => 't1.id',
            1 => 't1.order_no',
            2 => 't2.name',
            3 => 't5.name',
            4 => 't1.amount',
            5 => 't1.paid_amount',
            6 => 't1.status',
            7 => 't1.created_at',
        );

        // Map the sorting column index to the column name
        $sortBy = $arr[$col];
        if ($sortBy == '') {
            $sortBy = "t1.id";
        }
        if ($sSearch != '') {
			$sSearch = addslashes($sSearch);
            $where = " WHERE t1.order_no LIKE ('%" . $sSearch . "%')"
                    . " OR t2.name LIKE ('%" . $sSearch . "%')"
                    . " OR t1.status LIKE ('%" . $sSearch . "%')"
                    . " OR t5.name LIKE ('%" . $sSearch . "%')";
        }
        // Get the records after applying the datatable filters
        $orders = DB::select(
                        DB::raw("SELECT t1.id, t1.order_no, t2.name as buyer, t1.amount,"
                                . " GROUP_CONCAT(DISTINCT t5.name SEPARATOR ' , ') as seller, t1.paid_amount, t1.status, t1.created_at"
                                . " FROM orders t1"
                                . " JOIN users t2 ON t1.user_id = t2.id "
                                . " JOIN order_items t3 ON t1.id = t3.order_id "
                                . " JOIN books t4 ON t3.book_id = t4.id "
                                . " JOIN users t5 ON t4.user_id = t5.id " .
                                $where . " GROUP BY t1.id, t1.order_no, t2.name, "
                                . "t1.amount, t1.paid_amount, t1.status, t1.created_at"
                                . " ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );
        // Get the total count without any condition to maintian the pagination
        $orderCount = DB::select(
                        DB::raw("SELECT t1.id, t1.order_no, t2.name as buyer, t1.amount,"
                                . " GROUP_CONCAT(DISTINCT t5.name SEPARATOR ' , ') as seller, t1.paid_amount, t1.status, t1.created_at"
                                . " FROM orders t1"
                                . " JOIN users t2 ON t1.user_id = t2.id "
                                . " JOIN order_items t3 ON t1.id = t3.order_id "
                                . " JOIN books t4 ON t3.book_id = t4.id "
                                . " JOIN users t5 ON t4.user_id = t5.id " .
                                $where . "GROUP BY t1.id, t1.order_no, t2.name, "
                                . "t1.amount, t1.paid_amount, t1.status, t1.created_at")
        );

        // Assign it to the datatable pagination variable
        $iTotal = count($orderCount);

        $response = array(
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iTotal,
            'aaData' => array()
        );

        $k = 0;
        if (count($orders) > 0) {
            foreach ($orders as $order) {
                $response['aaData'][$k] = array(
                    0 => $k + 1,
                    1 => $order->order_no,
                    2 => $order->buyer,
                    3 => $order->seller,
                    4 => '$' . number_format($order->amount, 2),
                    5 => '$' . number_format($order->paid_amount, 2),
                    6 => $order->status,
                    7 => date('d-m-Y', strtotime($order->created_at)),
                    8 => '<a href="order_details/' . $order->id . '" '
                    . 'class="delete hidden-xs hidden-sm" title="View">'
                    . '<i class="fa fa-eye text-warning"></i></a> &nbsp;',
                );
                $k++;
            }
        }
        return response()->json($response);
    }
    
    /**
     * Fetch all the cancel orders from database table.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchCancelOrders() {

        $start = Input::get('iDisplayStart');      // Offset
        $length = Input::get('iDisplayLength');     // Limit
        $sSearch = Input::get('sSearch');            // Search string
        $col = Input::get('iSortCol_0');         // Column number for sorting
        $sortType = Input::get('sSortDir_0');         // Sort type
        $where = '';

        // Datatable column number to table column name mapping
        $arr = array(
            0 => 't1.id',
            1 => 't1.order_no',
            2 => 't2.name',
            3 => 't5.name',
            4 => 't1.amount',
            5 => 't1.paid_amount',
            6 => 't1.status',
            7 => 't1.created_at',
        );

        // Map the sorting column index to the column name
        $sortBy = $arr[$col];
        if ($sortBy == '') {
            $sortBy = "t1.id";
        }
        if ($sSearch != '') {
            $sSearch = addslashes($sSearch);
            $where = " AND (t1.order_no LIKE ('%" . $sSearch . "%')"
                    . " OR t2.name LIKE ('%" . $sSearch . "%')"
                    . " OR t1.status LIKE ('%" . $sSearch . "%')"
                    . " OR t5.name LIKE ('%" . $sSearch . "%'))";
        }
        // Get the records after applying the datatable filters
        $orders = DB::select(
                        DB::raw("SELECT t1.id, t1.order_no, t2.name as buyer, t1.amount,"
                                . " GROUP_CONCAT(DISTINCT t5.name SEPARATOR ' , ') as seller, t1.paid_amount, t1.status, t1.created_at"
                                . " FROM orders t1"
                                . " JOIN users t2 ON t1.user_id = t2.id "
                                . " JOIN order_items t3 ON t1.id = t3.order_id "
                                . " JOIN books t4 ON t3.book_id = t4.id "
                                . " JOIN users t5 ON t4.user_id = t5.id WHERE cancel_status = '1' " .
                                $where . " GROUP BY t1.id, t1.order_no, t2.name, "
                                . "t1.amount, t1.paid_amount, t1.status, t1.created_at"
                                . " ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );
        // Get the total count without any condition to maintian the pagination
        $orderCount = DB::select(
                        DB::raw("SELECT t1.id, t1.order_no, t2.name as buyer, t1.amount,"
                                . " GROUP_CONCAT(DISTINCT t5.name SEPARATOR ' , ') as seller, t1.paid_amount, t1.status, t1.created_at"
                                . " FROM orders t1"
                                . " JOIN users t2 ON t1.user_id = t2.id "
                                . " JOIN order_items t3 ON t1.id = t3.order_id "
                                . " JOIN books t4 ON t3.book_id = t4.id "
                                . " JOIN users t5 ON t4.user_id = t5.id WHERE cancel_status = '1' " .
                                $where . "GROUP BY t1.id, t1.order_no, t2.name, "
                                . "t1.amount, t1.paid_amount, t1.status, t1.created_at")
        );

        // Assign it to the datatable pagination variable
        $iTotal = count($orderCount);

        $response = array(
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iTotal,
            'aaData' => array()
        );

        $k = 0;
        if (count($orders) > 0) {
            foreach ($orders as $order) {
                $response['aaData'][$k] = array(
                    0 => $k + 1,
                    1 => $order->order_no,
                    2 => $order->buyer,
                    3 => $order->seller,
                    4 => '$' . number_format($order->amount, 2),
                    5 => '$' . number_format($order->paid_amount, 2),
                    6 => $order->status,
                    7 => date('d-m-Y', strtotime($order->created_at)),
                    8 => '<a href="cancel_order_details/' . $order->id . '" '
                    . 'class="delete hidden-xs hidden-sm" title="View">'
                    . '<i class="fa fa-eye text-warning"></i></a> &nbsp;',
                );
                $k++;
            }
        }
        return response()->json($response);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $ship_id = Order::select('shipping_id')->where('id', $id)->first();
        $order_details = ShippingInfo::with(['orders' => function($query) use ($id) {
                        return $query->with(['orderItems' => function($query) use ($id) {
                        return $query->join('books','order_items.book_id','=','books.id')
                                ->leftjoin('seller_payments','order_items.id','=','seller_payments.ord_detls_id')
                                ->join('users','books.user_id','=','users.id')
                                ->select('order_items.id', 'order_items.order_id',
                                        'order_items.book_id', 'order_items.item_name',
                                        'order_items.amount', 'order_items.quantity',
                                        'order_items.image', 'order_items.paymt_to_seller_status',
                                        'order_items.confirm_pkg_status',
                                        'seller_payments.amount as paid_amt_to_seller', 'seller_payments.message',
                                        'users.name', 'users.id as seller_id');
                        }, 'shippingDetails' => function($query){
                            $query->select('order_id', 'seller_id', 'job_id', 'pkg_tracking_no');
                        }])
                        ->select('id', 'shipping_id', 'order_no', 'shipping_rate',
                                'amount', 'paid_amount', 'status', 'cancel_status',
                                'created_at')
                        ->where('id', $id);
                    }])->where('id', $ship_id->shipping_id)->first();
        return view('admin.order_details', compact('order_details'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_cancel_order($id) {
        $ship_id = Order::select('shipping_id')->where('id', $id)->first();
        $order_details = ShippingInfo::with(['orders' => function($query) use ($id) {
                        return $query->with(['orderItems' => function($query) use ($id) {
                        return $query->join('books','order_items.book_id','=','books.id')
                                ->join('users','books.user_id','=','users.id')
                                ->select('order_items.id', 'order_items.order_id',
                                        'order_items.book_id', 'order_items.item_name',
                                        'order_items.amount', 'order_items.quantity',
                                        'order_items.image',
                                        'users.name', 'users.id as seller_id');
                        }, 'cancelOrders', 'shippingDetails' => function($query){
                            $query->select('order_id', 'seller_id', 'job_id', 'pkg_tracking_no');
                        }])
                        ->select('id', 'shipping_id', 'order_no', 'shipping_rate',
                                'amount', 'paid_amount', 'status', 'cancel_status',
                                'created_at')
                        ->where('id', $id);
                    }])->where('id', $ship_id->shipping_id)->first();
        return view('admin.cancel_order_details', compact('order_details'));
    }
    public function refund_index()
    {
        return view('admin.refund_list');
    }
    public function fetchRefundList()
    {	
        $start      = Input::get('iDisplayStart');      // Offset
        $length     = Input::get('iDisplayLength');     // Limit
        $sSearch    = Input::get('sSearch');            // Search string
        $col        = Input::get('iSortCol_0');         // Column number for sorting
        $sortType   = Input::get('sSortDir_0');         // Sort type
        $where = '';

        // Datatable column number to table column name mapping
        $arr = array(
                        0 => 't1.id',
                        1 => 't2.order_no',
                        2 => 't1.refund_amount',
                        3 => 't1.transaction_id',
                        4 => 't1.refund_transaction_fee',
                        5 => 't1.created_at',
                );

        // Map the sorting column index to the column name
        $sortBy = $arr[$col];
        if($sortBy == ''){
            $sortBy = "t1.id";

        }

        if($sSearch != ''){
            $where = " WHERE ( t1.refund_amount LIKE ('%". $sSearch ."%') "
                    . "OR t1.transaction_id LIKE ('%". $sSearch ."%') "
                    . "OR t2.order_no LIKE ('%". $sSearch ."%') )";

        }
        // Get the records after applying the datatable filters
        $refund_amount = DB::select(
            DB::raw("SELECT t1.*, t2.order_no, t2.id as ord_id "
                    . "FROM refund_amounts t1 JOIN orders t2 "
                    . "ON t1.order_id = t2.id " .
                    $where . " ORDER BY " . $sortBy . " " . 
                    $sortType ." LIMIT ".$start.", ".$length)
            );
        // Get the total count without any condition to maintian the pagination
        $refundCount = DB::select(
                    DB::raw("SELECT t1.*, t2.order_no, t2.id as ord_id FROM "
                            . "refund_amounts t1 JOIN orders t2 "
                            . "ON t1.order_id = t2.id " . $where));

        // Assign it to the datatable pagination variable
        $iTotal = count($refundCount);
        $response = array(
                'iTotalRecords' => $iTotal,
                'iTotalDisplayRecords' => $iTotal,
                'aaData' => array()
        );
        $k = 0;
        if ( count( $refund_amount ) > 0 )
        {
            foreach ($refund_amount as $refund)
            {
                $response['aaData'][$k] = array(
                    0 => $k+1,
                    1 => $refund->order_no,
                    2 => '$' . number_format($refund->refund_amount, 2),
                    3 => $refund->transaction_id,
                    4 => '$' . $refund->refund_transaction_fee,
                    5 => date('d-m-Y', strtotime($refund->created_at)),
                );
                $k++;
            }
        }
        return response()->json($response);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel_order($ord_id, $seller_id, $pTrckId)
    {
        try {
            //foreach($package_tracking_nos as $package_tracking_no){
            $fedex_cancel_order = app('App\Http\Controllers\User\FedexController')->fedex_cancel_pending_shipment($pTrckId);
            //}
        } catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
        $message = '';
        if($fedex_cancel_order->Notifications[0]->Code == '0000'){
                /*DB::table('orders')
        ->where('id', $ord_id)
        ->update(['cancel_status' => '2']);*/
        $order_details = DB::table('order_items')
        ->select('order_items.book_id', 'order_items.quantity')
        ->leftJoin('books', 'order_items.book_id', '=', 'books.id')
        ->where('order_items.order_id', '=', $ord_id)
        ->where('books.user_id', '=', $seller_id)
        ->get();
        if(count($order_details) > 0) {
            foreach($order_details as $order_detail) {
                DB::table('books')
                ->where('id', $order_detail->book_id)
                ->increment('quantity', $order_detail->quantity);
            }
        }
        $message = 'Order Canceled successfully';
        } else {
            $message = $fedex_cancel_order->Notifications[0]->Message;
        }
        return Response::json(['message' => $message]);
    }
}
