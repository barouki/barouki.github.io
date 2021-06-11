@extends('admin_layouts/main')
@section('pageSpecificCss')
<link href="{{asset('assets/bundles/datatables/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@stop
@section('content')
<section class="section">
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                
                <div class="card-body padd-0">
                    <div >
                        <h5 class="font-normal font-18 card-title" style="display:inline-block;">Complaint - {{$data['complaint_id']}}</h5>

                        @if($data['status']==1)
                            <a class="badge badge-danger text-white pull-right">
                            Open
                        </a>
                        @else
                        <a class="badge badge-success text-white pull-right">
                            Close
                        </a>
                        @endif
                    </div>
                    <br/>
                    <p> Order ID : {{$data['order_id']}}</p>
                    <p> User Name : {{ucfirst($data['first_name'])}} {{ucfirst($data['last_name'])}}</p>
                    <p> Mobile Number : {{$data['mobile_no']}}</p>
                    <p> Title : {{$data['title']}}</p>
                    <p> Description : {{$data['description']}}</p>
                    @if($data['status']==1)
                    <a id="changeComplaintStatus" data-id="{{$data['complaint_id']}}" class="btn btn-success text-white" title="Move to Close">Close Complaint</a>
                    @endif
                </div>
        
            </div>
        </div>
     

    </div>
    
</section>


@endsection

@section('pageSpecificJs')

<script>
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

                    if (data.success == 1) {
                       $('#changeComplaintStatus').hide(); 
                       $('.badge').removeClass('badge-danger'); 
                       $('.badge').addClass('badge-success');                       
                       $('.badge').text('Close'); 
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
    </script>

@endsection