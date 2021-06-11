<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect;
use URL;
use Hash;
use Session;
use DB;
use App\Admin;
use App\User;
use App\Settings;
use App\Banner;
use App\Area;
use App\City;
use App\FAQ;
use App\Notification;
use App\Common;
use File;
use Storage;

class SettingsController extends Controller
{

	public function viewOtherSettigs()
	{
		$settings = Settings::first();
		return view('admin.settings.settings')->with('data',$settings);
	}

	public function addUpdateShipping(Request $request)
	{
		$shipping_charge = $request->input('shipping_charge');
		$settings = Settings::first();
		$data['shipping_charge'] = $shipping_charge;
		if(!empty($settings['shipping_charge'])){
			$result =  Settings::where('id',$settings['id'])->update($data);
			$msg = "Update";
			$response['flag'] = 2;
		}else{
			$result =  Settings::insert($data);
			$msg = "Add";
			$response['flag'] = 1;
		}
		if ($result) {
			$response['success'] = 1;
			$response['message'] = "Successfully ".$msg." Shipping Charge";
		} else {
			$response['success'] = 0;
			$response['message'] = "Error While ".$msg." Shipping Charge";
		}
		echo json_encode($response);
	}

	public function sendNotification(Request $request)
	{
		$notification_topic = $request->input('notification_topic');
		$notification_title = $request->input('notification_title');
		$notification_message = $request->input('notification_message');
		$settings = Settings::first();

		$imagename = $notification_image = "";
		$s3 = Storage::disk('s3');
		if ($request->hasfile('notify_image')) {
			$file = $request->file('notify_image');
			$imageFileName='notification_' . rand(111,999) . '.' . $file->getClientOriginalExtension();
			$destinationPath = '/uploads/';
			// File::makeDirectory($destinationPath, $mode = 0777, true, true);
			$filePath = $destinationPath . $imageFileName;
			if ($s3->put($filePath, file_get_contents($file)) ){
				$imagename = $imageFileName;
				$notification_image = url(env('DEFAULT_IMAGE_URL').$imagename);
			}
		}
		// $userData = User::where('user_type',0)->get();
		// foreach($userData as $value){
		// 	$is_send = Common::send_push($notification_topic,$value['device_token'],$value['device_type'],$notification_title,$notification_message,0,$notification_image,1);
		// }
		
		$is_send = Common::send_push($notification_topic,$notification_title,$notification_message,0,$notification_image,1);
		
		if($is_send){

			$userData = User::where('user_type',0)->get();

			// foreach($userData as $val){
				$notificationdata = array(
					'user_id'=> '',
					'item_id'=>"",
					'notification_type'=>7,
					'title'=>$notification_title,
					'message'=>$notification_message,
					'image' => $imagename
				);	
			// 	Notification::insert($notificationdata);
			// }

			Notification::insert($notificationdata);

			$response['success'] = 1;
			$response['message'] = "Successfully Send Notification";
		} else {
			$response['success'] = 0;
			$response['message'] = "Error While Send Notification";
		}
		echo json_encode($response);
	}

	public function viewListBanner()
	{
		$total_banner = Banner::count();
		return view('admin.settings.banner_list')->with('total_banner',$total_banner);
	}

	public function addUpdateBanner(Request $request){
		$banner_id = $request->input('banner_id');
		
		$image_arr=[];
		$s3 = Storage::disk('s3');
		if ($request->hasfile('banner_img')) {
			$file = $request->file('banner_img');
			$imageFileName='banner_' . rand(111,999) . '.' . $file->getClientOriginalExtension();
			$destinationPath = '/uploads/';
			// File::makeDirectory($destinationPath, $mode = 0777, true, true);
			$filePath = $destinationPath . $imageFileName;
			if ($s3->put($filePath, file_get_contents($file)) ){
				$data['banner_img'] = $imageFileName;
			}
		}else{
			$data['banner_img'] = $request->input('hidden_banner_img');
		}

		if(!empty($banner_id)){
			$result =  Banner::where('id',$banner_id)->update($data);
			$msg = "Update";
			$response['flag'] = 2;
		}else{
			$result =  Banner::insert($data);
			$banner_id = DB::getPdo()->lastInsertId();
			$msg = "Add";
			$response['flag'] = 1;
		}
		
		$result = Banner::where('id',$banner_id)->first();
		$total_banner = Banner::count();
		if ($result) {
			$response['data'] = $result;
			$response['success'] = 1;
			$response['message'] = "Successfully ".$msg." Banner";
			$response['total_banner'] = $total_banner;
		} else {
			$response['data'] = "";
			$response['success'] = 0;
			$response['message'] = "Error While ".$msg." Banner";
			$response['total_banner'] = 0;
		}
		echo json_encode($response);
	}

