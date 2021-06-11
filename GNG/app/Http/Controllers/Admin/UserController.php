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

class UserController extends Controller
{

    public function viewListUser()
	{
		$total_user = User::where('user_type',0)->count();
		return view('admin.user.user_list')->with('total_user',$total_user);
    }
    
    // public function deleteUser(Request $request){
	// 	$user_id = $request->input('user_id');
	// 	$result =  User::where('user_id',$user_id)->where('user_type',0)->delete();
	// 	$total_user = User::where('user_type',0)->count();
	// 	if ($result) {
	// 		$response['success'] = 1;
	// 		$response['total_user'] = $total_user;
	// 	} else {
	// 		$response['success'] = 0;
	// 		$response['total_user'] = 0;
	// 	}
	// 	echo json_encode($response);
	// }

	public function showUserList(Request $request)
    {

		$columns = array( 
            0=>'user_id',
            1=>'full_name',
            2=>'email',
            3=>'status',
		);

		$totalData = User::where('user_type',0)->count();

		$totalFiltered = $totalData; 

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value')))
		{      
			
		$UserData = User::where('user_type',0)->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
		}
		else {
		$search = $request->input('search.value'); 

		$UserData =  User::where('user_type',0)->where('id','LIKE',"%{$search}%")
					->orWhere('first_name', 'LIKE',"%{$search}%")
					->orWhere('last_name', 'LIKE',"%{$search}%")
					->orWhere('email', 'LIKE',"%{$search}%")
					->orWhere('company_name', 'LIKE',"%{$search}%")
					->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();

		$totalFiltered = User::where('user_type',0)->where('id','LIKE',"%{$search}%")
					->orWhere('first_name', 'LIKE',"%{$search}%")
					->orWhere('last_name', 'LIKE',"%{$search}%")
					->orWhere('email', 'LIKE',"%{$search}%")
					->orWhere('company_name', 'LIKE',"%{$search}%")
					->count();
		}

		$data = array();
		if(!empty($UserData))
		{
			foreach ($UserData as $rows)
			{
                $view =  route('user/view',$rows->user_id);	

                if(!empty($rows->profile_image))
                {
                    $profile = '<img height="60px;" width="60px;" src="'.env('DEFAULT_IMAGE_URL').$rows->profile_image.'" class="" alt="">';
                }
                else
                {
                    $profile = '<img height="60px;" width="60px;" src="'.asset('assets/dist/img/default.png').'" class="" alt="">';
                }
                if($rows->first_name && $rows->last_name){
                    $fullname = $rows->first_name.' '.$rows->last_name;
                }elseif($rows->first_name){
                    $fullname = $rows->first_name;
                }elseif($rows->last_name){
                    $fullname = $rows->last_name;
                }
                if ($rows->status == 0) {
                    $status =  '<span class="badge badge-pill badge-danger">De-Active</span>';
                } elseif ($rows->status == 1) {
                    $status =  '<span class="badge badge-pill badge-success">Active</span>';
                } 
				$data[]= array(
					$profile,
					$fullname,
					$rows->email,
                    $status,
                    // '<a class="delete" id="userDelete" data-id="'.$rows->user_id.'"><i class="fa fa-trash text-danger font-20 pointer p-l-5 p-r-5"></i></a>'
				); 
				// <a href="'.$view.'" class="settings" title="View User" data-toggle="tooltip" data-original-title="View User Details"><i class="fa fa-eye text-success font-20 pointer p-l-5 p-r-5"></i></a>
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
