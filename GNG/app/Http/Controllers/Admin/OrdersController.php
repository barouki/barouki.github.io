<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect;
use URL;
use Crypt;
use Session;
use DB;
use App\Admin;
use App\User;
use App\Category;
use App\Product;
use App\Unit;
use App\ProductPriceUnit;
use App\DeliveryBoy;
use App\Order;
use App\OrdersItem;
use App\OrderReview;
use App\Complaint;
use App\Common;
use App\Notification;
use Carbon\Carbon;
use File;
use Storage;

class OrdersController extends Controller
{

	public function viewListOrder()
	{
		$total_order = Order::count();
		$total_processing_order = Order::where('status',1)->count();
		$total_confirmed_order = Order::where('status',2)->orWhere('status',6)->count();
		$total_onhold_order = Order::where('status',4)->count();
		$total_completed_order = Order::where('status',3)->count();
		$total_cancelled_order = Order::where('status',5)->count();
		return view('admin.orders.order_list')->with('total_order',$total_order)->with('total_processing_order',$total_processing_order)->with('total_confirmed_order',$total_confirmed_order)->with('total_onhold_order',$total_onhold_order)->with('total_completed_order',$total_completed_order)->with('total_cancelled_order',$total_cancelled_order);
	}
	
    public function viewOrder($order_id="")
	{
		$orderData = Order::select('tbl_orders.*', 'D.*', 'U.fullname', 'U.mobile_no as dmobile_no')
			->leftjoin('tbl_user_delivery_address as D', 'tbl_orders.delivery_address_id', 'D.delivery_address_id')
			->leftjoin('tbl_users as U', 'tbl_orders.delivery_boy_user_id', 'U.user_id')
            ->where('tbl_orders.order_id',$order_id)
			->first();

		$itemData = OrdersItem::select('tbl_orders_item.*','P.product_id','P.coupon_code','P.name','P.product_image', 'PU.price', 'PU.unit', 'U.unit_name')
			->leftjoin('tbl_products as P', 'tbl_orders_item.product_id', 'P.product_id')
			->leftjoin('tbl_product_price_unit as PU', 'tbl_orders_item.price_unit_id', 'PU.price_unit_id')
			->leftjoin('tbl_unit as U', 'PU.unit_id', 'U.id')
			->where('tbl_orders_item.order_id',$order_id)
			->get();

		return view('admin.orders.order_invoice')->with('order_data',$orderData)->with('item_data',$itemData);
	}
	
	public function viewUpdateOrder($id="")
	{
		$data = Product::where('product_id',$id)->first();
		$categorydata = Category::get();
		$ProductPriceUnitData = ProductPriceUnit::where('product_id',$id)->get();
		$unitdata = Unit::get();
		return view('admin.product.product_addupdatedetail')->with('categorydata',$categorydata)->with('unitdata',$unitdata)->with('ProductPriceUnitData',$ProductPriceUnitData)->with('data',$data)->with('title','Edit');;
	}
	
	public function assignDeliveryBoy(Request $request){
		$order_id = $request->input('order_id');
		$delivery_boy_user_id = $request->input('delivery_boy_user_id');
		
		$result =  Order::where('order_id',$order_id)->update(["delivery_boy_user_id"=>$delivery_boy_user_id,"assigned_at"=>date('Y-m-d H:i:s'),"status"=>2]);
		$total_order = Order::count();
		$total_processing_order = Order::where('status',1)->count();
		$total_confirmed_order = Order::where('status',2)->orWhere('status',6)->count();
		$total_onhold_order = Order::where('status',4)->count();
		$total_completed_order = Order::where('status',3)->count();
		$total_cancelled_order = Order::where('status',5)->count();
		if ($result) {
			$orderData = Order::where('order_id',$order_id)->first();
			$user_id = $orderData->user_id;
			$userData = User::where('user_id',$user_id)->first();
			if($userData->device_token){
				$message = 'Hey '.$userData->first_name.' '.$userData->last_name.', Your Order('.$order_id.') is Confirmed';
				
				$is_send = Common::send_push($userData->device_token,'Order Confirmed',$message,$userData->device_type);
				if( $is_send ){
					$notificationdata = array(
						'user_id'=>$user_id,
						'item_id'=>$order_id,
						'notification_type'=>2,
						'title'=>'Order Confirmed',
						'message'=>$message,
					);
	
					Notification::insert($notificationdata);
				}
			}
			$delivery_boyuserData = User::where('user_id',$delivery_boy_user_id)->first();
			if($delivery_boyuserData->device_token){
				$message2 = 'hello, '.$delivery_boyuserData->fullname.', you have new delivery';
				$is_send = Common::send_push($delivery_boyuserData->device_token,'New Delivery',$message2,$delivery_boyuserData->device_type);
				if( $is_send ){
					$notificationdata2 = array(
						'user_id'=>$user_id,
						'item_id'=>$delivery_boy_user_id,
						'notification_type'=>1,
						'title'=>'New Delivery',
						'message'=>$message2,
					);
	
					Notification::insert($notificationdata2);
				}
			}

			$response['success'] = 1;
			$response['message'] = "Successfully Assigned User";
			$response['total_order'] = $total_order;
			$response['total_processing_order'] = $total_processing_order;
			$response['total_confirmed_order'] = $total_confirmed_order;
			$response['total_onhold_order'] = $total_onhold_order;
			$response['total_completed_order'] = $total_completed_order;
			$response['total_cancelled_order'] = $total_cancelled_order;
		} else {
			$response['success'] = 0;
			$response['message'] = "Error While Assigned User";
			$response['total_order'] = 0;
			$response['total_processing_order'] = 0;
			$response['total_confirmed_order'] = 0;
			$response['total_onhold_order'] = 0;
			$response['total_completed_order'] = 0;
			$response['total_cancelled_order'] = 0;
		}
		echo json_encode($response);
	}