	public function deleteBanner(Request $request){

		$banner_id = $request->input('banner_id');
		$result =  Banner::where('id',$banner_id)->delete();
		$total_banner = Banner::count();
		if ($result) {
			$response['success'] = 1;
			$response['total_banner'] = $total_banner;
		} else {
			$response['success'] = 0;
			$response['total_banner'] = 0;
		}
		echo json_encode($response);

	}


	public function viewListFAQ()
	{
		$total_faq = FAQ::count();
		return view('admin.settings.faq_list')->with('total_faq',$total_faq);
	}

	public function viewFAQ($id="")
	{
		$data = FAQ::where('id',$id)->first();
		return view('admin.settings.faq_view')->with('data',$data);
	}

    public function viewAddFAQ()
	{
		return view('admin.settings.faq_addupdate')->with('data',"")->with('title','Add');;
	}
	
	public function viewUpdateFAQ($id="")
	{
		$data = FAQ::where('id',$id)->first();
		return view('admin.settings.faq_addupdate')->with('data',$data)->with('title','Edit');;
	}

	public function addUpdateFAQ(Request $request){
		$id = $request->input('id');
		$question = $request->input('question');
		$answer = $request->input('answer');

		if(!empty($id)){
			$data['question'] = $question;
			$data['answer'] = $answer;
			$result =  FAQ::where('id',$id)->update($data);
			$msg = "Update";
			$response['flag'] = 2;
		}else{
			foreach($question as $key => $value){
				$data['question'] = $value;
				$data['answer'] = $answer[$key];
				$result =  FAQ::insert($data);
			}			
			$msg = "Add";
			$response['flag'] = 1;
		}
		
		$total_faq = FAQ::count();
		if ($result) {
			$response['success'] = 1;
			$response['message'] = "Successfully ".$msg." FAQ";
			$response['total_faq'] = $total_faq;
		} else {
			$response['success'] = 0;
			$response['message'] = "Error While ".$msg." FAQ";
			$response['total_faq'] = 0;
		}
		echo json_encode($response);
	}

	public function deleteFAQ(Request $request){

		$id = $request->input('id');
		$result =  FAQ::where('id',$id)->delete();
		$total_faq = FAQ::count();
		if ($result) {
			$response['success'] = 1;
			$response['total_faq'] = $total_faq;
		} else {
			$response['success'] = 0;
			$response['total_faq'] = 0;
		}
		echo json_encode($response);

	}

	public function viewListCity()
	{
		$data = City::get();
		$total_city = City::count();
		$total_area = City::count();
		return view('admin.settings.address_list')->with('data',$data)->with('total_city',$total_city)->with('total_area',$total_area);
	}

	public function CheckExistCity(Request $request)
	{
		$city_name = $request->input('city_name');
		$city_id = $request->input('city_id');

		if(!empty($city_id)){
			$checkCity = City::selectRaw('*')->where('city_name',$city_name)->where('id','!=',$city_id)->first();
		}else{
			$checkCity = City::selectRaw('*')->where('city_name',$city_name)->first();
		}

		if(!empty($checkCity)) {
			return json_encode(FALSE);
		}else{
			return json_encode(TRUE);
		}
	}

