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
use App\Category;
use App\Product;
use App\Order;
use App\Complaint;
use App\DeliveryBoy;
use File;
use Storage;
use Carbon\Carbon;

class AdminController extends Controller
{

	public function showLogin()
	{
		if (Session::get('email') && Session::get('is_user') == 1) {
			return Redirect::route('dashboard');
		} else {
			return view('admin.login');
		}
	}

	public function dologin(Request $request)
	{
		$username = $request->input('username');
		$password = $request->input('password');
		$checkLogin = Admin::where('username', $username)->first();

		if (!empty($checkLogin)) {
			if ($checkLogin->password == $password || Hash::check($password, $checkLogin->password)) {
				Session::put('name', $checkLogin->username);
				Session::put('email', $checkLogin->email);
				Session::put('admin_id', $checkLogin->id);
				Session::put('profile_image', env('DEFAULT_IMAGE_URL').$checkLogin->profile_image);
				Session::put('is_logged', 1);
				Session::put('is_admin', 1);

				return Redirect::route('dashboard');
			} else {
				Session::flash('invalid', 'Invalid email or password combination. Please try again.');
				return back();
			}
		} else {
			Session::flash('invalid', 'Invalid email or password combination. Please try again.');
			return back();
		}
	}

	public function showDashboard()
	{	
		if (Session::get('name') && Session::get('is_logged') == 1) {

			$totalCategory = Category::count();
			$totalProduct = Product::count();
			$outofstockProduct = Product::where('stock_status',0)->count();

			$todayOrder = Order::whereDate('completed_at', Carbon::today())->where('status',3)->count();
			$totaltodayOrder = Order::where('status',3)->whereDate('completed_at', Carbon::today())->sum('total_amount');

			$thisMonthOrder = Order::whereMonth('completed_at', Carbon::now()->month)->where('status',3)->count();
			$totalthisMonthOrder = Order::where('status',3)->whereMonth('completed_at', Carbon::now()->month)->sum('total_amount');

			$thisYearOrder = Order::whereYear('completed_at', Carbon::now()->year)->where('status',3)->count();
			$totalthisYearOrder = Order::where('status',3)->whereYear('completed_at', Carbon::now()->year)->sum('total_amount');

			$allOrder = Order::where('status',3)->count();
			$totalallOrder = Order::where('status',3)->sum('total_amount');

			$processingOrders = Order::where('status',1)->count();
			$totalprocessingOrders = Order::where('status',1)->sum('total_amount');
			$confirmedOrders = Order::where('status',2)->count();
			$totalconfirmedOrders = Order::where('status',2)->sum('total_amount');
			$onholdOrders = Order::where('status',4)->count();
			$totalonholdOrders = Order::where('status',4)->sum('total_amount');
			$cancelledOrders = Order::where('status',5)->count();
			$totalcancelledOrders = Order::where('status',5)->sum('total_amount');

			$deliveryBoy = User::where('user_type',1)->where('status',1)->count();
			$deliveryBoyPayData = DeliveryBoy::sum('amount_to_pay');
			$openComplaint = Complaint::where('status',1)->count();
			return view('admin.dashboard')->with('totalCategory',$totalCategory)->with('totalProduct',$totalProduct)->with('outofstockProduct',$outofstockProduct)->with('todayOrder',$todayOrder)->with('totaltodayOrder',$totaltodayOrder)->with('thisMonthOrder',$thisMonthOrder)->with('totalthisMonthOrder',$totalthisMonthOrder)->with('thisYearOrder',$thisYearOrder)->with('totalthisYearOrder',$totalthisYearOrder)->with('allOrder',$allOrder)->with('totalallOrder',$totalallOrder)->with('processingOrders',$processingOrders)->with('totalprocessingOrders',$totalprocessingOrders)->with('confirmedOrders',$confirmedOrders)->with('totalconfirmedOrders',$totalconfirmedOrders)->with('onholdOrders',$onholdOrders)->with('totalonholdOrders',$totalonholdOrders)->with('cancelledOrders',$cancelledOrders)->with('totalcancelledOrders',$totalcancelledOrders)->with('deliveryBoy',$deliveryBoy)->with('deliveryBoyPayData',$deliveryBoyPayData)->with('openComplaint',$openComplaint);
		} else {
			return Redirect::route('login');
		}
	}

	public function logout($flag)
	{
		// Session::flush();
		Session::flush();
		if ($flag == 1) {
			Session::flash('matchResetPassword', 'Password change successfully, Now login by new password...!');
		}
		return redirect()->route('login');
	}
	public function MyProfile()
	{	
		if (Session::get('name') && Session::get('is_logged') == 1) {
			$data = Admin::first();
			return view('admin.my-profile')->with('data',$data);
		} else {
			return Redirect::route('login');
		}
	}

	public function updateAdminProfile(Request $request)
	{	
		$admin_id = $request->input('admin_id');
        $admin_name = $request->input('admin_name'); 
        $admin_email = $request->input('email');
        $password = $request->input('password');
        $hdn_profile_image =  $request->input('hdn_profile_image');
        $profile_image = '';
        $data = [];

		$s3 = Storage::disk('s3');
		if ($request->hasfile('admin_profile')) {
			$file = $request->file('admin_profile');
			$imageFileName='profile_' . rand(111,999) . '.' . $file->getClientOriginalExtension();
			$destinationPath = '/uploads/';
			// File::makeDirectory($destinationPath, $mode = 0777, true, true);
			$filePath = $destinationPath . $imageFileName;
			if ($s3->put($filePath, file_get_contents($file)) ){
				$data['profile_image'] = $imageFileName;
			}
		}else{
			$data['profile_image'] = $hdn_profile_image;
		}
		$profile_image = $data['profile_image'];

        $data['username'] = $admin_name;
        $data['email'] = $admin_email;
        $data['password'] = $password;

       $update =  Admin::where('id',$admin_id)->update($data);
       if($update){
        $response['admin_name'] = $admin_name;
        $response['admin_email'] = $admin_email;
		$response['admin_profile_url'] = env('DEFAULT_IMAGE_URL').$profile_image;
		$response['admin_profile'] = $profile_image;
        $response['status'] = 1;
       }else{
        $response['admin_name'] = "";
        $response['admin_email'] = "";
		$response['admin_profile_url'] = "";
		$response['admin_profile'] = "";
        $response['status'] = 0;
       }
       echo json_encode($response);
	}
}