	public function deleteOrder(Request $request){
		$order_id = $request->input('order_id');
		$result =  Order::where('order_id',$order_id)->delete();
		OrdersItem::where('order_id',$order_id)->delete();
		OrderReview::where('order_id',$order_id)->delete();

		$total_order = Order::count();
		$total_processing_order = Order::where('status',1)->count();
		$total_confirmed_order = Order::where('status',2)->orWhere('status',6)->count();
		$total_onhold_order = Order::where('status',4)->count();
		$total_completed_order = Order::where('status',3)->count();
		$total_cancelled_order = Order::where('status',5)->count();

		if ($result) {
			$response['success'] = 1;
			$response['total_order'] = $total_order;
			$response['total_processing_order'] = $total_processing_order;
			$response['total_confirmed_order'] = $total_confirmed_order;
			$response['total_onhold_order'] = $total_onhold_order;
			$response['total_completed_order'] = $total_completed_order;
			$response['total_cancelled_order'] = $total_cancelled_order;
		} else {
			$response['success'] = 0;
			$response['total_order'] = 0;
			$response['total_processing_order'] = 0;
			$response['total_confirmed_order'] = 0;
			$response['total_onhold_order'] = 0;
			$response['total_completed_order'] = 0;
			$response['total_cancelled_order'] = 0;
		}
		echo json_encode($response);
	}

	public function viewListComplaint()
	{
		$total_complaint = Complaint::count();
		$total_open_complaint = Complaint::where('status',1)->count();
		$total_close_complaint = Complaint::where('status',0)->count();
		return view('admin.orders.complaint_list')->with('total_complaint',$total_complaint)->with('total_open_complaint',$total_open_complaint)->with('total_close_complaint',$total_close_complaint);
	}

	public function viewComplaint($complaint_id){

		$data = Complaint::select('tbl_complaint.*','U.first_name','U.last_name','U.profile_image')->where('complaint_id',$complaint_id)->leftjoin('tbl_users as U', 'tbl_complaint.user_id', 'U.user_id')->first();
		return view('admin.orders.complaints_view')->with('data',$data);
	}

	public function changeComplaintStatus(Request $request){
		$complaint_id = $request->input('complaint_id');
		
		$status = 0;

		$result =  Complaint::where('complaint_id',$complaint_id)->update(["status"=>$status]);
		$total_complaint = Complaint::count();
		$total_open_complaint = Complaint::where('status',1)->count();
		$total_close_complaint = Complaint::where('status',0)->count();
		if ($result) {
			$data = Complaint::where('complaint_id',$complaint_id)->first();
			$user_id = $data['user_id'];
			$userData = User::where('user_id',$user_id)->first();
			if($userData->device_token){
				$message = 'Hey '.$userData->first_name.' '.$userData->last_name.', Your Complaint is Resolved';
				
				$is_send = Common::send_push($userData->device_token,'Complaint Resolved',$message,$userData->device_type);
				if($is_send){
					$notificationdata = array(
						'user_id'=>$user_id,
						'item_id'=>$user_id,
						'notification_type'=>6,
						'title'=>'Complaint Resolved',
						'message'=>$message,
					);
	
					Notification::insert($notificationdata);
				}
			}
			$response['success'] = 1;
			$response['total_complaint'] = $total_complaint;
			$response['total_open_complaint'] = $total_open_complaint;
			$response['total_close_complaint'] = $total_close_complaint;
		} else {
			$response['success'] = 0;
			$response['total_complaint'] = 0;
			$response['total_open_complaint'] = 0;
			$response['total_close_complaint'] = 0;
		}
		echo json_encode($response);
	}

	public function deleteComplaint(Request $request){
		$complaint_id = $request->input('complaint_id');
		$result =  Complaint::where('complaint_id',$complaint_id)->delete();
		$total_complaint = Complaint::count();
		$total_open_complaint = Complaint::where('status',1)->count();
		$total_close_complaint = Complaint::where('status',0)->count();
		if ($result) {
			$response['success'] = 1;
			$response['total_complaint'] = $total_complaint;
			$response['total_open_complaint'] = $total_open_complaint;
			$response['total_close_complaint'] = $total_close_complaint;
		} else {
			$response['success'] = 0;
			$response['total_complaint'] = 0;
			$response['total_open_complaint'] = 0;
			$response['total_close_complaint'] = 0;
		}
		echo json_encode($response);
	}

	
	public function viewListDeliveryUsers()
	{
		$total_delivery_boy = User::where('user_type',1)->count();
		return view('admin.orders.delivery_users_list')->with('total_delivery_boy',$total_delivery_boy);
	}
	
	public function getDeliveryBoyList()
	{
		$data = User::where('user_type',1)->where('status',1)->get();

		if ($data) {
			$response['success'] = 1;
			$response['data'] = $data;
		} else {
			$response['success'] = 0;
			$response['data'] = [];
		}
		echo json_encode($response);
	}

    public function viewAddDeliveryUsers()
	{
		return view('admin.orders.delivery_addupdatedetail')->with('data',"")->with('title','Add');
	}