	public function addUpdateCity(Request $request){
		$city_id = $request->input('city_id');
		$city_name = $request->input('city_name');

		$data['city_name'] = $city_name;
		if(!empty($city_id)){
			$result =  City::where('id',$city_id)->update($data);
			$msg = "Update";
			$response['flag'] = 2;
		}else{
			$result =  City::insert($data);
			$msg = "Add";
			$response['flag'] = 1;
		}
		$total_city = City::count();
		$total_area = Area::count();
		if ($result) {
			$response['success'] = 1;
			$response['message'] = "Successfully ".$msg." City";
			$response['total_city'] = $total_city;
			$response['total_area'] = $total_area;
		} else {
			$response['success'] = 0;
			$response['message'] = "Error While ".$msg." City";
			$response['total_city'] = 0;
			$response['total_area'] = 0;
		}
		echo json_encode($response);
	}

	public function deleteCity(Request $request){

		$city_id = $request->input('city_id');
		$result =  City::where('id',$city_id)->delete();
		$result =  Area::where('city_id',$city_id)->delete();

		$total_city = City::count();
		$total_area = Area::count();

		if ($result) {
			$response['success'] = 1;
			$response['total_city'] = $total_city;
			$response['total_area'] = $total_area;
		} else {
			$response['success'] = 0;
			$response['total_city'] = 0;
			$response['total_area'] = 0;
		}
		echo json_encode($response);

	}

	public function CheckExistArea(Request $request)
	{
		$area_name = $request->input('area_name');
		$area_id = $request->input('area_id');
		$city_id = $request->input('city_id');

		if(!empty($area_id)){
			$checkArea = Area::selectRaw('*')->where('area_name',$area_name)->where('id','!=',$area_id)->where('city_id',$city_id)->first();
		}else{
			$checkArea = Area::selectRaw('*')->where('area_name',$area_name)->where('city_id',$city_id)->first();
		}

		if(!empty($checkArea)) {
			return json_encode(FALSE);
		}else{
			return json_encode(TRUE);
		}
	}


	public function addUpdateArea(Request $request){
		$area_id = $request->input('area_id');
		$area_name = $request->input('area_name');
		$city_id = $request->input('city_idd');

		$data['area_name'] = $area_name;
		$data['city_id'] = $city_id;
		if(!empty($area_id)){
			$result =  Area::where('id',$area_id)->update($data);
			$msg = "Update";
			$response['flag'] = 2;
		}else{
			$result =  Area::insert($data);
			$msg = "Add";
			$response['flag'] = 1;
		}
		$total_city = City::count();
		$total_area = Area::count();

		if ($result) {
			$response['success'] = 1;
			$response['message'] = "Successfully ".$msg." Area";
			$response['total_city'] = $total_city;
			$response['total_area'] = $total_area;
		} else {
			$response['success'] = 0;
			$response['message'] = "Error While ".$msg." Area";
			$response['total_city'] = 0;
			$response['total_area'] = 0;
		}
		echo json_encode($response);
	}

	public function deleteArea(Request $request){

		$area_id = $request->input('area_id');
		$result =  Area::where('id',$area_id)->delete();

		$total_city = City::count();
		$total_area = Area::count();

		if ($result) {
			$response['success'] = 1;
			$response['total_city'] = $total_city;
			$response['total_area'] = $total_area;
		} else {
			$response['success'] = 0;
			$response['total_city'] = 0;
			$response['total_area'] = 0;
		}
		echo json_encode($response);

	}

	public function viewListNotification()
	{
		$total_notification = Notification::count();
		return view('admin.settings.notification_list')->with('total_notification',$total_notification);
	}

