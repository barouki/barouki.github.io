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
use App\Unit;
use App\ProductPriceUnit;
use App\Coupon;
use App\Order;
use App\OrdersItem;
use File;
use Storage;

class ProductController extends Controller
{

	public function viewListProduct()
	{
		$total_product = Product::count();
		$total_outofstock_product = Product::where('stock_status',0)->count();
		return view('admin.product.product_list')->with('data',[])->with('total_product',$total_product)->with('total_outofstock_product',$total_outofstock_product );
	}
	
    public function viewProduct($id="")
	{
		$categorydata = Category::get();
		$unitdata = Unit::get();
		$ProductPriceUnitData = ProductPriceUnit::select('tbl_product_price_unit.*', 'U.unit_name')
				->leftjoin('tbl_unit as U', 'tbl_product_price_unit.unit_id', 'U.id')
				->where('tbl_product_price_unit.product_id',$id)
				->get();	

		$data = Product::select('tbl_products.*', 'C.category_name')
		->leftjoin('tbl_category as C', 'tbl_products.category_id', 'C.id')
		->where('tbl_products.product_id',$id)
		->first();
		return view('admin.product.product_view')->with('data',$data)->with('categorydata',$categorydata)->with('unitdata',$unitdata)->with('ProductPriceUnitData',$ProductPriceUnitData);
	}

    public function viewAddProduct()
	{
		$categorydata = Category::get();
		$unitdata = Unit::get();
		return view('admin.product.product_addupdatedetail')->with('categorydata',$categorydata)->with('unitdata',$unitdata)->with('data',"")->with('ProductPriceUnitData',[])->with('title','Add');;
	}
	
	public function viewUpdateProduct($id="")
	{
		$data = Product::where('product_id',$id)->first();
		$categorydata = Category::get();
		$ProductPriceUnitData = ProductPriceUnit::where('product_id',$id)->get();
		$unitdata = Unit::get();
		return view('admin.product.product_addupdatedetail')->with('categorydata',$categorydata)->with('unitdata',$unitdata)->with('ProductPriceUnitData',$ProductPriceUnitData)->with('data',$data)->with('title','Edit');;
	}