    public function viewDeliveryUsers($user_id="")
	{
		$data = User::where('user_id',$user_id)->where('user_type',1)->first();
		$itemData = Order::select('*')
		->where('delivery_boy_user_id',$user_id)
		->where(function($query) {
			$query->where('status', 2);
			$query->orWhere('status', 3);
			$query->orWhere('status', 4);
		})
		->get();
		$payData = DeliveryBoy::where('user_id', $user_id)->where('status', 1)->sum('amount_to_pay');
		return view('admin.orders.user_profile')->with('data',$data)->with('itemData',$itemData)->with('payData',$payData);
	}
	
	public function CheckExistUser(Request $request)
	{
		$username = $request->input('username');
		$user_id = $request->input('user_id');

		if(!empty($user_id)){
			$checkUser = User::selectRaw('*')->where('username',$username)->where('user_type',1)->where('user_id','!=',$user_id)->first();
		}else{
			$checkUser = User::selectRaw('*')->where('username',$username)->where('user_type',1)->first();
		}

		if(!empty($checkUser)) {
			return json_encode(FALSE);
		}else{
			return json_encode(TRUE);
		}
	}

	public function addEditDeliveryUsers(Request $request){
		
		$username = $request->input('username');
		$password = $request->input('password');
		$fullname = $request->input('fullname');
		$mobile_no = $request->input('mobile_no');
		$user_id = $request->input('user_id');
	
		$data['username'] = $username;
		if($password){
			$data['password'] = bcrypt($password);
		}
		$data['fullname'] = $fullname;
		$data['mobile_no'] = $mobile_no;
		$data['user_type'] = 1;
		if(!empty($user_id)){
			$result =  User::where('user_id',$user_id)->update($data);
			$msg = "Update";
			$response['flag'] = 2;
		}else{
			$user_id = User::get_random_string();
			$data['user_id'] = $user_id;
			$result =  User::insert($data);
			$msg = "Add";
			$response['flag'] = 1;
		}
		$total_delivery_boy = User::where('user_type',1)->count();
		if ($result) {
			$response['success'] = 1;
			$response['message'] = "Successfully ".$msg." Delivery User";
			$response['total_delivery_boy'] = $total_delivery_boy;
		} else {
			$response['success'] = 0;
			$response['message'] = "Error While ".$msg." Delivery User";
			$response['total_delivery_boy'] = 0;
		}
		echo json_encode($response);
	}

	public function deleteDeliveryUsers(Request $request){
		$user_id = $request->input('user_id');
		
		$result =  User::where('user_id',$user_id)->where('user_type',1)->delete();
		$total_delivery_boy = User::where('user_type',1)->count();
		if ($result) {
			$response['success'] = 1;
			$response['total_delivery_boy'] = $total_delivery_boy;
		} else {
			$response['success'] = 0;
			$response['total_delivery_boy'] = 0;
		}
		echo json_encode($response);
	}
	
	public function paymentResolve(Request $request){
		$user_id = $request->input('user_id');
		
		$result =  DeliveryBoy::where('user_id',$user_id)->update(['status'=>2]);

		if ($result) {
			$response['success'] = 1;
		} else {
			$response['success'] = 0;
		}
		echo json_encode($response);
	}

	public function viewListReviewRatings()
	{
		$total_review = OrderReview::count();
		return view('admin.orders.review_list')->with('total_review',$total_review);
	}
	