	public function UpdateNotification(Request $request){
		$notification_id = $request->input('notification_id');
		
		$image_arr=[];
		$s3 = Storage::disk('s3');
		if ($request->hasfile('notification_img')) {
			$file = $request->file('notification_img');
			$imageFileName='notification_' . rand(111,999) . '.' . $file->getClientOriginalExtension();
			$destinationPath = '/uploads/';
			// File::makeDirectory($destinationPath, $mode = 0777, true, true);
			$filePath = $destinationPath . $imageFileName;
			if ($s3->put($filePath, file_get_contents($file)) ){
				$data['image'] = $imageFileName;
			}
		}else{
			$data['image'] = $request->input('hidden_notification_img');
		}
		$data['title'] = $request->input('notification_title');
		$data['message'] = $request->input('notification_message');
		
		if(!empty($notification_id)){
			$result =  Notification::where('notification_id',$notification_id)->update($data);
			$msg = "Update";
			$response['flag'] = 2;
		}
		
		$result = Notification::where('notification_id',$notification_id)->first();
		$total_notification = Notification::count();
		if ($result) {
			$response['data'] = $result;
			$response['success'] = 1;
			$response['message'] = "Successfully ".$msg." Notification";
			$response['total_notification'] = $total_notification;
		} else {
			$response['data'] = "";
			$response['success'] = 0;
			$response['message'] = "Error While ".$msg." Notification";
			$response['total_notification'] = 0;
		}
		echo json_encode($response);
	}

	public function deleteNotification(Request $request){

		$notification_id = $request->input('notification_id');
		$result =  Notification::where('notification_id',$notification_id)->delete();
		$total_notification = Notification::count();
		if ($result) {
			$response['success'] = 1;
			$response['total_notification'] = $total_notification;
		} else {
			$response['success'] = 0;
			$response['total_notification'] = 0;
		}
		echo json_encode($response);

	}

