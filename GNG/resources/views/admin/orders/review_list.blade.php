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
              <h4>Review List (<span class="total_review">{{$total_review}}</span>)</h4>
            </div>
            <div class="card-body">	
                <div class="table-responsive">
                <table class="table table-striped" id="review-listing">
                    <thead>
                    <tr>
                        <th> Order Id </th>
                        <th> User Name </th>
                        <th> Review </th>
                        <th> Rating</th>
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
  var dataTable = $('#review-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    // 'columnDefs': [ {
    //     'targets': [5,6], /* column index */
    //     'orderable': false, /* true or false */
    //  }],
    'ajax': {
        'url':'{{ route("showOrderReviewList") }}',
        'data': function(data){
            // Read values
            // var user_id = $('#user_id').val();

            // Append to data
            // data.user_id = user_id;
        }
    }
  });

});
</script>

@endsection