	public function showOrderList(Request $request)
    {

		$columns = array( 
			0=> 'ordered_at',
			1 =>'order_id', 
			2 =>'first_name',
			3=> 'total_amount',
			4=> 'status',
			5=> 'payment_type',
			6=> 'ordered_at',
		);

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		$status= $request->input("status");
		$startdate= $request->input("startdate");
		$enddate= $request->input("enddate");
	

		if(empty($request->input('search.value')))
		{      
			if(!empty($status))
			{
				if(!empty($startdate) && !empty($enddate))
				{
					// $from = date('2018-01-01');
					// $to = date('2018-05-02');

					$OrderData = Order::select('tbl_orders.*', 'D.first_name', 'D.last_name')
						->leftjoin('tbl_user_delivery_address as D', 'tbl_orders.delivery_address_id', 'D.delivery_address_id')
						->where('tbl_orders.status',$status)
						->whereBetween('tbl_orders.ordered_at', [$startdate, $enddate])
						->offset($start)
						->limit($limit)
						->orderBy($order,$dir)
						->get();

					$totalData =  Order::where('status',$status)->whereBetween('ordered_at',[$startdate, $enddate])->count();
					$totalFiltered = $totalData; 
				}else{
					$OrderData = Order::select('tbl_orders.*', 'D.first_name', 'D.last_name')
						->leftjoin('tbl_user_delivery_address as D', 'tbl_orders.delivery_address_id', 'D.delivery_address_id')
						->where('tbl_orders.status',$status)
						->offset($start)
						->limit($limit)
						->orderBy($order,$dir)
						->get();

					$totalData =  Order::where('status',$status)->count();
					$totalFiltered = $totalData; 
				}
			}else{
				if(!empty($startdate) && !empty($enddate))
				{
					// $from = date('2018-01-01');
					// $to = date('2018-05-02');

					$OrderData = Order::select('tbl_orders.*', 'D.first_name', 'D.last_name')
						->leftjoin('tbl_user_delivery_address as D', 'tbl_orders.delivery_address_id', 'D.delivery_address_id')
						->whereBetween('tbl_orders.ordered_at', [$startdate, $enddate])
						->offset($start)
						->limit($limit)
						->orderBy($order,$dir)
						->get();

					$totalData =  Order::whereBetween('ordered_at',[$startdate, $enddate])->count();
					$totalFiltered = $totalData; 
				}else{
					$OrderData = Order::select('tbl_orders.*', 'D.first_name', 'D.last_name')
						->leftjoin('tbl_user_delivery_address as D', 'tbl_orders.delivery_address_id', 'D.delivery_address_id')
						->offset($start)
						->limit($limit)
						->orderBy($order,$dir)
						->get();

					$totalData =  Order::count();
					$totalFiltered = $totalData; 
				}
			}
		}
		else {
			$totalData =  Order::count();
			$totalFiltered = $totalData; 
			$search = $request->input('search.value'); 
			if(!empty($status))
			{   
				if(!empty($startdate) && !empty($enddate))
				{   
					$OrderData =  Order::select('tbl_orders.*', 'D.first_name', 'D.last_name')
							->leftjoin('tbl_user_delivery_address as D', 'tbl_orders.delivery_address_id', 'D.delivery_address_id')
							->where('tbl_orders.status',$status)
							->whereBetween('tbl_orders.ordered_at', [$startdate, $enddate])
							->where('tbl_orders.id','LIKE',"%{$search}%")
							->orWhere('tbl_orders.status','LIKE',"%{$search}%")
							->orWhere('D.first_name','LIKE',"%{$search}%")
							->orWhere('D.last_name','LIKE',"%{$search}%")
							->offset($start)
							->limit($limit)
							->orderBy($order,$dir)
							->get();

					$totalFiltered = Order::select('tbl_orders.*', 'C.category_name')
							->leftjoin('tbl_category as C', 'tbl_orders.category_id', 'C.id')
							->where('tbl_orders.status',$status)
							->whereBetween('tbl_orders.ordered_at', [$startdate, $enddate])
							->where('tbl_orders.id','LIKE',"%{$search}%")
							->orWhere('tbl_orders.status','LIKE',"%{$search}%")
							->orWhere('D.first_name','LIKE',"%{$search}%")
							->orWhere('D.last_name','LIKE',"%{$search}%")
							->count();
				}else{
					$OrderData =  Order::select('tbl_orders.*', 'D.first_name', 'D.last_name')
							->leftjoin('tbl_user_delivery_address as D', 'tbl_orders.delivery_address_id', 'D.delivery_address_id')
							->where('tbl_orders.status',$status)
							->where('tbl_orders.id','LIKE',"%{$search}%")
							->orWhere('tbl_orders.status','LIKE',"%{$search}%")
							->orWhere('D.first_name','LIKE',"%{$search}%")
							->orWhere('D.last_name','LIKE',"%{$search}%")
							->offset($start)
							->limit($limit)
							->orderBy($order,$dir)
							->get();

					$totalFiltered = Order::select('tbl_orders.*', 'C.category_name')
							->leftjoin('tbl_category as C', 'tbl_orders.category_id', 'C.id')
							->where('tbl_orders.status',$status)
							->where('tbl_orders.id','LIKE',"%{$search}%")
							->orWhere('tbl_orders.status','LIKE',"%{$search}%")
							->orWhere('D.first_name','LIKE',"%{$search}%")
							->orWhere('D.last_name','LIKE',"%{$search}%")
							->count();
				}
			}else{
				if(!empty($startdate) && !empty($enddate))
				{ 
					$OrderData =  Order::select('tbl_orders.*', 'D.first_name', 'D.last_name')
						->leftjoin('tbl_user_delivery_address as D', 'tbl_orders.delivery_address_id', 'D.delivery_address_id')
						->whereBetween('tbl_orders.ordered_at', [$startdate, $enddate])
						->where('tbl_orders.id','LIKE',"%{$search}%")
						->orWhere('tbl_orders.status','LIKE',"%{$search}%")
						->orWhere('D.first_name','LIKE',"%{$search}%")
						->orWhere('D.last_name','LIKE',"%{$search}%")
						->offset($start)
						->limit($limit)
						->orderBy($order,$dir)
						->get();

					$totalFiltered = Order::select('tbl_orders.*', 'C.category_name')
						->leftjoin('tbl_category as C', 'tbl_orders.category_id', 'C.id')
						->whereBetween('tbl_orders.ordered_at', [$startdate, $enddate])
						->where('tbl_orders.id','LIKE',"%{$search}%")
						->orWhere('tbl_orders.status','LIKE',"%{$search}%")
						->orWhere('D.first_name','LIKE',"%{$search}%")
						->orWhere('D.last_name','LIKE',"%{$search}%")
						->count();
				}else{
					$OrderData =  Order::select('tbl_orders.*', 'D.first_name', 'D.last_name')
							->leftjoin('tbl_user_delivery_address as D', 'tbl_orders.delivery_address_id', 'D.delivery_address_id')
							->where('tbl_orders.id','LIKE',"%{$search}%")
							->orWhere('tbl_orders.status','LIKE',"%{$search}%")
							->orWhere('D.first_name','LIKE',"%{$search}%")
							->orWhere('D.last_name','LIKE',"%{$search}%")
							->offset($start)
							->limit($limit)
							->orderBy($order,$dir)
							->get();

					$totalFiltered = Order::select('tbl_orders.*', 'C.category_name')
						->leftjoin('tbl_category as C', 'tbl_orders.category_id', 'C.id')
						->where('tbl_orders.id','LIKE',"%{$search}%")
						->orWhere('tbl_orders.status','LIKE',"%{$search}%")
						->orWhere('D.first_name','LIKE',"%{$search}%")
						->orWhere('D.last_name','LIKE',"%{$search}%")
						->count();
				}
			}
		}
		
		$data = array();
		if(!empty($OrderData))
		{
			foreach ($OrderData as $rows)
			{
				$show =  route('order/view',$rows->order_id);
				$edit =  route('order/edit',$rows->order_id);	
				if($rows->status == 1){
					$status = '<a class="badge badge-info text-white">Processing</a>';
				}else  if($rows->status == 2){
					$status = '<a class="badge badge-primary text-white">Confirmed</a>';
				}else  if($rows->status == 3){
					$status = '<a class="badge badge-success text-white">Completed</a>';
				}else  if($rows->status == 4){
					$status = '<a class="badge badge-dark text-white">On Hold</a>';
				}  if($rows->status == 5){
					$status = '<a class="badge badge-danger text-white">Cancelled</a>';
				}
				$start_delivery = '';
				if($rows->status == 1 || $rows->status == 4){
					$start_delivery = '<a class="assignUser" data-toggle="modal" data-target="#assignUserModal" data-id="'.$rows->order_id.'" data-delivery_boy_user_id="'.$rows->delivery_boy_user_id.'" title="Start Delivery"><i class="i-cl-3 fas fa-truck col-dark font-20 pointer p-l-5 p-r-5"></i></a>';
				}

				// $complete_payment = '';
				// if($rows->status == 3){
				// 	$complete_payment = '<a class="assignUser" data-toggle="modal" data-target="#assignUserModal" data-id="'.$rows->order_id.'" data-delivery_boy_user_id="'.$rows->delivery_boy_user_id.'" title="Start Delivery"><i class="i-cl-3 fas fa-truck col-dark font-20 pointer p-l-5 p-r-5"></i></a>';
				// }

				if($rows->payment_type == 1){
					$payment_type = 'Cash on Delivery';
				}else  if($rows->payment_type == 2){
					$payment_type = 'Card Payment';
				}
				$date = date('Y-m-d H:i:s A',strtotime($rows->ordered_at)); 
				// $date = Carbon::createFromFormat('Y-m-d H:i:s', $tz, 'UTC')->format('Y-m-d H:i:s');
				$data[]= array(
					$rows->order_id,
					$rows->first_name.' '.$rows->last_name,
					'$'.$rows->total_amount,
					$status,
					$payment_type,
					$date,
					'<a href="'.$show.'" class="edit" title="View Order"><i class="i-cl-3 fas fa-eye col-green font-20 pointer p-l-5 p-r-5"></i></a>
					<a class="delete" id="orderDelete" data-id="'.$rows->order_id.'"><i class="fa fa-trash text-danger font-20 pointer p-l-5 p-r-5"></i></a>',
					$start_delivery
				); 
			}
		}

		$json_data = array(
			"draw"            => intval($request->input('draw')),  
			"recordsTotal"    => intval($totalData),  
			"recordsFiltered" => intval($totalFiltered), 
			"data"            => $data   
			);

		echo json_encode($json_data); 
        exit();
	}
	