	public function addEditProduct(Request $request){
		$product_name = $request->input('product_name');
		$product_description = $request->input('product_description');
		$price_unit_id = $request->input('product_price_unit_id');
		$product_price = $request->input('product_price');
		$product_discount = $request->input('product_discount');
		$price_unit_name = $request->input('price_unit_name');
		$price_unit = $request->input('price_unit');
		$product_category = $request->input('product_category');
		// $stock_qun = $request->input('stock_qun');
		// $stock_qun_unit_name = $request->input('stock_qun_unit_name');
		$product_id = $request->input('product_id');
		$action = $request->input('action');
	
		$hidden_product_image = trim($request->input('hidden_product_image'),",");
		
		if(empty($product_id)){
			$product_id = Product::get_random_string();
		}
		$product_image = $request->file('product_image');
		
		$productimgname = "";
		$image_arr=[];
		$s3 = Storage::disk('s3');
		if ($request->hasfile('product_image')) {
			foreach ($request->file('product_image') as $file) {
				$imageFileName='product_' . rand(111,999) . '.' . $file->getClientOriginalExtension();
				$destinationPath = '/uploads/';
				// File::makeDirectory($destinationPath, $mode = 0777, true, true);
				$filePath = $destinationPath . $imageFileName;
				if ($s3->put($filePath, file_get_contents($file)) ){
					// $image_url = $s3->url($imagePath);
					$image_arr[] = $imageFileName;
				}
				
			}
			if($hidden_product_image){
				$hdn_product_image = explode(',',$hidden_product_image);
				$image_arr = array_merge($hdn_product_image,$image_arr);
				$product_image = implode(',', $image_arr);
			}else{
				$product_image = implode(',', $image_arr);
			}
            
		}else{
			$product_image = $hidden_product_image;
		}

		// foreach($price_unit as $key => $unit){
		// 	$unit_arr[] = $unit.' '.$price_unit_name[$key];
		// }
		// if($stock_qun){
		// 	$stock_quantity = $stock_qun.'-'.$stock_qun_unit_name;
		// }else{
		// 	$stock_quantity = "";
		// }
		$data['product_id'] = $product_id;
		$data['name'] = $product_name;
		$data['description'] = $product_description;
		// $data['price'] = implode(',',$product_price);
		// $data['discount'] = implode(',',$product_discount);
		// $data['unit'] = implode(',',$price_unit);
		// $data['unit_id'] = implode(',',$price_unit_name);
	
		$data['category_id'] = $product_category;
		// $data['stock_quantity'] = $stock_quantity;
		$data['product_image'] = $product_image;

		if($action == 'update'){
			// ProductPriceUnit::where('product_id',$product_id)->delete();
			$result =  Product::where('product_id',$product_id)->update($data);
			$msg = "Update";
		}else{			
			$result =  Product::insert($data);
			$msg = "Add";
		}
		$i=0;
		foreach($price_unit_id as $k => $value){

			$data1['product_id'] = $product_id;
			$data1['price'] =  $product_price[$i];
			$data1['discount'] = $product_discount[$i];
			$data1['unit'] = $price_unit[$i];
			$data1['unit_id'] = $price_unit_name[$i];

			$ProductPriceUnitData = ProductPriceUnit::where('price_unit_id',$price_unit_id[$i])->first();
			if($ProductPriceUnitData){				
				ProductPriceUnit::where('price_unit_id',$price_unit_id[$i])->update($data1);
			}else{
				$data1['price_unit_id'] = ProductPriceUnit::get_random_string();
				ProductPriceUnit::insert($data1);
			}
			$i++;			
		}	

		if ($result) {
			$response['success'] = 1;
			$response['message'] = "Successfully ".$msg." Product";
		} else {
			$response['success'] = 0;
			$response['message'] = "Error While ".$msg." Product";
		}
		echo json_encode($response);
	}
	public function deleteProductPriceUnit(Request $request){
		$price_unit_id = $request->input('price_unit_id');
		ProductPriceUnit::where('price_unit_id',$price_unit_id)->delete();
		echo 1;
	}

	public function changeProductStock(Request $request){
		$product_id = $request->input('product_id');
		$category_id = $request->input('category_id');
		$status = $request->input('status');

		$result =  Product::where('product_id',$product_id)->update(['stock_status'=>$status]);
		if($category_id){
			$total_count = Product::where('category_id',$category_id)->count();
			$count = Product::where('category_id',$category_id)->where('stock_status',0)->count();
		}else{
			$count = Product::where('stock_status',0)->count();
			$total_count = Product::count();
		}
		
		if ($result) {
			$response['success'] = 1;
			$response['total_count'] = $total_count;
			$response['count'] = $count;
		} else {
			$response['success'] = 0;
			$response['total_count'] = 0;
			$response['count'] = 0;
		}
		echo json_encode($response);
	}

	public function deleteProduct(Request $request){
		$product_id = $request->input('product_id');
		$category_id = $request->input('category_id');

		$result =  Product::where('product_id',$product_id)->delete();
		
		if($category_id){
			$total_count = Product::where('category_id',$category_id)->count();
			$count = Product::where('category_id',$category_id)->where('stock_status',0)->count();
		}else{
			$count = Product::where('stock_status',0)->count();
			$total_count = Product::count();
		}
		
		if ($result) {
			$response['success'] = 1;
			$response['message'] = "Successfully Delete Discount";
			$response['total_count'] = $total_count;
			$response['count'] = $count;
		} else {
			$response['success'] = 0;
			$response['message'] = "Error While Delete Discount";
			$response['total_count'] = 0;
			$response['count'] = 0;
		}
		echo json_encode($response);
	}
	
