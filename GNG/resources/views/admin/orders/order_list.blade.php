@extends('admin_layouts/main')
@section('pageSpecificCss')
<link href="{{asset('assets/bundles/datatables/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@stop
@section('content')
<section class="section">
  <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Order List (<span class="total_order">{{$total_order}}</span>)</h4>
            </div>
            <div class="card-body">
              <div class="col-md-3 pull-right">
                <div class="form-group">
                  <label>Filter By Ordered Date</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-calendar"></i>
                      </div>
                    </div>
                    <input type="text" class="form-control change_orderdata">
                    <input type="hidden" class="form-control" id="startdate">
                    <input type="hidden" class="form-control" id="enddate">
                  </div>
                </div>
              </div>

            <div class="tab" role="tabpanel">
              <ul class="nav nav-pills border-b mb-0 p-3">
								<li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#Section1" aria-controls="home" role="tab" data-toggle="tab">All Orders <span class="badge badge-transparent total_order">{{$total_order}}</span></a></li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section2" role="tab" data-toggle="tab">Processing <span class="badge badge-transparent total_processing_order">{{$total_processing_order}}</span></a></li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section3" role="tab" data-toggle="tab">Confirmed <span class="badge badge-transparent total_confirmed_order">{{$total_confirmed_order}}</span></a></li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section4" role="tab" data-toggle="tab"> On hold <span class="badge badge-transparent total_onhold_order">{{$total_onhold_order}}</span></a></li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section5" role="tab" data-toggle="tab">Completed <span class="badge badge-transparent total_completed_order">{{$total_completed_order}}</span></a></li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section6" role="tab" data-toggle="tab">Cancelled <span class="badge badge-transparent total_cancelled_order">{{$total_cancelled_order}}</span></a></li>
							</ul>
              <div class="tab-content tabs" id="home">
							
                <div role="tabpanel" class="tab-pane active" id="Section1">
                  <div class="card-body">	
                    <div class="table-responsive">
                      <table class="table table-striped" id="order-listing">
                        <thead>
                          <tr>
                          <th> Order Id </th>
                          <th> User Name </th>
                          <th> Total </th>
                          <th> Status </th>
                          <th> Payment Type </th>
                          <th> Order At </th>
                          <th> Action</th>
                          <th> Start Delivery</th>

                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="Section2">
                  <div class="card-body">	
                    <div class="table-responsive">
                      <table class="table table-striped" id="processiong-order-listing" width="100%">
                        <thead>
                          <tr>
                          <th> Order Id </th>
                          <th> User Name </th>
                          <th> Total </th>
                          <th> Status </th>
                          <th> Payment Type </th>
                          <th> Order At </th>
                          <th> Action</th>
                          <th> Start Delivery</th>

                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="Section3">
                  <div class="card-body">	
                    <div class="table-responsive">
                      <table class="table table-striped" id="confirmed-order-listing" width="100%">
                        <thead>
                          <tr>
                          <th> Order Id </th>
                          <th> User Name </th>
                          <th> Total </th>
                          <th> Status </th>
                          <th> Payment Type </th>
                          <th> Order At </th>
                          <th> Action</th>
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="Section5">
                  <div class="card-body">	
                    <div class="table-responsive">
                      <table class="table table-striped" id="completed-order-listing" width="100%">
                        <thead>
                          <tr>
                          <th> Order Id </th>
                          <th> User Name </th>
                          <th> Total </th>
                          <th> Status </th>
                          <th> Payment Type </th>
                          <th> Order At </th>
                          <th> Action</th>
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                
                <div role="tabpanel" class="tab-pane" id="Section4">
                  <div class="card-body">	
                    <div class="table-responsive">
                      <table class="table table-striped" id="onhold-order-listing" width="100%">
                        <thead>
                          <tr>
                          <th> Order Id </th>
                          <th> User Name </th>
                          <th> Total </th>
                          <th> Status </th>
                          <th> Payment Type </th>
                          <th> Order At </th>
                          <th> Action</th>
                          <th> Start Delivery</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="Section6">
                  <div class="card-body">	
                    <div class="table-responsive">
                      <table class="table table-striped" id="cancelled-order-listing" width="100%">
                        <thead>
                          <tr>
                          <th> Order Id </th>
                          <th> User Name </th>
                          <th> Total </th>
                          <th> Status </th>
                          <th> Payment Type </th>
                          <th> Order At </th>
                          <th> Action</th>
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
      </div>
    </div>
  </div>
