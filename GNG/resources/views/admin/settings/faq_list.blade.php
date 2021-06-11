@extends('admin_layouts/main')
@section('pageSpecificCss')
<link href="{{asset('assets/bundles/datatables/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/izitoast/css/iziToast.min.css')}}" rel="stylesheet">

@stop
@section('content')
<section class="section">
  <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>FAQ List (<span class="total_faq">{{$total_faq}}</span>)</h4>
            </div>
            <div class="card-body">
              <div class="pull-right">
                <div class="buttons"> 
                  <a href="{{route('faq/add')}}" class="btn btn-primary">Add FAQ</a>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-striped" id="faq-listing">
                  <thead>
                    <tr>
                      <th>Question</th>
                      <th>Answer</th>
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

<div class="modal fade" id="faqModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel"> Add FAQ </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addUpdateFAQ" method="post" enctype="multipart">
        {{ csrf_field() }}
        <div class="modal-body">

          <div class="form-group">
            <label for="faq_img">FAQ Image</label>
            <input type="file" id="faq_img" name="faq_img" class="form-control">
            <div id="photo_gallery" class="col-md-10 mt-4">
            </div>
            <input type="hidden" name="hidden_faq_img" id="hidden_faq_img" value="">
          </div>
          </div>
          <div class="modal-footer">
              <input type="hidden" name="faq_id" id="faq_id" value="">
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

  var dataTable = $('#faq-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    'columnDefs': [ {
      'targets': [0,2], 
      'orderable': false,
    }],
    "columnDefs": [
      { "width": "20%", "targets": 0 },
      { "width": "20%", "targets": 0 }
    ],
    'ajax': {
        'url':'{{ route("showFAQList") }}',
        'data': function(data){
        }
    }
  });


  $(document).on('click', '#DeleteFAQ', function (e) {
    e.preventDefault();
    var faq_id = $(this).attr('data-id');
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
              url: '{{ route("deleteFAQ") }}',
              type: 'POST',
              data: {"faq_id":faq_id},
              dataType: "json",
              cache: false,
              success: function (data) {
                  $('.loader').hide();
                  $('#faq-listing').DataTable().ajax.reload(null, false);
                  $('.total_faq').text(data.total_faq);
                  if (data.success == 1) {
                    swal("Confirm!", "FAQ has been deleted!", "success");
                  } else {
                    swal("Confirm!", "FAQ has not been deleted!", "error");
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