	public function viewListProductByCategory($category_id=0)
	{
		$data = Category::where('id',$category_id)->first();
		$total_product = Product::where('category_id',$category_id)->count();
		$total_outofstock_product = Product::where('category_id',$category_id)->where('stock_status',0)->count();
		return view('admin.product.product_list')->with('data',$data)->with('total_product',$total_product)->with('total_outofstock_product',$total_outofstock_product );
	}
	
	public function viewListCategory()
	{
		$total_category = Category::count();
		return view('admin.product.category_list')->with('total_category',$total_category);;
	}

	public function CheckExistCategory(Request $request)
	{
		$category_name = $request->input('category_name');
		$category_id = $request->input('category_id');

		if(!empty($category_id)){
			$checkCategory = Category::selectRaw('*')->where('category_name',$category_name)->where('id','!=',$category_id)->first();
		}else{
			$checkCategory = Category::selectRaw('*')->where('category_name',$category_name)->first();
		}

		if(!empty($checkCategory)) {
			return json_encode(FALSE);
		}else{
			return json_encode(TRUE);
		}
	}

	public function addEditCategory(Request $request){
		$category_name = $request->input('category_name');
		$category_id = $request->input('category_id');

		$data['category_name'] = $category_name;
		if(!empty($category_id)){
			$result =  Category::where('id',$category_id)->update($data);
			$msg = "Update";
			$response['flag'] = 2;
		}else{
			$result =  Category::insert($data);
			$category_id = DB::getPdo()->lastInsertId();
			$msg = "Add";
			$response['flag'] = 1;
		}
		
		$result = Category::where('id',$category_id)->first();
		$total_category = Category::count();
	
		if ($result) {
			$response['data'] = $result;
			$response['success'] = 1;
			$response['message'] = "Successfully ".$msg." Product";
			$response['total_category'] = $total_category;
		} else {
			$response['data'] = "";
			$response['success'] = 0;
			$response['message'] = "Error While ".$msg." Product";
			$response['total_category'] = 0;
		}
		echo json_encode($response);
	}

	public function deleteCategory(Request $request){

		$category_id = $request->input('category_id');

		$result =  Category::where('id',$category_id)->delete();

		$total_category = Category::count();
		if ($result) {
			$response['success'] = 1;
			$response['message'] = "Successfully Delete Category";
			$response['total_category'] = $total_category;
		} else {
			$response['success'] = 0;
			$response['message'] = "Error While Delete Category";
			$response['total_category'] = 0;
		}
		echo json_encode($response);

	}

	public function viewListUnit()
	{
		$total_unit = Unit::count();
		return view('admin.product.unit_list')->with('total_unit',$total_unit);
	}

	public function CheckExistUnit(Request $request)
	{
		$unit_name = $request->input('unit_name');
		$unit_id = $request->input('unit_id');

		if(!empty($unit_id)){
			$checkUnit = Unit::selectRaw('*')->where('unit_name',$unit_name)->where('id','!=',$unit_id)->first();
		}else{
			$checkUnit = Unit::selectRaw('*')->where('unit_name',$unit_name)->first();
		}

		if(!empty($checkUnit)) {
			return json_encode(FALSE);
		}else{
			return json_encode(TRUE);
		}
	}

	public function addEditUnit(Request $request){
		$unit_name = $request->input('unit_name');
		$unit_id = $request->input('unit_id');

		$data['unit_name'] = $unit_name;
		if(!empty($unit_id)){
			$result =  Unit::where('id',$unit_id)->update($data);
			$msg = "Update";
			$response['flag'] = 2;
		}else{
			$result =  Unit::insert($data);
			$unit_id = DB::getPdo()->lastInsertId();
			$msg = "Add";
			$response['flag'] = 1;
		}
		
		$result = Unit::where('id',$unit_id)->first();
		$total_unit = Unit::count();
		if ($result) {
			$response['data'] = $result;
			$response['success'] = 1;
			$response['message'] = "Successfully ".$msg." Product";
			$response['total_unit'] = $total_unit;
		} else {
			$response['data'] = "";
			$response['success'] = 0;
			$response['message'] = "Error While ".$msg." Product";
			$response['total_unit'] = 0;
		}
		echo json_encode($response);
	}