</section>


<div class="modal fade" id="assignUserModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel"> Assign User </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="assignDeliveryBoy" method="post" enctype="multipart">
        {{ csrf_field() }}
        <div class="modal-body">

          <div class="form-group">
            <label for="delivery_boy_user_id">DeliveryBoy</label>
            <select id="delivery_boy_user_id" name="delivery_boy_user_id" class="form-control form-control-danger">
                <option value="">Select</option>
            </select>
          </div>
          </div>
          <div class="modal-footer">
              <input type="hidden" name="order_id" id="order_id" value="">
            <button type="submit" class="btn btn-success">Confirm</button>
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
<script src="{{asset('assets/bundles/izitoast/js/iziToast.min.js')}}"></script>
<script src="{{asset('assets/bundles/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script>
$(document).ready(function (){
  var dataTable = $('#order-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    'columnDefs': [ {
        'targets': [6,7], /* column index */
        'orderable': false, /* true or false */
     }],
    'ajax': {
        'url':'{{ route("showOrderList") }}',
        'data': function(data){
          // var change_order_status = $('#change_order_status').val();
          data.status = 0;
          data.startdate = $("#startdate").val();
          data.enddate = $("#enddate").val();
        }
    }
  });

  var dataTable2 = $('#processiong-order-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    'columnDefs': [ {
        'targets': [6,7], /* column index */
        'orderable': false, /* true or false */
     }],
    'ajax': {
        'url':'{{ route("showOrderList") }}',
        'data': function(data){
          data.status = 1;
          data.startdate = $("#startdate").val();
          data.enddate = $("#enddate").val();
        }
    }
  });

  
  var dataTable3 = $('#confirmed-order-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    'columnDefs': [ {
        'targets': [6], /* column index */
        'orderable': false, /* true or false */
     }],
    'ajax': {
        'url':'{{ route("showOrderList") }}',
        'data': function(data){
          data.status = 2;
          data.startdate = $("#startdate").val();
          data.enddate = $("#enddate").val();
        }
    }
  });

  
  
  var dataTable5 = $('#completed-order-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    'columnDefs': [ {
        'targets': [6], /* column index */
        'orderable': false, /* true or false */
     }],
    'ajax': {
        'url':'{{ route("showOrderList") }}',
        'data': function(data){
          data.status = 3;
          data.startdate = $("#startdate").val();
          data.enddate = $("#enddate").val();
        }
    }
  });

  var dataTable4 = $('#onhold-order-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    'columnDefs': [ {
        'targets': [6,7], /* column index */
        'orderable': false, /* true or false */
     }],
    'ajax': {
        'url':'{{ route("showOrderList") }}',
        'data': function(data){
          data.status = 4;
          data.startdate = $("#startdate").val();
          data.enddate = $("#enddate").val();
        }
    }
  });
  
  var dataTable6 = $('#cancelled-order-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    'columnDefs': [ {
        'targets': [6], /* column index */
        'orderable': false, /* true or false */
     }],
    'ajax': {
        'url':'{{ route("showOrderList") }}',
        'data': function(data){
          data.status = 5;
          data.startdate = $("#startdate").val();
          data.enddate = $("#enddate").val();
        }
    }
  });
  
  $('.change_orderdata').daterangepicker({
      locale: { format: 'YYYY-MM-DD' },
      drops: 'down',
      opens: 'right',
      // minDate: new Date(),
      setDate: new Date()
    });
    $('.change_orderdata').on('apply.daterangepicker', function (ev, picker) {
      
      var startdate = picker.startDate.format('YYYY-MM-DD');
      var enddate = picker.endDate.format('YYYY-MM-DD');
      $("#startdate").val(startdate);
      $("#enddate").val(enddate);
      $('#order-listing').DataTable().ajax.reload(null, false);
      $('#processiong-order-listing').DataTable().ajax.reload(null, false);
      $('#confirmed-order-listing').DataTable().ajax.reload(null, false);
      $('#completed-order-listing').DataTable().ajax.reload(null, false);
      $('#onhold-order-listing').DataTable().ajax.reload(null, false);
      $('#cancelled-order-listing').DataTable().ajax.reload(null, false);
    });

  $('#assignUserModal').on('hidden.bs.modal', function(e) {
        $("#assignDeliveryBoy")[0].reset();
        $('.modal-title').text('Assign User');
        $('#delivery_boy_user_id').val("");
        $('#order_id').val("");
        var validator = $("#assignDeliveryBoy").validate();
        validator.resetForm();
    });
  
    $(document).on("click", ".assignUser", function() {
        $('.loader').show();
        $('.modal-title').text('Assign User');
        var order_id = $(this).attr('data-id')
        var user_id = $(this).attr('data-delivery_boy_user_id');
        $.ajax({
          url: '{{ route("getDeliveryBoyList") }}',
          type: 'POST',
          data: {},
          dataType: "json",
          cache: false,
          success: function (data) {
              $('.loader').hide();
              var html = '<option value="">Select</option>';
              if (data.success == 1) {
                $('#order_id').val(order_id);
                $(data.data).each(function( index,value ) {
                  if(user_id == value.user_id){
                    var selected = 'selected';
                  }else{
                    var selected = '';
                  }
                  html += '<option value="'+value.user_id+'" '+selected+'>'+value.fullname+'</option>';
                });
              } 
              $('#delivery_boy_user_id').html(html);
          },
          error: function (jqXHR, textStatus, errorThrown) {
              alert(errorThrown);
          }
      });
    });


    $("#assignDeliveryBoy").validate({
        rules: {
            delivery_boy_user_id: {
                required: true,
            },
        },
        messages: {
            delivery_boy_user_id: {
                required: "Please Select User",
            },
        }
    });

    $(document).on('submit', '#assignDeliveryBoy', function (e) {
      e.preventDefault();
      
      var formdata = new FormData($("#assignDeliveryBoy")[0]);
      $('.loader').show();

      $.ajax({
          url: '{{ route("assignDeliveryBoy") }}',
          type: 'POST',
          data: formdata,
          dataType: "json",
          contentType: false,
          cache: false,
          processData: false,
          success: function (data) {
              $('.loader').hide();
              if (data.success == 1) {
                $('#assignUserModal').modal('hide');
                $('#order-listing').DataTable().ajax.reload(null, false);
                $('#processiong-order-listing').DataTable().ajax.reload(null, false);
                $('#confirmed-order-listing').DataTable().ajax.reload(null, false);
                $('#completed-order-listing').DataTable().ajax.reload(null, false);
                $('#onhold-order-listing').DataTable().ajax.reload(null, false);
                $('#cancelled-order-listing').DataTable().ajax.reload(null, false);

                $('.total_order').text(data.total_order);
                $('.total_processing_order').text(data.total_processing_order);
                $('.total_confirmed_order').text(data.total_confirmed_order);
                $('.total_onhold_order').text(data.total_onhold_order);
                $('.total_completed_order').text(data.total_completed_order);
                $('.total_cancelled_order').text(data.total_cancelled_order);

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

  $(document).on('click', '#orderDelete', function (e) {
      e.preventDefault();
      var order_id = $(this).attr('data-id');
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
                url: '{{ route("deleteOrder") }}',
                type: 'POST',
                data: {"order_id":order_id},
                dataType: "json",
                cache: false,
                success: function (data) {
                    $('.loader').hide();
                      
                      $('#order-listing').DataTable().ajax.reload(null, false);
                      $('#processiong-order-listing').DataTable().ajax.reload(null, false);
                      $('#confirmed-order-listing').DataTable().ajax.reload(null, false);
                      $('#completed-order-listing').DataTable().ajax.reload(null, false);
                      $('#onhold-order-listing').DataTable().ajax.reload(null, false);
                      $('#cancelled-order-listing').DataTable().ajax.reload(null, false);

                      $('.total_order').text(data.total_order);
                      $('.total_processing_order').text(data.total_processing_order);
                      $('.total_confirmed_order').text(data.total_confirmed_order);
                      $('.total_onhold_order').text(data.total_onhold_order);
                      $('.total_completed_order').text(data.total_completed_order);
                      $('.total_cancelled_order').text(data.total_cancelled_order);

                    if (data.success == 1) {
                      swal("Confirm!", "Your order has been deleted!", "success");
                    } else {
                      swal("Confirm!", "Your order has not been deleted!", "error");
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
