@extends('admin_layouts/main')
@section('pageSpecificCss')
<link href="{{asset('assets/bundles/datatables/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">

<link href="{{asset('assets/bundles/summernote/summernote-bs4.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/izitoast/css/iziToast.min.css')}}" rel="stylesheet">

<style type="text/css">
  .ul-font>li {
    font-weight: 400;
    font-size: 15px;
  }

  .form-group {
    margin-bottom: 15px !important;
  }

  #uploadimg, #product_image {
    overflow: hidden;
    cursor: pointer;
    width: 130px;
    height: 130px;
    margin-right: 10px;
    border: none !important;
    border-radius: unset !important;
    padding: unset !important;
  }

  #product_image:before {
    width: 130px;
    height: 130px;
    background-color: #F9F9F9;
    cursor: pointer;
    border: 1px dashed #000;
    border-radius: 8px;
    text-align: center;
    padding: 55px 20px 40px 20px;
    font-size: 16px;
    content: 'Add Photo';
    display: inline-block;
    text-align: center;
  }
  .borderwrap {
    float: left;
    /* border: 1px dashed #000; */
    margin-right: 10px;
    border-radius: 6px;
    position: relative;
  }
  .middle {
	transition: .5s ease;
	opacity: 1;
	position: absolute;
	top: 4px;
  right: -22px;
	transform: translate(-50%, -50%);
	-ms-transform: translate(-50%, -50%)
}

.remove_img {
	color: #ffa117 !important;
  cursor:pointer;
}
.sale_price{
  font-size:12px;
}
</style>

@stop
@section('content')
<section class="section">
  <div class="section-body">

<div class="row">
  <div class="col-12 col-md-12 col-lg-12">
    <div class="card">
      <div class="card-body">
        <div class="card-header">
          <h4>View Product</h4>
        </div>
        <div class="card-body">
          <form class="forms-sample" id="addUpdateProduct">
            {{ csrf_field() }}
 
            
            <div class="form-row">
              <div class="form-group col-md-12">
                <div id="photo_gallery" class="col-md-10 mt-4">
                    <?php if($data){
                        $product_img = explode(',',$data['product_image']);
                    ?>
                    @foreach($product_img as $val)
                      @if(!empty($val))
                        <div class="borderwrap" data-href="@if(!empty($val)){{$val}}@endif">
                          <div class="filenameupload"><img src="@if($val && !empty($val) ){{url(env('DEFAULT_IMAGE_URL').$val)}}@endif" width="130" height="130">
                          </div>
                        </div>
                      @endif
                    @endforeach 
                  <?php } ?>
                </div>
              </div>  
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="product_name">Product Name</label>
                  <input type="text"  name="product_name" class="form-control" id="product_name" placeholder="Name" value="@if($data){{$data['name']}}@endif" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label for="product_category">Category</label>
                  <input type="text"  name="product_name" class="form-control" id="product_name" placeholder="Name" value="@if($data){{$data['category_name']}}@endif" readonly>
             
                </div>
            </div>
            @if(!empty($ProductPriceUnitData))
              <?php foreach($ProductPriceUnitData as $key => $value){ ?>
                <div class="form-row pricediv">
                <div class="form-group col-md-2">
                  <label for="price_unit">Unit</label>
                  <div class="form-group mb-2 mr-sm-2">
                    <input type="text" name="price_unit[{{$key}}]" placeholder="Unit" class="form-control price_unit" value="@if($data){{$value['unit'].' '.$value['unit_name']}}@endif">
                  </div>                  
                </div>

                <div class="form-group col-md-2">
                  <label for="product_price">Price</label>
                  <div class="input-group mb-2 mr-sm-2">
                    <input type="text" name="product_price[{{$key}}]" placeholder="Price" class="form-control product_price" value="@if($data){{$value['price']}}@endif">
                    <div class="input-group-prepend">
                      <div class="input-group-text">₹</div>
                    </div>
                  </div>
                </div>

                <?php
                  if($value['discount'] > 0){
                    $discount_price = ($value['price']*$value['discount'])/100;
                    $discount_price = '₹'.($value['price'] - $discount_price);
                  }else{
                    $discount_price = '';
                  }
                ?>

                <div class="form-group col-md-2">
                  <label for="product_discount">Discount</label>
                  <div class="input-group mr-sm-2">
                    <input type="text" name="product_discount[{{$key}}]" placeholder="Discount" class="form-control product_discount" value="@if($data){{$value['discount']}}@else{{'0'}}@endif">
                    <div class="input-group-prepend">
                      <div class="input-group-text">%</div>
                    </div>
                  </div>
                  <p class="sale_price mb-0">Sale Price: {{$discount_price}} </p>
                </div>

                </div>
              <?php }  ?>
            @endif

            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="product_description">Product Description</label>
                <textarea id="product_description" name="product_description" readonly>
                @if($data){{$data['description']}}@endif
                </textarea>
              </div>
            </div>

            </div>
            <a class="btn btn-light" href="{{route('product/list')}}">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>

  </div>
</section>
@endsection
@section('pageSpecificJs')

<script src="{{asset('assets/bundles/summernote/summernote-bs4.js')}}"></script>

<script>

  $(document).ready(function() {

    $("#product_description").summernote("disable",{
        height: 250,
        toolbar: [
            [ 'style', [ 'style' ] ],
            [ 'font', [ 'bold', 'italic', 'underline', 'clear'] ],
            [ 'fontname', [ 'fontname' ] ],
            [ 'fontsize', [ 'fontsize' ] ],
            [ 'color', [ 'color' ] ],
            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
            [ 'table', [ 'table' ] ],
            [ 'insert', [ 'link'] ],
            [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
        ],
    });
  });
</script>
@endsection