	public function deleteUnit(Request $request){

		$unit_id = $request->input('unit_id');
		
		$result =  Unit::where('id',$unit_id)->delete();
		$total_unit = Unit::count();
		if ($result) {
			$response['success'] = 1;
			$response['message'] = "Successfully Delete Unit";
			$response['total_unit'] = $total_unit;
		} else {
			$response['success'] = 0;
			$response['message'] = "Error While Delete Unit";
			$response['total_unit'] = 0;
		}
		echo json_encode($response);

	}

	public function viewListCoupon()
	{
		$total_coupon = Coupon::count();
		return view('admin.product.coupon_list')->with('total_coupon',$total_coupon);
	}

    public function viewAddCoupon()
	{
		$categorydata = Coupon::get_random_string('coupon_code');
		$data = Product::get();
		return view('admin.product.coupon_update')->with('data',$data)->with('coupon_code',$coupon_code);
	}

	public function CheckExistCoupon(Request $request)
	{
		$coupon_code = $request->input('coupon_code');
		$coupon_id = $request->input('coupon_id');

		if(!empty($coupon_id)){
			$checkCoupon = Coupon::selectRaw('*')->where('coupon_code',$coupon_code)->where('id','!=',$coupon_id)->first();
		}else{
			$checkCoupon = Coupon::selectRaw('*')->where('coupon_code',$coupon_code)->first();
		}

		if(!empty($checkCoupon)) {
			return json_encode(FALSE);
		}else{
			return json_encode(TRUE);
		}
	}

	public function getCouponCode(Request $request)
	{
		$coupon_code =  Coupon::get_random_string('coupon_code');
		
		if ($coupon_code) {
			$response['success'] = 1;
			$response['coupon_code'] = $coupon_code;
		} else {
			
			$response['success'] = 0;
			$response['coupon_code'] = "";

		}
		echo json_encode($response);
		
	}

	public function getProductForCoupon(Request $request)
	{
		$data = Product::selectRaw('*')->whereNULL('coupon_code')->get();

		if ($data) {
			$response['success'] = 1;
			$response['data'] = $data;
		} else {	
			$response['success'] = 0;
			$response['data'] = "";
		}
		echo json_encode($response);
	}

	public function addCoupon(Request $request){
		
		$coupon_id = $request->input('coupon_id');
		$coupon_code = $request->input('coupon_code');
		$description = $request->input('description');
		$discount_type = $request->input('discount_type');
		$coupon_discount = $request->input('coupon_discount'); 
		$minimum_amount = $request->input('min_amount');
		// $coupon_uses = $request->input('coupon_uses');

		$data['coupon_code'] = strtoupper($coupon_code);
		$data['description'] = $description;
		$data['discount_type'] = $discount_type;
		$data['coupon_discount'] = $coupon_discount;
		$data['minimum_amount'] = $minimum_amount;

		if(!empty($coupon_id)){
			$result =  Coupon::where('id',$coupon_id)->update($data);
			$msg = "Update";
			$response['flag'] = 2;
		}else{
			$result =  Coupon::insert($data);
			$unit_id = DB::getPdo()->lastInsertId();
			$msg = "Add";
			$response['flag'] = 1;
		}
		
		$total_coupon = Coupon::count();	
		if ($result) {
			$response['success'] = 1;
			$response['message'] = "Successfully Add Coupon";
			$response['total_coupon'] = $total_coupon;
		} else {
			$response['success'] = 0;
			$response['message'] = "Error While Add Coupon";
			$response['total_coupon'] = 0;
		}
		echo json_encode($response);
	}

	public function deleteCoupon(Request $request){

		$coupon_id = $request->input('coupon_id');
		$result =  Coupon::where('id',$coupon_id)->delete();
		$total_coupon = Coupon::count();
		if ($result) {
			$response['success'] = 1;
			$response['message'] = "Successfully Delete Coupon";
			$response['total_coupon'] = $total_coupon;
		} else {
			$response['success'] = 0;
			$response['message'] = "Error While Delete Coupon";
			$response['total_coupon'] = 0;
		}
		echo json_encode($response);

	}

