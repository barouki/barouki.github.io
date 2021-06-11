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
          <h4>{{$title}} Product</h4>
        </div>
        <div class="card-body">
          <form class="forms-sample" id="addUpdateProduct">
            {{ csrf_field() }}
 
            
            <div class="form-row">
              <div class="form-group col-md-2">
                <label for="Product_img">Add Product Images</label>
                <input type="file" name="product_image[]" id="product_image" class="form-control product_image" accept="image/png,image/jpg,image/jpeg,image/webp" multiple/>
              </div>
              <div id="photo_gallery" class="col-md-10 mt-4">
                  <?php if($data){
                      $product_img = explode(',',$data['product_image']);
                  ?>
                  @foreach($product_img as $val)
                    @if(!empty($val))
                      <div class="borderwrap" data-href="@if(!empty($val)){{$val}}@endif">
                        <div class="filenameupload"><img src="@if($val && !empty($val) ){{url(env('DEFAULT_IMAGE_URL').$val)}}@endif" width="130" height="130">
                        <div class="middle"><i class="material-icons remove_img">cancel</i></div> 
                        </div>
                      </div>
                    @endif
                  @endforeach 
                <?php } ?>
              </div>
              <input type="hidden" name="hidden_product_image" id="hidden_product_image" value="@if($data){{$data['product_image']}}@endif">
              </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="product_name">Product Name</label>
                  <input type="text"  name="product_name" class="form-control" id="product_name" placeholder="Name" value="@if($data){{$data['name']}}@endif">
                </div>
                <div class="form-group col-md-5">
                  <label for="product_category">Select Category</label>
                  <select class="form-control form-control-lg" id="product_category" name="product_category">
                    <option value="">Select</option>
                    @foreach($categorydata as $catval)
                    <?php if ($data && $data['category_id'] == $catval['id']) {
                      $selected = 'selected';
                    } else {
                      $selected = '';
                    } ?>
                    <option value="{{$catval['id']}}" {{$selected}}>{{$catval['category_name']}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group col-md-1">
                  <label for="product_category"></label>
                  <div class="buttons mt-2">
                    <a class="btn btn-info text-light add_category" data-toggle="modal" data-target="#categoryModal" data-whatever="@mdo"><i class="fa fa-plus"></i></a>
                  </div>
                </div>
            </div>

            <div class="form-row pricediv">

              <div class="form-group col-md-2">
                <input type="hidden" name="product_price_unit_id[0]" placeholder="Price" class="form-control product_price_unit_id" value="@if($ProductPriceUnitData){{$ProductPriceUnitData[0]['price_unit_id']}}@endif">
                  <label for="price_unit">Unit</label>

                  <div class="input-group mb-2 mr-sm-2">
                    <input type="text" name="price_unit[0]" placeholder="Unit" class="form-control price_unit" value="@if($ProductPriceUnitData){{$ProductPriceUnitData[0]['unit']}}@endif">
                    <div class="input-group-prepend">
                      <select class="form-control form-control-lg price_unit_name" name="price_unit_name[0]">
                        <option value="">Select</option>
                        @foreach($unitdata as $unitval)
                          <?php if ($ProductPriceUnitData && $unitval['id'] == $ProductPriceUnitData[0]['unit_id']) {
                            $selected = 'selected';
                          } else {
                            $selected = '';
                          } ?>
                          <option value="{{$unitval['id']}}" {{$selected}}>{{$unitval['unit_name']}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  
                </div>
                <div class="form-group col-md-2">
                  <label for="product_price">Price</label>
                  <div class="input-group mb-2 mr-sm-2">
                    <div class="input-group-prepend">
                      <div class="input-group-text">₹</div>
                    </div>
                    <input type="text" name="product_price[0]" placeholder="Price" class="form-control product_price" value="@if($ProductPriceUnitData){{$ProductPriceUnitData[0]['price']}}@endif">
                  </div>
                </div>
                <?php
                  if($ProductPriceUnitData && $ProductPriceUnitData[0]['discount']){
                    $discount_price = ($ProductPriceUnitData[0]['price']*$ProductPriceUnitData[0]['discount'])/100;
                    $discount_price = '₹'.($ProductPriceUnitData[0]['price'] - $discount_price);
                  }else{
                    $discount_price = '';
                  }
                ?>
                <div class="form-group col-md-2">
                  <label for="product_discount">Discount</label>
                  <div class="input-group mr-sm-2">
                    <input type="text" name="product_discount[0]" placeholder="Discount" class="form-control product_discount" value="@if($ProductPriceUnitData){{$ProductPriceUnitData[0]['discount']}}@else{{'0'}}@endif">
                    <div class="input-group-prepend">
                      <div class="input-group-text">%</div>
                    </div>
                  </div>
                  <p class="sale_price mb-0">Sale Price: {{$discount_price}} </p>
                </div>

                <div class="form-group col-md-1">
                  <label for="product_price"></label>
                  <div class="buttons mt-2">
                    <a class="btn btn-info text-light add_price"><i class="fa fa-plus"></i></a>
                  </div>
                </div>
        
            </div>
            @if(!empty($ProductPriceUnitData))
              <?php foreach($ProductPriceUnitData as $key => $value){ 
                if($key !=0 ) { ?>
                <div class="form-row pricediv">
                <div class="form-group col-md-2">
                <input type="hidden" name="product_price_unit_id[{{$key}}]" placeholder="Price" class="form-control product_price_unit_id" value="@if($data){{$value['price_unit_id']}}@endif">
                  <label for="price_unit">Unit</label>

                  <div class="input-group mb-2 mr-sm-2">
                    <input type="text" name="price_unit[{{$key}}]" placeholder="Unit" class="form-control price_unit" value="@if($data){{$value['unit']}}@endif">
                    <div class="input-group-prepend">
                      <select class="form-control form-control-lg price_unit_name" name="price_unit_name[{{$key}}]">
                        <option value="">Select</option>
                        @foreach($unitdata as $unitval)
                          <?php if ($data && $unitval['id'] == $value['unit_id']) {
                            $selected = 'selected';
                          } else {
                            $selected = '';
                          } ?>
                          <option value="{{$unitval['id']}}" {{$selected}}>{{$unitval['unit_name']}}</option>
                        @endforeach
                      </select>
                    </div>
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

                <div class="form-group col-md-1">
                  <label for="product_price"></label>
                  <div class="buttons mt-2">
                    <a class="btn btn-danger text-light remove_price"><i class="fa fa-minus"></i></a>
                  </div>
                </div>
                </div>
              <?php } } ?>
            @endif

            <input type="hidden" id="hidden_count" value="@if($ProductPriceUnitData){{count($ProductPriceUnitData)}}@else{{1}}@endif">
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="product_description">Product Description</label>
                <textarea id="product_description" name="product_description">
                @if($data){{$data['description']}}@endif
                </textarea>
              </div>
            </div>

            </div>
            <input type="hidden" name="product_id" id="product_id" value="@if($data){{$data['product_id']}}@endif">
            <input type="hidden" name="action" id="action" value="@if($data){{'update'}}@else{{'add'}}@endif">
            <button type="submit" class="btn btn-primary mr-2">Submit</button>
            <a class="btn btn-light" href="{{route('product/list')}}">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>

  </div>
</section>
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel"> Add Category </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="addUpdateCategory" method="post" enctype="multipart">
      {{ csrf_field() }}
      <div class="modal-body">

        <div class="form-group">
          <label for="category_name">Category Name</label>
          <input id="category_name" name="category_name" type="text" class="form-control form-control-danger">
        </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="category_id" id="category_id" value="">
          <button type="submit" class="btn btn-success">Save</button>
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@section('pageSpecificJs')

<script src="{{asset('assets/bundles/datatables/datatables.min.js')}}"></script>
<script src="{{asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/bundles/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/js/page/datatables.js')}}"></script>
<script src="{{asset('assets/bundles/summernote/summernote-bs4.js')}}"></script>
<script src="{{asset('assets/bundles/izitoast/js/iziToast.min.js')}}"></script>

<script>

  $(document).ready(function() {

    $("#product_description").summernote({
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
        styleTags: [
          'p',
              { title: '', tag: '', className: 'summernote_p', value: '' },
        ],
    });
    $('.note-editing-area .note-editable p').attr("style","line-height:1");


    $("form").on('click', '.add_price', function (e) {
      var counter = $("#hidden_count").val();
      counter = parseInt(counter);
      var html = $(".pricediv:first").clone();
      $(html).find('input').val('');
      $(html).find('.product_price_unit_id').attr('name','product_price_unit_id['+counter+']');
      $(html).find('.price_unit').attr('name','price_unit['+counter+']');
      $(html).find('.price_unit_name').attr('name','price_unit_name['+counter+']');
      $(html).find('.product_price').attr('name','product_price['+counter+']');
      $(html).find('.product_discount').attr('name','product_discount['+counter+']');
      $(html).find('.sale_price').html('Sale Price:');
      $(html).find('label.error').remove();
      $(html).find('.add_price').removeClass('btn-info');
      $(html).find('.add_price').addClass('btn-danger');
      $(html).find('.add_price').html('<i class="fa fa-minus"></i>');
      $(html).find('.add_price').addClass('remove_price');
      $(html).find('.add_price').removeClass('add_price');
      $(html).insertAfter(".pricediv:last");
     
      $('input[name="price_unit['+counter+']"]').rules("add", {
        required: true,
        messages: {
          required: "Please Select Product Unit",
        },
      });

      
      $('input[name="price_unit_name['+counter+']"]').rules("add", {
        required: true,
        messages: {
          required: "Please Enter Product Unit Name",
        },
      });

      $('input[name="product_price['+counter+']"]').rules("add", {
        required: true,
        messages: {
          required: "Please Enter Product Price",
        },
      });

      $("#hidden_count").val(counter+1);

    });

    $(document).on('click', '.remove_price', function (e) {
      $(this).parent().parent().parent().remove();
      var price_unit_id = $(this).parent().parent().parent().find('.product_price_unit_id').val();
      if(price_unit_id){
        $.ajax({
            url: '{{ route("deleteProductPriceUnit") }}',
            type: 'POST',
            data: {price_unit_id:price_unit_id},
            cache: false,
            success: function (data) {
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
      }

    });

    $(document).on('keyup', '.product_discount', function() {
      var discount = $(this).val();
      var price = $(this).parent().parent().prev().find('.product_price').val();
      if(discount>0){
        var discount_price = (price*discount)/100;
        var discount_price = '₹'+(price - discount_price);
      }else{
        var discount_price = 0;
      } 
      if(discount_price > 0){
        $(this).parent().parent().find('.sale_price').text('Sale Price: '+discount_price);
      }else{
        $(this).parent().parent().find('.sale_price').text('Sale Price: ₹0');
      }
      
    });

    $(document).on('change', '#product_image', function() {
      var flag = $(this).attr('data-flag');
      imagesPreview(this, '#photo_gallery');
    });

    var imagesPreview = function(input, placeToInsertImagePreview) {

      if (input.files) {
        var filesAmount = input.files.length;
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.jfif|\.webp)$/i;
        for (i = 0; i < filesAmount; i++) {

          if(!allowedExtensions.exec(input.value)){
            iziToast.error({
              title: 'Error!',
              message: 'Please upload file having extensions .jpeg/.jpg/.png only.',
              position: 'topRight'
            });
            input.value = '';
            return false;
          }else{

            var reader = new FileReader();

            reader.onload = function(event) {
              $(placeToInsertImagePreview).append('<div class="borderwrap" data-href="'+event.target.result+'"><div class="filenameupload"><img src="'+event.target.result+'" width="130" height="130"> <div class="middle"><i class="material-icons remove_img">cancel</i></div> </div></div>');
            }

            reader.readAsDataURL(input.files[i]);
          }
        }
      }
    };

    $(document).on('click','.remove_img', function(){

      var img_len = $('.borderwrap').length-1;
      var p_img = $(this).closest("div").parent().parent().attr('data-href');
      $(this).closest("div").parent().parent().remove();

      var upload_img = $('#hidden_product_image').val();
      var temp = upload_img.replace(p_img+",",'');

      if(upload_img == temp){
        var temp = upload_img.replace(p_img,'');
      }
      $('#hidden_product_image').val(temp);
      $('#hidden_product_image').attr('value',temp);

    });

    $("#addUpdateProduct").validate({
      rules: {
        product_name: {
          required: true,
        },
        product_description: {
          required: true,
        },
        "product_price[0]": {
          required: true,
        },
        "price_unit[0]": {
          required: true,
        },
        "price_unit_name[0]": {
          required: true,
        },
        product_category: {
          required: true,
        },
        stock_qun_unit_name: {
          required: function(element) {
            return $(".stock_qun").val() > 0;
          }
        }
      },
      messages: {
        product_name: {
          required: "Please Enter Product Name",
        },
        product_description: {
          required: "Please Enter Product Description",
        },
        "product_price[0]": {
          required: "Please Enter Product Price",
        },
        "price_unit[0]": {
          required: "Please Enter Product Unit",
        },
        "price_unit_name[0]": {
          required: "Please Select Product Unit Name",
        },
        product_category: {
          required: "Please Select Product Category",
        },
        stock_qun_unit_name: {
          required: "Please Select Stock Quantity Unit Name",
        }
      },
      errorPlacement: function(error, element) {
          console.log(element.hasClass( "price_unit" ))
            if (element.hasClass("price_unit") ) {
                $(error).insertAfter((element).parent());
            }
            else if (element.hasClass("price_unit_name") ) {
              $(error).insertAfter((element).parent());
            }
            //Custom position: second name
            else if (element.hasClass("product_price") ) {
              $(error).insertAfter((element).parent());
            }
        }

    });

  $(document).on('submit', '#addUpdateProduct', function (e) {
      e.preventDefault();
      var formdata = new FormData($("#addUpdateProduct")[0]);
      $('.loader').show();
      $.ajax({
          url: '{{ route("addUpdateProduct") }}',
          type: 'POST',
          data: formdata,
          dataType: "json",
          contentType: false,
          cache: false,
          processData: false,
          success: function (data) {
              $('.loader').hide();
              if (data.success == 1) {
                window.location.href = '{{ route("product/list") }}';
                iziToast.success({
                  title: 'Success!',
                  message: data.message,
                  position: 'topRight'
                });
              } else {
                iziToast.error({
                  title: 'Error!',
                  message: data.message,
                  position: 'topRight'
                });
              }
          },
          error: function (jqXHR, textStatus, errorThrown) {
              alert(errorThrown);
          }
      });
    });

    $('#categoryModal').on('hidden.bs.modal', function(e) {
        $("#addUpdateCategory")[0].reset();
        $('.modal-title').text('Add Category');
        $('#category_id').val("");
        var validator = $("#addUpdateCategory").validate();
        validator.resetForm();
    });

    $("#addUpdateCategory").validate({
        rules: {
        category_name: {
                required: true,
                remote: {
                    url: '{{ route("CheckExistCategory") }}',
                    type: "post",
                    data: {
                        category_name: function () { return $("#category_name").val(); },
                        category_id: function () { return $("#category_id").val(); },
                    }
                }
            }
        },
        messages: {
        category_name: {
                required: "Please Enter Category Name",
                remote: "Category already Exist"
            }
        }
    });
  
    $(document).on('submit', '#addUpdateCategory', function (e) {
      e.preventDefault();
      
      var formdata = new FormData($("#addUpdateCategory")[0]);
      $('.loader').show();
      $.ajax({
          url: '{{ route("addUpdateCategory") }}',
          type: 'POST',
          data: formdata,
          dataType: "json",
          contentType: false,
          cache: false,
          processData: false,
          success: function (data) {
              $('.loader').hide();
              console.log(data);
              $('#categoryModal').modal('hide');
              if (data.success == 1) {
                var html = '<option value="'+data.data.id+'" {{$selected}}>'+data.data.category_name+'</option>';
                $('#product_category').append(html);
                $('#product_category').val(data.data.id);
                iziToast.success({
                  title: 'Success!',
                  message: data.message,
                  position: 'topRight'
                });
              } else {
                iziToast.error({
                  title: 'Error!',
                  message: data.message,
                  position: 'topRight'
                });
              }
          },
          error: function (jqXHR, textStatus, errorThrown) {
              alert(errorThrown);
          }
      });
    });
           
  });

</script>

@endsection