	public function showOpenComplaintList(Request $request)
    {

		$columns = array( 
			0 =>'created_at', 
			1 =>'complaint_id', 
			2 =>'order_id', 
			3 =>'first_name',
			4=> 'last_name',
			5=> 'status',
			6=> 'created_at',
		);

		$totalData = Complaint::count();

		$totalFiltered = $totalData; 

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value')))
		{      
			
			$ComplaintData = Complaint::select('tbl_complaint.*', 'U.first_name', 'U.last_name')
				->leftjoin('tbl_users as U', 'tbl_complaint.user_id', 'U.user_id')
				->where('tbl_complaint.status',1)
				->offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
		}
		else {
			$search = $request->input('search.value'); 

			$ComplaintData =  Complaint::select('tbl_complaint.*', 'U.first_name', 'U.last_name')
					->leftjoin('tbl_users as U', 'tbl_complaint.user_id', 'U.user_id')
					->where('tbl_complaint.status',1)
					->where('tbl_complaint.id','LIKE',"%{$search}%")
					->orWhere('tbl_complaint.order_id','LIKE',"%{$search}%")
					->orWhere('tbl_complaint.status','LIKE',"%{$search}%")
					->orWhere('U.first_name','LIKE',"%{$search}%")
					->orWhere('U.last_name','LIKE',"%{$search}%")
					->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();

			$totalFiltered = Complaint::select('tbl_complaint.*', 'U.first_name', 'U.last_name')
					->leftjoin('tbl_users as U', 'tbl_complaint.user_id', 'U.user_id')
					->where('tbl_complaint.status',1)
					->where('tbl_complaint.id','LIKE',"%{$search}%")
					->orWhere('tbl_complaint.order_id','LIKE',"%{$search}%")
					->orWhere('tbl_complaint.status','LIKE',"%{$search}%")
					->orWhere('U.first_name','LIKE',"%{$search}%")
					->orWhere('U.last_name','LIKE',"%{$search}%")
					->count();
		}

		$data = array();
		if(!empty($ComplaintData))
		{
			foreach ($ComplaintData as $rows)
			{
				$show =  route('complaint/view',$rows->complaint_id);
				
				$status = '<a class="badge badge-danger text-white">Open</a>';
				// $tz = $rows->created_at; 
				$date = date('Y-m-d H:i:s A',strtotime($rows->created_at)); 
				// $date = Carbon::createFromFormat('Y-m-d H:i:s', $tz, 'UTC')->format('Y-m-d');
				$data[]= array(
					$rows->complaint_id,
					$rows->order_id,
					$rows->first_name.' '.$rows->last_name,
					$status,
					$date,
					$btn =  '<a id="changeComplaintStatus" data-id="'.$rows->complaint_id.'"class="text-danger" title="Move to Close"><i class="fas fa-share-square col-blue font-20 pointer p-l-5 p-r-5"></i></a>',
					'<a href="'.$show.'" class="edit" title="View Complaint"><i class="i-cl-3 fa fa-eye col-green font-20 pointer p-l-5 p-r-5"></i></a>
					<a class="delete" id="complaintDelete" data-id="'.$rows->complaint_id.'"><i class="fa fa-trash text-danger font-20 pointer p-l-5 p-r-5"></i></a>'
				); 

			}
		}

		$json_data = array(
			"draw"            => intval($request->input('draw')),  
			"recordsTotal"    => intval($totalData),  
			"recordsFiltered" => intval($totalFiltered), 
			"data"            => $data   
			);

		echo json_encode($json_data); 
        exit();
	}