	public function showNotificationList(Request $request)
    {

		$columns = array( 
			0 =>'notification_id', 
			1 =>'title', 
			2 =>'message',
			3 =>'image',
			4=> 'action'
		);

		$totalData = Notification::where('notification_type',7)->count();

		$totalFiltered = $totalData; 

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value')))
		{            
			$NotificationData = Notification::where('notification_type',7)->offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
		}
		else {
			$search = $request->input('search.value'); 

			$NotificationData =  Notification::where('notification_type',7)->where('notification_id','LIKE',"%{$search}%")
						->offset($start)
						->limit($limit)
						->orderBy($order,$dir)
						->get();

			$totalFiltered = Notification::where('notification_type',7)->where('notification_id','LIKE',"%{$search}%")
					->count();
		}

		$data = array();
		if(!empty($NotificationData))
		{
			foreach ($NotificationData as $rows)
			{
				if($rows->image){
					$url = '<img class="img-lg rounded" src="'.url(env('DEFAULT_IMAGE_URL').$rows->image).'" width="60" height="60"/>';
				}else{
					$url = '';
				}

				$data[]= array(
					$rows->title,
					$rows->message,
					$url,
					'<a class="UpdateNotification" data-toggle="modal" data-target="#notificationModal" data-id="'.$rows->notification_id.'" data-title="'.$rows->title.'" data-message="'.$rows->message.'" data-img="'.$rows->image.'"><i class="i-cl-3 fa fa-edit col-blue font-20 pointer p-l-5 p-r-5"></i></a>
					<a class="delete" id="DeleteNotification" data-id="'.$rows->notification_id.'"><i class="fa fa-trash text-danger font-20 pointer p-l-5 p-r-5"></i></a>'
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

	public function showBannerList(Request $request)
    {

		$columns = array( 
			0 =>'id', 
			1 =>'banner_image',
			2=> 'action'
		);

		$totalData = Banner::count();

		$totalFiltered = $totalData; 

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value')))
		{            
		$BannerData = Banner::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
		}
		else {
		$search = $request->input('search.value'); 

		$BannerData =  Banner::where('id','LIKE',"%{$search}%")
					->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();

		$totalFiltered = Banner::where('id','LIKE',"%{$search}%")
					->count();
		}

		$data = array();
		if(!empty($BannerData))
		{
			foreach ($BannerData as $rows)
			{
				$url = url(env('DEFAULT_IMAGE_URL').$rows->banner_img);
				$data[]= array(
					'<img class="img-lg rounded" src="'.$url.'" width="60" height="60"/>',
					'<a class="UpdateBanner" data-toggle="modal" data-target="#bannerModal" data-id="'.$rows->id.'" data-img="'.$rows->banner_img.'"><i class="i-cl-3 fa fa-edit col-blue font-20 pointer p-l-5 p-r-5"></i></a>
					<a class="delete" id="DeleteBanner" data-id="'.$rows->id.'"><i class="fa fa-trash text-danger font-20 pointer p-l-5 p-r-5"></i></a>'
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


	public function showFAQList(Request $request)
    {

		$columns = array( 
			0 =>'id', 
			1 =>'question',
			2 =>'answer',
			3=> 'action'
		);

		$totalData = FAQ::count();

		$totalFiltered = $totalData; 

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value')))
		{            
		$FAQData = FAQ::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
		}
		else {
		$search = $request->input('search.value'); 

		$FAQData =  FAQ::where('id','LIKE',"%{$search}%")
					->where('question','LIKE',"%{$search}%")
					->where('answer','LIKE',"%{$search}%")
					->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();

		$totalFiltered = FAQ::where('id','LIKE',"%{$search}%")
					->count();
		}

		$data = array();
		if(!empty($FAQData))
		{
			foreach ($FAQData as $rows)
			{
				$edit =  route('faq/edit',$rows->id);	
				$data[]= array(
					$rows->question,
					$rows->answer,
					'<a href="'.$edit.'" class="edit"><i class="i-cl-3 fa fa-edit col-blue font-20 pointer p-l-5 p-r-5"></i></a>
					<a class="delete" id="DeleteFAQ" data-id="'.$rows->id.'"><i class="fa fa-trash text-danger font-20 pointer p-l-5 p-r-5"></i></a>'
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
	
	public function showCityList(Request $request)
    {

		$columns = array( 
			0 =>'id', 
			1 =>'city_name',
			2=> 'action'
		);

		$totalData = City::count();

		$totalFiltered = $totalData; 

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value')))
		{            
			$CityData = City::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
		}
		else {
			$search = $request->input('search.value'); 

			$CityData =  City::where('city_name','LIKE',"%{$search}%")
					->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();

			$totalFiltered = City::where('city_name','LIKE',"%{$search}%")
					->count();
		}

		$data = array();
		if(!empty($CityData))
		{
			foreach ($CityData as $rows)
			{
				$data[]= array(
					$rows->city_name,
					'<a class="UpdateCity" data-toggle="modal" data-target="#cityModal" data-id="'.$rows->id.'" data-name="'.$rows->city_name.'"><i class="i-cl-3 fa fa-edit col-blue font-20 pointer p-l-5 p-r-5"></i></a>
					<a class="delete" id="DeleteCity" data-id="'.$rows->id.'"><i class="fa fa-trash text-danger font-20 pointer p-l-5 p-r-5"></i></a>'
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

	public function showAreaList(Request $request)
    {

		$columns = array( 
			0 =>'id', 
			1 =>'city_name',
			2=> 'action'
		);

		$totalData = Area::count();

		$totalFiltered = $totalData; 

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value')))
		{            
			$AreaData = Area::select('tbl_area.*', 'C.city_name')
				->leftjoin('tbl_city as C', 'tbl_area.city_id', 'C.id')
				->offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
		}
		else {
			$search = $request->input('search.value'); 

			$AreaData =  Area::select('tbl_area.*', 'C.city_name')
					->leftjoin('tbl_city as C', 'tbl_area.city_id', 'C.id')
					->where('city_name','LIKE',"%{$search}%")
					->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();

			$totalFiltered = Area::select('tbl_area.*', 'C.city_name')
					->leftjoin('tbl_city as C', 'tbl_area.city_id', 'C.id')
					->where('city_name','LIKE',"%{$search}%")
					->count();
		}

		$data = array();
		if(!empty($AreaData))
		{
			foreach ($AreaData as $rows)
			{
				$data[]= array(
					$rows->city_name,
					$rows->area_name,
					'<a class="UpdateArea" data-toggle="modal" data-target="#areaModal" data-id="'.$rows->id.'" data-city_id="'.$rows->city_id.'" data-name="'.$rows->area_name.'"><i class="i-cl-3 fa fa-edit col-blue font-20 pointer p-l-5 p-r-5"></i></a>
					<a class="delete" id="DeleteArea" data-id="'.$rows->id.'"><i class="fa fa-trash text-danger font-20 pointer p-l-5 p-r-5"></i></a>'
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


 