	public function CouponAssigntoProduct(Request $request){
		$coupon_code = $request->input('coupon_code');
		$product_id = $request->input('product_id');

		$data['coupon_code'] = $coupon_code;
		$result =  Product::where('product_id',$product_id)->update($data);
				
		if ($result) {
			$response['success'] = 1;
			$response['message'] = "Successfully Update Coupon to Product";
		} else {
			$response['success'] = 0;
			$response['message'] = "Error While Update Coupon to Product";
		}
		echo json_encode($response);
	}

	public function showProductList(Request $request)
    {

		$columns = array( 
			0 => 'id',
			1 =>'name',
			2=> 'price',
			3=> 'stock_quantity',
			4=> 'category_name',
		);


		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		$category_id = $request->input('category_id');
		$flag = $request->input('flag');

		if(empty($request->input('search.value')))
		{      
			if($category_id){
				if($flag == 1){
					$ProductData = Product::select('tbl_products.*', 'C.category_name')
					->leftjoin('tbl_category as C', 'tbl_products.category_id', 'C.id')
					->where('tbl_products.category_id',$category_id)
					->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();
					$totalData =  Product::where('category_id',$category_id)->count();
					$totalFiltered = $totalData; 
				}else{
					$ProductData = Product::select('tbl_products.*', 'C.category_name')
					->leftjoin('tbl_category as C', 'tbl_products.category_id', 'C.id')
					->where('tbl_products.category_id',$category_id)
					->where('tbl_products.stock_status',0)
					->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();
					$totalData =  Product::where('category_id',$category_id)->where('stock_status',0)->count();
					$totalFiltered = $totalData; 
				}
				
			}else{
				if($flag == 1){
					$ProductData = Product::select('tbl_products.*', 'C.category_name')
					->leftjoin('tbl_category as C', 'tbl_products.category_id', 'C.id')
					->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();
					
				
					$totalData =  Product::count();
					$totalFiltered = $totalData; 

				}else{
					$ProductData = Product::select('tbl_products.*', 'C.category_name')
					->leftjoin('tbl_category as C', 'tbl_products.category_id', 'C.id')
					->where('tbl_products.stock_status',0)
					->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();
					$totalData =  Product::where('stock_status',0)->count();
					$totalFiltered = $totalData; 
				}				
			}			
		}
		else {
			$totalData =  Product::count();
				$search = $request->input('search.value'); 
				if($category_id){
					$ProductData =  Product::select('tbl_products.*', 'C.category_name')
						->leftjoin('tbl_category as C', 'tbl_products.category_id', 'C.id')
						->where('tbl_products.category_id',$category_id)
						->where('tbl_products.id','LIKE',"%{$search}%")
						->orWhere('tbl_products.name', 'LIKE',"%{$search}%")
						->orWhere('C.category_name', 'LIKE',"%{$search}%")
						->offset($start)
						->limit($limit)
						->orderBy($order,$dir)
						->get();

					$totalFiltered = Product::select('tbl_products.*', 'C.category_name')
						->leftjoin('tbl_category as C', 'tbl_products.category_id', 'C.id')
						->where('tbl_products.category_id',$category_id)
						->where('tbl_products.id','LIKE',"%{$search}%")
						->orWhere('tbl_products.name', 'LIKE',"%{$search}%")
						->orWhere('C.category_name', 'LIKE',"%{$search}%")
						->count();
				}else{
					$ProductData =  Product::select('tbl_products.*', 'C.category_name')
						->leftjoin('tbl_category as C', 'tbl_products.category_id', 'C.id')
						->where('tbl_products.id','LIKE',"%{$search}%")
						->orWhere('tbl_products.name', 'LIKE',"%{$search}%")
						->orWhere('C.category_name', 'LIKE',"%{$search}%")
						->offset($start)
						->limit($limit)
						->orderBy($order,$dir)
						->get();

					$totalFiltered = Product::select('tbl_products.*', 'C.category_name')
						->leftjoin('tbl_category as C', 'tbl_products.category_id', 'C.id')
						->where('tbl_products.id','LIKE',"%{$search}%")
						->orWhere('tbl_products.name', 'LIKE',"%{$search}%")
						->orWhere('C.category_name', 'LIKE',"%{$search}%")
						->count();
				}
				
		}

		$data = array();
		if(!empty($ProductData))
		{
			foreach ($ProductData as $rows)
			{
				$view =  route('product/view',$rows->product_id);
				$edit =  route('product/edit',$rows->product_id);	
				$product_image = explode(',',$rows->product_image);
				$url = url(env('DEFAULT_IMAGE_URL').$product_image[0]);

				$ProductPriceUnitData = ProductPriceUnit::select('tbl_product_price_unit.*', 'U.unit_name')
				->leftjoin('tbl_unit as U', 'tbl_product_price_unit.unit_id', 'U.id')
				->where('tbl_product_price_unit.product_id',$rows->product_id)
				->get();			

				$price_unit = '';
				$discount_price = '';
				foreach($ProductPriceUnitData as $k => $val){
					if($val['discount']){
						$discount_price = ($val['price']*$val['discount'])/100;
						$discount_price = '$'.($val['price'] - $discount_price);
						if($discount_price < 0){
							$discount_price = '';
						}
					}else{
						$discount_price = '';
					}
					$price_unit .= $val['unit'].' '.$val['unit_name'].' - $'.$val['price'].' <del>'.$discount_price.'</del><br/>';
				}
				if($rows->stock_status == 1){
					$checked = 'checked';
				}else{
					$checked = '';
                }
				
				$stock_status = '<label class="switch"> <input type="checkbox" id="changeProductStock" data-id="'.$rows->product_id.'" data-status="'.$rows->stock_status.'" '.$checked.'> <span class="slider round"></span> </label>';
               
				$data[]= array(
					'<img class="img-lg rounded" src="'.$url.'" width="60" height="60"/>',
					$rows->name,
					$price_unit,
					$stock_status,
					$rows->category_name,
					'<a href="'.$view.'" class="view"><i class="i-cl-3 fa fa-eye col-green  font-20 pointer p-l-5 p-r-5"></i></a> <a href="'.$edit.'" class="edit"><i class="i-cl-3 fa fa-edit col-blue font-20 pointer p-l-5 p-r-5"></i></a>
					<a class="delete" id="productDelete" data-id="'.$rows->product_id.'"><i class="fa fa-trash text-danger font-20 pointer p-l-5 p-r-5"></i></a>'
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
	
	public function showCategoryList(Request $request)
    {

		$columns = array( 
			0 =>'id', 
			1 =>'category_name',
		);

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		$totalData = Category::count();

		$totalFiltered = $totalData; 

		if(empty($request->input('search.value')))
		{            
		$CategoryData = Category::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
		}
		else {
		$search = $request->input('search.value'); 

		$CategoryData =  Category::where('id','LIKE',"%{$search}%")
					->orWhere('category_name', 'LIKE',"%{$search}%")
					->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();

		$totalFiltered = Category::where('id','LIKE',"%{$search}%")
					->orWhere('category_name', 'LIKE',"%{$search}%")
					->count();
		}
		
		$data = array();
		if(!empty($CategoryData))
		{
			foreach ($CategoryData as $rows)
			{
				$show =  route('category/product/list',$rows->id);
				$data[]= array(
					$rows->category_name,
					'<a class="btn btn-success text-white" title="View Product" href="'.$show.'">View Products</a>',
					'<a class="UpdateCategory" data-toggle="modal" data-target="#categoryModal" data-id="'.$rows->id.'" data-name="'.$rows->category_name.'"><i class="i-cl-3 fa fa-edit col-blue font-20 pointer p-l-5 p-r-5"></i></a>
					<a class="delete" id="DeleteCategory" data-id="'.$rows->id.'"><i class="fa fa-trash text-danger font-20 pointer p-l-5 p-r-5"></i></a>'
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
	public function showUnitList(Request $request)
    {

		$columns = array( 
			0 =>'id', 
			1 =>'unit_name',
		);

		$totalData = Unit::count();

		$totalFiltered = $totalData; 

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value')))
		{            
			$UnitData = Unit::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
		}
		else {
			$search = $request->input('search.value'); 

			$UnitData =  Unit::where('id','LIKE',"%{$search}%")
					->orWhere('unit_name', 'LIKE',"%{$search}%")
					->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();

			$totalFiltered = Unit::where('id','LIKE',"%{$search}%")
					->orWhere('unit_name', 'LIKE',"%{$search}%")
					->count();
		}

		$data = array();
		if(!empty($UnitData))
		{
			foreach ($UnitData as $rows)
			{
			// $show =  route('posts.show',$post->id);
			// $edit =  route('posts.edit',$post->id);

			// $nestedData['id'] = $post->id;
			// $nestedData['title'] = $post->title;
			// $nestedData['body'] = substr(strip_tags($post->body),0,50)."...";
			// $nestedData['created_at'] = date('j M Y h:i a',strtotime($post->created_at));
			// $nestedData['options'] = "&emsp;<a href='{$show}' title='SHOW' ><span class='glyphicon glyphicon-list'></span></a>
			// 						&emsp;<a href='{$edit}' title='EDIT' ><span class='glyphicon glyphicon-edit'></span></a>";
			// $data[] = $nestedData;
				$data[]= array(
					$rows->unit_name,
					'<a class="UpdateUnit" data-toggle="modal" data-target="#unitModal" data-id="'.$rows->id.'" data-name="'.$rows->unit_name.'"><i class="i-cl-3 fa fa-edit col-blue font-20 pointer p-l-5 p-r-5"></i></a>
					<a class="delete" id="DeleteUnit" data-id="'.$rows->id.'"><i class="fa fa-trash text-danger font-20 pointer p-l-5 p-r-5"></i></a>'
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

	public function showCouponList(Request $request)
    {

		$columns = array( 
			0 =>'coupon_code', 
			1 =>'discount_type',
			2 =>'coupon_discount',
			3=> 'minimum_amount',
			4 =>'description', 
		);

		$totalData = Coupon::count();

		$totalFiltered = $totalData; 

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value')))
		{            
			$CouponData = Coupon::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
		}
		else {
			$search = $request->input('search.value'); 

			$CouponData =  Coupon::where('id','LIKE',"%{$search}%")
						->orWhere('coupon_code', 'LIKE',"%{$search}%")
						->offset($start)
						->limit($limit)
						->orderBy($order,$dir)
						->get();

			$totalFiltered = Coupon::where('id','LIKE',"%{$search}%")
					->orWhere('coupon_code', 'LIKE',"%{$search}%")
					->count();
		}

		$data = array();
		if(!empty($CouponData))
		{
			foreach ($CouponData as $rows)
			{
				if($rows->discount_type == 1){
					$discount_type = 'Flat';			
					$amount = '$'.$rows->coupon_discount;
				}else{
					$discount_type = 'Upto';
					$amount = $rows->coupon_discount.'%';
				}
				$data[]= array(
					$rows->coupon_code,
					$discount_type,
					$amount,
					$rows->minimum_amount ? '$'.$rows->minimum_amount : "",
					$rows->description,
					'<a data-toggle="modal" data-target="#couponModal" class="updateCoupon" data-id="'.$rows->id.'" data-coupon_code="'.$rows->coupon_code.'" data-discount_type="'.$rows->discount_type.'" data-coupon_discount="'.$rows->coupon_discount.'" data-minimum_amount="'.$rows->minimum_amount.'" data-description="'.$rows->description.'"><i class="i-cl-3 fa fa-edit col-blue font-20 pointer p-l-5 p-r-5"></i></a> <a class="delete" id="DeleteCoupon" data-id="'.$rows->id.'"><i class="fa fa-trash text-danger font-20 pointer p-l-5 p-r-5"></i></a>'
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


 