	public function showCloseComplaintList(Request $request)
    {

		$columns = array( 
			0=> 'created_at',
			1 =>'complaint_id', 
			2 =>'order_id', 
			3 =>'first_name',
			4=> 'last_name',
			5=> 'status',
			6=> 'created_at',
		);

		$totalData = Complaint::count();

		$totalFiltered = $totalData; 

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value')))
		{      
			
			$ComplaintData = Complaint::select('tbl_complaint.*', 'U.first_name', 'U.last_name')
				->leftjoin('tbl_users as U', 'tbl_complaint.user_id', 'U.user_id')
				->where('tbl_complaint.status',0)
				->offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
		}
		else {
			$search = $request->input('search.value'); 

			$ComplaintData =  Complaint::select('tbl_complaint.*', 'U.first_name', 'U.last_name')
					->leftjoin('tbl_users as U', 'tbl_complaint.user_id', 'U.user_id')
					->where('tbl_complaint.status',0)
					->where('tbl_complaint.id','LIKE',"%{$search}%")
					->orWhere('tbl_complaint.order_id','LIKE',"%{$search}%")
					->orWhere('tbl_complaint.status','LIKE',"%{$search}%")
					->orWhere('U.first_name','LIKE',"%{$search}%")
					->orWhere('U.last_name','LIKE',"%{$search}%")
					->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();

			$totalFiltered = Complaint::select('tbl_complaint.*', 'U.first_name', 'U.last_name')
					->leftjoin('tbl_users as U', 'tbl_complaint.user_id', 'U.user_id')
					->where('tbl_complaint.status',0)
					->where('tbl_complaint.id','LIKE',"%{$search}%")
					->orWhere('tbl_complaint.order_id','LIKE',"%{$search}%")
					->orWhere('tbl_complaint.status','LIKE',"%{$search}%")
					->orWhere('U.first_name','LIKE',"%{$search}%")
					->orWhere('U.last_name','LIKE',"%{$search}%")
					->count();
		}

		$data = array();
		if(!empty($ComplaintData))
		{
			foreach ($ComplaintData as $rows)
			{
				$show =  route('complaint/view',$rows->complaint_id);
				
				$status = '<a class="badge badge-success text-white">Close</a>';
				// $tz = $rows->created_at; 
				$date = date('Y-m-d H:i:s A',strtotime($rows->created_at)); 
				// $date = Carbon::createFromFormat('Y-m-d H:i:s', $tz, 'UTC')->format('Y-m-d');
				$data[]= array(
					$rows->complaint_id,
					$rows->order_id,
					$rows->first_name.' '.$rows->last_name,
					$status,
					$date,
					'<a href="'.$show.'" class="edit" title="View Complaint"><i class="i-cl-3 fa fa-eye col-green font-20 pointer p-l-5 p-r-5"></i></a>
					<a class="delete" id="complaintDelete" data-id="'.$rows->complaint_id.'"><i class="fa fa-trash text-danger font-20 pointer p-l-5 p-r-5"></i></a>'
				); 

			}
		}

		$json_data = array(
			"draw"            => intval($request->input('draw')),  
			"recordsTotal"    => intval($totalData),  
			"recordsFiltered" => intval($totalFiltered), 
			"data"            => $data   
			);

		echo json_encode($json_data); 
        exit();
	}

