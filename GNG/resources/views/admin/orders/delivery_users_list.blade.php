@extends('admin_layouts/main')
@section('pageSpecificCss')
<link href="{{asset('assets/bundles/datatables/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/izitoast/css/iziToast.min.css')}}" rel="stylesheet">
<style>

  .field-icon {
    float: right;
    margin-right: 10px;
    margin-top: -28px;
    position: relative;
    z-index: 2;
    font-size: 18px;
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
              <h4>Delivery Boy List (<span class="total_delivery_boy">{{$total_delivery_boy}}</span>)</h4>
            </div>
            <div class="card-body">
                <div class="pull-right">
                    <div class="buttons">
                    <a id="addDeliveryUser" data-toggle="modal" data-target="#deliveryUserModal" class="btn btn-primary text-white">Add Delivery Boy</a>
                    </div>
                </div>
                
              <div class="table-responsive">
                <table class="table table-striped" id="delivery-boy-listing">
                  <thead>
                    <tr>
                    <th> Image </th>
                    <th> UserName </th>
                    <th> Mobile No </th>
                    <th> Full Name </th>
                    <th> Amount to Pay </th>
                    <th> Action </th>
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


<div class="modal fade" id="deliveryUserModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel"> Add User </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addUpdateDeliveryBoy" method="post" enctype="multipart">
        {{ csrf_field() }}
        <div class="modal-body">

          <div class="form-group">
            <label for="username">UserName</label>
            <input id="username" name="username" type="text" class="form-control form-control-danger">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
              <input id="password" type="password" class="form-control" name="password">
              <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
          </div>
          <div class="form-group">
            <label for="fullname">FullName</label>
            <input id="fullname" name="fullname" type="text" class="form-control form-control-danger">
          </div>
          <div class="form-group">
            <label for="mobile_no">Mobile Number</label>
            <input id="mobile_no" name="mobile_no" type="text" class="form-control form-control-danger">
          </div>
          </div>
          <div class="modal-footer">
              <input type="hidden" name="user_id" id="user_id" value="">
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
  var dataTable = $('#delivery-boy-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    'columnDefs': [ {
        'targets': [0,4,5], /* column index */
        'orderable': false, /* true or false */
     }],
    'ajax': {
        'url':'{{ route("showDeliveryUsersList") }}',
        'data': function(data){
            // Read values
            // var user_id = $('#user_id').val();

            // Append to data
            // data.user_id = user_id;
        }
    }
  });

  $(document).on('click',".toggle-password",function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });

  $('#deliveryUserModal').on('hidden.bs.modal', function(e) {
        $("#addUpdateDeliveryBoy")[0].reset();
        $('.modal-title').text('Add delivery-boy');
        $('#category_id').val("");
        var validator = $("#addUpdateDeliveryBoy").validate();
        validator.resetForm();
    });
  
    $('#delivery-boy-listing').on("click", ".updateDeliveryUser", function() {
        $('.loader').show();
        $('.modal-title').text('Edit User');
        var username = $(this).attr('data-username');
        var mobile_no = $(this).attr('data-mobile_no');
        var fullname = $(this).attr('data-fullname');
        $('#user_id').val($(this).attr('data-id'));
        $('#username').val(username);
        $('#fullname').val(fullname);
        $('#mobile_no').val(mobile_no);
        $('#password').val("");
        $('.loader').hide();
    });

    jQuery.validator.addMethod("noSpace", function(value, element) { 
      return value.indexOf(" ") < 0 && value != ""; 
    }, "Please enter userame without space.");

    $("#addUpdateDeliveryBoy").validate({
        rules: {
            username: {
                required: true,
                noSpace: true,
                remote: {
                    url: '{{ route("CheckExistUser") }}',
                    type: "post",
                    data: {
                        username: function () { return $("#username").val(); },
                        user_id: function () { return $("#user_id").val(); },
                    }
                }
            },
            password: {
                required: true,
            },
            fullname: {
                required: true,
            },
            mobile_no: {
                required: true,
                maxlength:10,
                minlength:10,
            },
        },
        messages: {
            username: {
                required: "Please Enter User Name",
                remote: "User already Exist"
            },
            password: {
                required: "Please Enter Password",
            },
            fullname: {
                required: "Please Enter User FullName",
            },
            mobile_no: {
                required: "Please Enter Mobile Number",
            }
        }
    });
  
    $(document).on('submit', '#addUpdateDeliveryBoy', function (e) {
      e.preventDefault();
      
      var formdata = new FormData($("#addUpdateDeliveryBoy")[0]);
      $('.loader').show();
      $.ajax({
          url: '{{ route("addUpdateDeliveryUsers") }}',
          type: 'POST',
          data: formdata,
          dataType: "json",
          contentType: false,
          cache: false,
          processData: false,
          success: function (data) {
              $('.loader').hide();
              $('#deliveryUserModal').modal('hide');
              if (data.success == 1) {
  
                $('#delivery-boy-listing').DataTable().ajax.reload(null, false);
                $('.total_delivery_boy').text(data.total_delivery_boy);
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

    $(document).on('click', '#deliveryDelete', function (e) {
      e.preventDefault();
      var user_id = $(this).attr('data-id');
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
                url: '{{ route("deleteDeliveryUsers") }}',
                type: 'POST',
                data: {"user_id":user_id},
                dataType: "json",
                cache: false,
                success: function (data) {
                    $('.loader').hide();
                    $('#delivery-boy-listing').DataTable().ajax.reload(null, false);
                    $('.total_delivery_boy').text(data.total_delivery_boy);

                    if (data.success == 1) {
                      swal("Confirm!", "Delivery Boy has been deleted!", "success");
                    } else {
                      swal("Confirm!", "Delivery Boy has not been deleted!", "error");
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

});
</script>

@endsection
