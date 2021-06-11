@extends('admin_layouts/main')
@section('pageSpecificCss')
<link href="{{asset('assets/bundles/datatables/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/izitoast/css/iziToast.min.css')}}" rel="stylesheet">

<style type="text/css">
  .hide {
    display:none;
  }
</style>
@stop
@section('content')
<section class="section">
  <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Coupon List (<span class="total_coupon">{{$total_coupon}}</span>)</h4>
            </div>
            <div class="card-body">
              <div class="pull-right">
                <div class="buttons"> 
                  <button class="btn btn-primary text-light" data-toggle="modal" data-target="#couponModal" data-whatever="@mdo">Add Coupon</button>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-striped" id="coupon-listing">
                  <thead>
                    <tr>
                      <th>Coupon Code</th>
                      <th>Discount Type</th>
                      <th>Discount Amount</th>
                      <th>Minimum Amount</th>
                      <th>Description</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
            </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="couponModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel"> Add Coupon </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addCoupon" method="post" enctype="multipart">
        {{ csrf_field() }}
          <div class="modal-body">

            <div class="form-group">
              <label for="coupon_code">Coupon Code</label>
                  <input type="text" id="coupon_code" class="form-control coupon_code" name="coupon_code" placeholder="Coupon code" style="text-transform:uppercase" value="">
            </div>

            <div class="form-group">
              <label for="description">Coupon Description</label>
                  <input type="text" id="description" class="form-control description" name="description" placeholder="Coupon Description" value="">
            </div>

            <div class="form-group">
              <label for="discount_type">Discount Type</label>
                <select class="form-control form-control-lg discount_type" id="discount_type" name="discount_type">
                  <option value="">Select</option>
                  <option value="1">Flat Discount</option>
                  <option value="2">Upto Discount</option>
                </select>
            </div>
            
            <div class="form-group flat">
              <label for="coupon_discount">Coupon Discount</label>
                <div class="input-group mb-2 mr-sm-2">
                  <input type="text" id="coupon_discount" name="coupon_discount" placeholder="Discount Amount" class="form-control coupon_discount" value="">
                  <div class="input-group-prepend">
                    <div class="input-group-text">%</div>
                  </div> 
                </div>
            </div>

            <div class="form-group upto">
                <label for="min_amount">Minimum Amount</label>
                <div class="input-group mb-2 mr-sm-2">
                    <div class="input-group-prepend">
                      <div class="input-group-text">₹</div>
                    </div>
                    <input type="text" id="min_amount" name="min_amount" placeholder="Minimum Amount" class="form-control min_amount" >
                </div>
            </div>

              <!-- <div class="form-group">
                <label for="coupon_uses">Max Number of Uses</label>
                  <input type="text" id="coupon_uses" name="coupon_uses" placeholder="Leave Blank for Unlimited" class="form-control" value="">
              </div> -->
          </div>
          <div class="modal-footer">
              <input type="hidden" name="coupon_id" id="coupon_id" value="">
            <button type="submit" class="btn btn-success">Save</button>
            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <div class="modal fade" id="AssigntoProduct" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel"> Assign Coupon To Product </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="CouponAssigntoProduct" method="post" enctype="multipart">
        {{ csrf_field() }}
          <div class="modal-body">

            <div class="form-group">
              <label for="p_coupon_code">Coupon Code</label>
                <div class="input-group mb-2 mr-sm-2">
                      <input type="text" id="p_coupon_code" class="form-control" name="coupon_code" placeholder="Coupon code" readonly>
                </div>
            </div>

            <div class="form-group">
              <label for="product_id">Select Product</label>
                <select class="form-control form-control-lg" id="product_id" name="product_id">
                  <option value="">Select</option>
                </select>
            </div>

          </div>
          <div class="modal-footer">
              <input type="hidden" name="coupon_id" id="coupon_id" value="">
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
<script src="{{asset('assets/js/fnStandingRedraw.js')}}"></script>
<script src="{{asset('assets/bundles/izitoast/js/iziToast.min.js')}}"></script>

