@extends('admin_layouts/main')
@section('pageSpecificCss')
<link href="{{asset('assets/bundles/datatables/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@stop
@section('content')
<section class="section">
  <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Complaint List (<span class="total_complaint">{{$total_complaint}}</span>)</h4>
            </div>
            <div class="tab" role="tabpanel">
              <ul class="nav nav-pills border-b mb-0 p-3">
								<li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#Section1" aria-controls="home" role="tab" data-toggle="tab">Open Compliant <span class="badge badge-transparent total_open_complaint">{{$total_open_complaint}}</span></a></li>
								<li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section2" role="tab" data-toggle="tab">Close Compliant <span class="badge badge-transparent total_close_complaint">{{$total_close_complaint}}</span></a></li>
							</ul>
              <div class="tab-content tabs" id="home">
							
              <div role="tabpanel" class="tab-pane active" id="Section1">
                <div class="card-body">	
                  <div class="table-responsive">
                    <table class="table table-striped" id="open-complaint-listing">
                      <thead>
                        <tr>
                        <th> Complaint Id </th>
                        <th> Order Id </th>
                        <th> User Name </th>
                        <th> Status </th>
                        <th> Created At </th>
                        <th> Move to Close </th>
                        <th> Action</th>
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
                    <table class="table table-striped" id="close-complaint-listing" width="100%">
                      <thead>
                        <tr>
                        <th> Complaint Id </th>
                        <th> Order Id </th>
                        <th> User Name </th>
                        <th> Status </th>
                        <th> Created At </th>
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
  </div>
</section>
@endsection

@section('pageSpecificJs')

<script src="{{asset('assets/bundles/datatables/datatables.min.js')}}"></script>
<script src="{{asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/bundles/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/js/page/datatables.js')}}"></script>
<script src="{{asset('assets/bundles/izitoast/js/iziToast.min.js')}}"></script>

<script>
$(document).ready(function (){
  var dataTable = $('#open-complaint-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    'columnDefs': [ {
        'targets': [5,6], /* column index */
        'orderable': false, /* true or false */
     }],
    'ajax': {
        'url':'{{ route("showOpenComplaintList") }}',
        'data': function(data){
            // Read values
            // var user_id = $('#user_id').val();

            // Append to data
            // data.user_id = user_id;
        }
    }
  });
  var dataTable = $('#close-complaint-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    'columnDefs': [ {
        'targets': [5], /* column index */
        'orderable': false, /* true or false */
     }],
    'ajax': {
        'url':'{{ route("showCloseComplaintList") }}',
        'data': function(data){
            // Read values
            // var user_id = $('#user_id').val();

            // Append to data
            // data.user_id = user_id;
        }
    }
  });

  $(document).on('click', '#changeComplaintStatus', function (e) {
      e.preventDefault();
      var complaint_id = $(this).attr('data-id');

      var text = 'You will not be able to recover this data!';   
      var confirmButtonText = 'Yes, Close it!';
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
              url: '{{ route("changeComplaintStatus") }}',
              type: 'POST',
              data: {"complaint_id":complaint_id, "status":0},
              dataType: "json",
              cache: false,
              success: function (data) {
                  $('.loader').hide();
                    $('#open-complaint-listing').DataTable().ajax.reload(null, false);
                    $('#close-complaint-listing').DataTable().ajax.reload(null, false);
                    $('.total_complaint').text(data.total_complaint);
                    $('.total_open_complaint').text(data.total_open_complaint);
                    $('.total_close_complaint').text(data.total_close_complaint);

                    if (data.success == 1) {
                      swal("Confirm!", "Your complaint has been closed!", "success");
                    } else {
                      swal("Confirm!", "Your complaint has not been closed!", "error");
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

    $(document).on('click', '#complaintDelete', function (e) {
      e.preventDefault();
      var complaint_id = $(this).attr('data-id');

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
                url: '{{ route("deleteComplaint") }}',
                type: 'POST',
                data: {"complaint_id":complaint_id},
                dataType: "json",
                cache: false,
                success: function (data) {
                    $('.loader').hide();
                    $('.total_complaint').text(data.total_complaint);
                    $('.total_open_complaint').text(data.total_open_complaint);
                    $('.total_close_complaint').text(data.total_close_complaint);

                    if (data.success == 1) {
                      swal("Confirm!", "Your complaint has been deleted!", "success");
                    } else {
                      swal("Confirm!", "Your complaint has not been deleted!", "error");
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