	public function showDeliveryUsersList(Request $request)
    {

		$columns = array( 
			0 =>'user_id', 
			1 =>'username',
			2 =>'fullname',
			3 =>'mobile_no',
		);

		$totalData = User::select('*')->where('user_type',1)->count();

		$totalFiltered = $totalData; 

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value')))
		{   
			$DeliveryUserData = User::select('*')
			->where('user_type',1)
			->orderBy($order,$dir)
			->offset($start)
			->limit($limit)
			->get();        
			
		}
		else {
		$search = $request->input('search.value'); 

		$DeliveryUserData =  User::select('*')->where('user_type',1)->where('user_id','LIKE',"%{$search}%")
					->orWhere('username', 'LIKE',"%{$search}%")	
					->orWhere('fullname', 'LIKE',"%{$search}%")	
					->orWhere('mobile_no', 'LIKE',"%{$search}%")					
					->orderBy($order,$dir)
					->offset($start)
					->limit($limit)
					->get();

		$totalFiltered = User::where('user_type',1)->where('user_id','LIKE',"%{$search}%")
					->orWhere('username', 'LIKE',"%{$search}%")
					->orWhere('fullname', 'LIKE',"%{$search}%")	
					->orWhere('mobile_no', 'LIKE',"%{$search}%")	
					->count();
		}

		$data = array();
		if(!empty($DeliveryUserData))
		{
			foreach ($DeliveryUserData as $rows)
			{
				$payData = DeliveryBoy::where('user_id', $rows->user_id)->where('status', 1)->sum('amount_to_pay');

				$show =  route('delivery/user/details',$rows->user_id);	

				if(!empty($rows->profile_image))
                {
                    $profile = '<img height="60px;" width="60px;" src="'.env('DEFAULT_IMAGE_URL').$rows->profile_image.'" class="" alt="">';
                }
                else
                {
                    $profile = '<img height="60px;" width="60px;" src="'.asset('assets/dist/img/default.png').'" class="" alt="">';
				}
				
				$data[]= array(
					$profile,
					$rows->username,
					$rows->mobile_no,
					$rows->fullname,
					'$'.$payData,
					'<a href="'.$show.'" class="edit" title="View Details"><i class="i-cl-3 fa fa-eye col-green font-20 pointer p-l-5 p-r-5"></i></a>
					<a data-toggle="modal" data-target="#deliveryUserModal" data-id="'.$rows->user_id.'" data-username="'.$rows->username.'" data-mobile_no="'.$rows->mobile_no.'" data-fullname="'.$rows->fullname.'" class="updateDeliveryUser" title="Edit User"><i class="i-cl-3 fa fa-edit col-blue font-20 pointer p-l-5 p-r-5"></i></a>
					<a class="delete" id="deliveryDelete" data-id="'.$rows->user_id.'"><i class="fa fa-trash text-danger font-20 pointer p-l-5 p-r-5"></i></a>'
				); 
			}
		}

		$json_data = array(
			"draw"            => intval($request->input('draw')),  
			"recordsTotal"    => intval($totalData),  
			"recordsFiltered" => intval($totalFiltered), 
			"data"            => $data   
			);

		echo json_encode($json_data); 
        exit();
	}
	

	
	public function showDeliveryOrderList(Request $request)
    {

		$columns = array( 
			0 =>'ordered_at',
			1 =>'order_id',  
			2 =>'first_name',
			3=> 'area',
			4=> 'total_amount',
			5=> 'payment_type',
			6=> 'status',
			7=> 'ordered_at',
			8=> 'completed_at',
			8=> 'status',
		);

		$totalData = Order::count();

		$totalFiltered = $totalData; 

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		$status= $request->input("status");
		$user_id= $request->input("user_id");
		if(empty($request->input('search.value')))
		{      
			if(!empty($status))
			{
				$OrderData = Order::select('tbl_orders.*', 'U.profile_image', 'D.first_name', 'D.last_name','D.mobile_number','D.alt_mobile_number','D.home_no','D.street','D.landmark','D.city','D.area','D.society','D.pincode','D.latitude','D.longitude')
					->leftjoin('tbl_user_delivery_address as D', 'tbl_orders.delivery_address_id', 'D.delivery_address_id')
					->leftjoin('tbl_users as U', 'tbl_orders.user_id', 'U.user_id')
					->where('tbl_orders.delivery_boy_user_id',$user_id)
					->where('tbl_orders.status',$status)
					->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();
			}
			
		}
		else {
			$search = $request->input('search.value'); 
			if(!empty($status))
			{   
				$OrderData = Order::select('tbl_orders.*', 'U.profile_image', 'D.first_name', 'D.last_name','D.mobile_number','D.alt_mobile_number','D.home_no','D.street','D.landmark','D.city','D.area','D.society','D.pincode','D.latitude','D.longitude')
				->leftjoin('tbl_user_delivery_address as D', 'tbl_orders.delivery_address_id', 'D.delivery_address_id')
				->leftjoin('tbl_users as U', 'tbl_orders.user_id', 'U.user_id')
				->where('tbl_orders.delivery_boy_user_id',$user_id)
				->where('tbl_orders.status',$status)
				->where('tbl_orders.id','LIKE',"%{$search}%")
				->orWhere('tbl_orders.status','LIKE',"%{$search}%")
				->orWhere('D.first_name','LIKE',"%{$search}%")
				->orWhere('D.last_name','LIKE',"%{$search}%")
				->orWhere('D.area','LIKE',"%{$search}%")
				->offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();

				$totalFiltered = Order::select('tbl_orders.*', 'U.profile_image', 'D.first_name', 'D.last_name','D.mobile_number','D.alt_mobile_number','D.home_no','D.street','D.landmark','D.city','D.area','D.society','D.pincode','D.latitude','D.longitude')
				->leftjoin('tbl_user_delivery_address as D', 'tbl_orders.delivery_address_id', 'D.delivery_address_id')
				->leftjoin('tbl_users as U', 'tbl_orders.user_id', 'U.user_id')
				->where('tbl_orders.delivery_boy_user_id',$user_id)
				->where('tbl_orders.status',$status)
				->where('tbl_orders.id','LIKE',"%{$search}%")
				->orWhere('tbl_orders.status','LIKE',"%{$search}%")
				->orWhere('D.first_name','LIKE',"%{$search}%")
				->orWhere('D.last_name','LIKE',"%{$search}%")
				->orWhere('D.area','LIKE',"%{$search}%")
				->count();
			}
		}

		$data = array();
		if(!empty($OrderData))
		{
			foreach ($OrderData as $rows)
			{
				$payData = DeliveryBoy::where('user_id', $user_id)->where('order_id', $rows->order_id)->first();

				if($rows->status == 2){
					$status = '<a class="badge badge-primary text-white">Confirmed</a>';
				}else  if($rows->status == 3){
					$status = '<a class="badge badge-success text-white">Completed</a>';
				}else  if($rows->status == 4){
					$status = '<a class="badge badge-dark text-white">On Hold</a>';
				}

				if($rows->payment_type == 1){
					$payment_type = 'Cash on Delivery';
				}else  if($rows->payment_type == 2){
					$payment_type = 'Card Payment';
				}
				
				$complete_payment = '';
				$completed_at = '';
				if($rows->status == 3){
					if($rows->payment_type == 1){
						if($payData['status'] == 1){
							$complete_payment = '<a class="badge badge-dark text-white">Not Paid</a>';
						}else{
							$complete_payment = '<a class="badge badge-success text-white">Paid</a>';
						}
					}
					// $tz = $rows->created_at; 
					$date = date('Y-m-d H:i:s A',strtotime($rows->created_at)); 
					// $date = Carbon::createFromFormat('Y-m-d H:i:s', $tz, 'UTC')->format('Y-m-d');
					$completed_at = $date;
				}
				if($rows->status == 3){
					// $tz = $rows->ordered_at; 
					$date = date('Y-m-d H:i:s A',strtotime($rows->ordered_at)); 
					// $date = Carbon::createFromFormat('Y-m-d H:i:s', $tz, 'UTC')->format('Y-m-d');
					$data[]= array(
						$rows->order_id,
						$rows->first_name.' '.$rows->last_name,
						$rows->area,
						'$'.$rows->total_amount,
						$payment_type,
						$status,
						$date,
						$completed_at,
						$complete_payment
					);
				}else{
					// $tz = $rows->ordered_at; 
					$date = date('Y-m-d H:i:s A',strtotime($rows->ordered_at)); 
					// $date = Carbon::createFromFormat('Y-m-d H:i:s', $tz, 'UTC')->format('Y-m-d');
					$data[]= array(
						$rows->order_id,
						$rows->first_name.' '.$rows->last_name,
						$rows->area,
						'$'.$rows->total_amount,
						$payment_type,
						$status,
						$date
					);
				} 

			}
		}

		$json_data = array(
			"draw"            => intval($request->input('draw')),  
			"recordsTotal"    => intval($totalData),  
			"recordsFiltered" => intval($totalFiltered), 
			"data"            => $data   
			);

		echo json_encode($json_data); 
        exit();
	}