<script>
$(document).ready(function (){

  var dataTable = $('#coupon-listing').dataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        "order": [[ 0, "desc" ]],
        'columnDefs': [ {
          'targets': [5], /* column index */
          'orderable': false, /* true or false */
        }],
        'ajax': {
            'url':'{{ route("showCouponList") }}',
            'data': function(data){
                // Read values
                // var user_id = $('#user_id').val();

                // Append to data
                // data.user_id = user_id;
            }
        }
        });


  // $(document).on('click', '.code_generate', function (e) {
  //   e.preventDefault();
  //   $('.loader').show();
  //   $.ajax({
  //       url: '{{ route("getCouponCode") }}',
  //       type: 'POST',
  //       dataType: "json",
  //       cache: false,
  //       success: function (response) {
  //         $('.loader').hide();
  //           if (response.success == 1) {
  //             if(response.coupon_code){
  //               $('#coupon_code').val(response.coupon_code);
  //             }
  //           }
  //       },
  //       error: function (jqXHR, textStatus, errorThrown) {
  //           alert(errorThrown);
  //       }
  //   });
  // });

  // $('#AssigntoProduct').on('hidden.bs.modal', function(e) {
    //     $("#AssigntoProduct")[0].reset();
    //     $('.modal-title').text('Assign Coupon To Product');
    //     var validator = $("#AssigntoProduct").validate();
    //     validator.resetForm();
    // });

    $(document).on('click', '#AssignCoupon', function (e) {
      $('.loader').show();
      var id = $(this).attr('data-id');
      var coupon_code = $(this).attr('data-coupon_code');
      $('#p_coupon_code').attr('value',coupon_code);
      $.ajax({
          url: '{{ route("getProductForCoupon") }}',
          type: 'POST',
          data: "",
          dataType: "json",
          cache: false,
          success: function (data) {
              $('.loader').hide();
              $('#product_id').html(""); 
              var html = '<option value="">Select</option>';

              if (data.success == 1) {
                $( data.data ).each(function(i,value) {
                  html += '<option value="'+value.product_id+'">'+value.name+'</option>';
                });
              }

              $('#product_id').html(html); 
          },
          error: function (jqXHR, textStatus, errorThrown) {
              alert(errorThrown);
          }
      });
    });

    $('#couponModal').on('hidden.bs.modal', function(e) {
        $("#addCoupon")[0].reset();
        $('.modal-title').text('Add Coupon');
        var validator = $("#addCoupon").validate();
        validator.resetForm();
    });
  
    $("#coupon-listing").on("click", ".updateCoupon", function() {
        $('.loader').show();
        $('.modal-title').text('Edit Coupon');
        var coupon_code = $(this).attr('data-coupon_code');
        console.log(coupon_code);
        var discount_type = $(this).attr('data-discount_type');
        var coupon_discount = $(this).attr('data-coupon_discount');
        var minimum_amount = $(this).attr('data-minimum_amount');
        var description = $(this).attr('data-description');
        $('#coupon_id').val($(this).attr('data-id'));
        $('#coupon_code').val(coupon_code);
        $('#discount_type').val(discount_type);
        $('#coupon_discount').val(coupon_discount);
        $('#min_amount').val(minimum_amount);
        $('#description').val(description);
        $('.loader').hide();
    });


    // $(document).on('change', '#discount_type', function (e) {
    //   var discount_type = $(this).val();
    //   if(discount_type == 1){
    //     $('.flat').removeClass('hide');
    //     $('.upto').addClass('hide');
    //   }else{
    //     $('.upto').removeClass('hide');
    //     $('.flat').addClass('hide');
    //   }
    // });

    $("#addCoupon").validate({
        rules: {
          coupon_code: {
                required: true,
                remote: {
                    url: '{{ route("CheckExistCoupon") }}',
                    type: "post",
                    data: {
                        coupon_code: function () { return $("#coupon_code").val(); },
                        coupon_id: function () { return $("#coupon_id").val(); },
                    }
                }
            },
          discount_type:{
            required: true,
          },
          coupon_discount: {
            required: true,
            digits: true
          },
          min_amount:{
            digits: true
          }
        },
        messages: {
          coupon_code: {
            required: "Please Enter Coupon",
            remote: "Coupon already Exist"
          },
          discount_type: {
              required: "Please Select Discount Type",
          },
          coupon_discount: {
              required: "Please Enter Discount Amount",
          },
          min_amount:{
            digits: "Please enter only digits."
          }
        },
      errorPlacement: function(error, element) {
            if (element.hasClass("coupon_discount") ) {
                $(error).insertAfter((element).parent());
            }
        }
    });
  
    $(document).on('submit', '#addCoupon', function (e) {
      e.preventDefault();
      
      var formdata = new FormData($("#addCoupon")[0]);
      $('.loader').show();
      $.ajax({
          url: '{{ route("addCoupon") }}',
          type: 'POST',
          data: formdata,
          dataType: "json",
          contentType: false,
          cache: false,
          processData: false,
          success: function (data) {
              $('.loader').hide();
              $('#couponModal').modal('hide');
              if (data.success == 1) {
  
                $('#coupon-listing').DataTable().ajax.reload(null, false);
                $('.total_coupon').text(data.total_coupon);
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

    $(document).on('click', '#DeleteCoupon', function (e) {
      e.preventDefault();
      var coupon_id = $(this).attr('data-id');
      var text = 'You will not be able to recover this data!';   
      var confirmButtonText = 'Yes, Delete it!';
      var btn = 'btn-danger';
      swal({
        title: "Are you sure?",
        text: text,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: btn,
        confirmButtonText: confirmButtonText,
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: false,
        closeOnCancel: false
      },
      function(isConfirm){
          if (isConfirm){
            $('.loader').show();
            $.ajax({
                url: '{{ route("deleteCoupon") }}',
                type: 'POST',
                data: {"coupon_id":coupon_id},
                dataType: "json",
                cache: false,
                success: function (data) {
                    $('.loader').hide();
                    $('#coupon-listing').DataTable().ajax.reload(null, false);
                    $('.total_coupon').text(data.total_coupon);
                    if (data.success == 1) {
                      swal("Confirm!", "Your coupon has been deleted!", "success");
                    } else {
                      swal("Confirm!", "Your coupon has not been deleted!", "error");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
          } else {
          swal("Cancelled", "Your imaginary file is safe :)", "error");
        }
      });
    });

    
     
    $("#CouponAssigntoProduct").validate({
        rules: {
          product_id: {
                required: true,
          }
        },
        messages: {
          product_id: {
              required: "Please Enter Product Id",
          }
        },
    });
  
    $(document).on('submit', '#CouponAssigntoProduct', function (e) {
      e.preventDefault();
      
      var formdata = new FormData($("#CouponAssigntoProduct")[0]);
      $('.loader').show();
      $.ajax({
          url: '{{ route("CouponAssigntoProduct") }}',
          type: 'POST',
          data: formdata,
          dataType: "json",
          contentType: false,
          cache: false,
          processData: false,
          success: function (data) {
              $('.loader').hide();
              $('#AssigntoProduct').modal('hide');
              if (data.success == 1) {
  
                $('#coupon-listing').DataTable().ajax.reload(null, false);

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

