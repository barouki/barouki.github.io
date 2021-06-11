@extends('admin_layouts/main')
@section('pageSpecificCss')
<link href="{{asset('assets/bundles/datatables/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@stop
@section('content')
<section class="section">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                
                <div class="card-body padd-0 text-center">
                    <div class="card-avatar style-2">
                        <?php
                            if(!empty($data['profile_image']))
                            {
                                ?>
                                <img height="150px" width="150px" src="{{env('DEFAULT_IMAGE_URL').$data['profile_image']}}" class="rounded-circle author-box-picture mb-2" alt="">
                                <?php
                            }
                            else
                            {
                                ?>
                                <img height="150px" width="150px" src="{{asset('assets/dist/img/logo.png')}}" class="rounded-circle author-box-picture mb-2" alt="">
                                <?php
                            }
                        ?>
                        
                    </div>
                    <h5 class="font-normal mrg-bot-0 font-18 card-title">{{$data['fullname']}}</h5>
                    <h6 class="font-normal mrg-bot-0 font-15 card-title">{{$data['username']}}</h6>
                    <h6 class="font-normal mrg-bot-0 font-15 card-title">{{$data['mobile_no']}}</h6>
                </div>
        
            </div>
        </div>
        <div class="col-md-6">
                <div class="card" >
                    
                <div class="card-header">
                <h4 class="box-title">Payment Details</h4>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-body pb-5">
                            
                            <p> Delivery Boy Amount to Pay : ₹<span class="pay_amount">@if($payData){{$payData}}@else{{0}}@endif</span></p>
                            <button class="btn btn-success text-white" title="Payment Resolve" data-user_id="{{$data['user_id']}}" id="paymentResolve">Payment Resolve</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="box-title">List of Delivery ({{count($itemData)}})</h4>
                </div>
                <div class="card-body">
                <input type="hidden" id="user_id" value="{{$data['user_id']}}">
                <div class="tab" role="tabpanel">
                    <ul class="nav nav-pills border-b mb-0 p-3">
                         <li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#Section3" role="tab" data-toggle="tab">Completed</a></li>
                        <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section1" role="tab" data-toggle="tab">Confirmed</a></li>
                        <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section2" role="tab" data-toggle="tab"> On hold</a></li>
                    </ul>
                    <div class="tab-content tabs" id="home">
                                    
                        <div role="tabpanel" class="tab-pane" id="Section1">
                            <div class="card-body">	
                                <div class="table-responsive">
                                    <table class="table table-striped" id="delivery-confirmed-listing" width="100%">
                                        <thead>
                                        <tr>
                                            <th> Order Id </th>
                                            <th> User Name </th>
                                            <th> Area </th>
                                            <th> Total </th>
                                            <th> Payment Type </th>
                                            <th> Status </th>
                                            <th> Order At </th>
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
                                    <table class="table table-striped" id="delivery-onhold-listing" width="100%">
                                        <thead>
                                        <tr>
                                            <th> Order Id </th>
                                            <th> User Name </th>
                                            <th> Area </th>
                                            <th> Total </th>
                                            <th> Payment Type </th>
                                            <th> Status </th>
                                            <th> Order At </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane active" id="Section3">
                            <div class="card-body">	
                                <div class="table-responsive">
                                    <table class="table table-striped" id="delivery-completed-listing" width="100%">
                                        <thead>
                                        <tr>
                                            <th> Order Id </th>
                                            <th> User Name </th>
                                            <th> Area </th>
                                            <th> Total </th>
                                            <th> Payment Type </th>
                                            <th> Status </th>
                                            <th> Order At </th>
                                            <th> Completed At </th>
                                            <th> Payment Status</th>
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


@endsection

@section('pageSpecificJs')

<script src="{{asset('assets/bundles/datatables/datatables.min.js')}}"></script>
<script src="{{asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/bundles/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/js/page/datatables.js')}}"></script>

<script>
$(document).ready(function (){
    var dataTable3 = $('#delivery-confirmed-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    'ajax': {
        'url':'{{ route("showDeliveryOrderList") }}',
        'data': function(data){
          data.status = 2;
          data.user_id = $("#user_id").val();
        }
    }
  });

  
  
  var dataTable5 = $('#delivery-completed-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    'ajax': {
        'url':'{{ route("showDeliveryOrderList") }}',
        'data': function(data){
          data.status = 3;
          data.user_id = $("#user_id").val();
        }
    }
  });

  var dataTable4 = $('#delivery-onhold-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    'ajax': {
        'url':'{{ route("showDeliveryOrderList") }}',
        'data': function(data){
          data.status = 4;
          data.user_id = $("#user_id").val();
        }
    }
  });

  $(document).on('click', '#paymentResolve', function (e) {
      e.preventDefault();
      var user_id = $(this).attr('data-user_id');

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
                url: '{{ route("paymentResolve") }}',
                type: 'POST',
                data: {"user_id":user_id},
                dataType: "json",
                cache: false,
                success: function (data) {
                    $('.loader').hide();
                    $('.pay_amount').text(0);
                    $('#delivery-completed').DataTable().ajax.reload(null, false);
                    if (data.success == 1) {
                      swal("Confirm!", "Payment Resolved Successfully!", "success");
                    } else {
                      swal("Confirm!", "Payment Not Resolved!", "error");
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