	public function showOrderReviewList(Request $request)
    {

		$columns = array( 
			0 =>'created_at', 
			1 =>'order_id', 
			2 =>'user_name', 
			3 =>'review',
			4=> 'rating',
		);

		$totalData = OrderReview::count();

		$totalFiltered = $totalData; 

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value')))
		{      
			
			$OrderReviewtData = OrderReview::select('tbl_order_review.*', 'U.first_name', 'U.last_name')
				->leftjoin('tbl_users as U', 'tbl_order_review.user_id', 'U.user_id')
				->offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
		}
		else {
			$search = $request->input('search.value'); 

			$OrderReviewtData =  OrderReview::select('tbl_order_review.*', 'U.first_name', 'U.last_name')
					->leftjoin('tbl_users as U', 'tbl_order_review.user_id', 'U.user_id')
					->where('tbl_order_review.id','LIKE',"%{$search}%")
					->orWhere('tbl_order_review.order_id','LIKE',"%{$search}%")
					->orWhere('U.first_name','LIKE',"%{$search}%")
					->orWhere('U.last_name','LIKE',"%{$search}%")
					->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();

			$totalFiltered = OrderReview::select('tbl_complaint.*', 'U.first_name', 'U.last_name')
					->leftjoin('tbl_users as U', 'tbl_order_review.user_id', 'U.user_id')
					->where('tbl_order_review.id','LIKE',"%{$search}%")
					->orWhere('tbl_order_review.order_id','LIKE',"%{$search}%")
					->orWhere('U.first_name','LIKE',"%{$search}%")
					->orWhere('U.last_name','LIKE',"%{$search}%")
					->count();
		}

		$data = array();
		if(!empty($OrderReviewtData))
		{
			foreach ($OrderReviewtData as $rows)
			{				
				$show =  route('order/view',$rows->order_id);
				$data[]= array(
					'<a href="'.$show.'" target="_blank">'.$rows->order_id.'</a>',
					$rows->first_name.' '.$rows->last_name,
					$rows->review,
					$rows->rating,
				); 

			}
		}

		$json_data = array(
			"draw"            => intval($request->input('draw')),  
			"recordsTotal"    => intval($totalData),  
			"recordsFiltered" => intval($totalFiltered), 
			"data"            => $data   
			);

		echo json_encode($json_data); 
        exit();
	}

}


 