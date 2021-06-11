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
              <h4>Unit List (<span class="total_unit">{{$total_unit}}</span>)</h4>
            </div>
            <div class="card-body">
              <div class="pull-right">
                <div class="buttons"> 
                  <button class="btn btn-primary text-light" data-toggle="modal" data-target="#unitModal" data-whatever="@mdo">Add Unit</button>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-striped" id="unit-listing">
                  <thead>
                    <tr>
                      <th>Unit Name</th>
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

<div class="modal fade" id="unitModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel"> Add Unit </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addUpdateUnit" method="post" enctype="multipart">
        {{ csrf_field() }}
        <div class="modal-body">

          <div class="form-group">
            <label for="unit_name">Unit Name</label>
            <input id="unit_name" name="unit_name" type="text" class="form-control form-control-danger">
          </div>
          </div>
          <div class="modal-footer">
              <input type="hidden" name="unit_id" id="unit_id" value="">
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

  var dataTable = $('#unit-listing').dataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        "order": [[ 0, "desc" ]],
        'columnDefs': [ {
          'targets': [1], /* column index */
          'orderable': false, /* true or false */
        }],
        'ajax': {
            'url':'{{ route("showUnitList") }}',
            'data': function(data){
                // Read values
                // var user_id = $('#user_id').val();

                // Append to data
                // data.user_id = user_id;
            }
        }
        });


    $('#unitModal').on('hidden.bs.modal', function(e) {
        $("#addUpdateUnit")[0].reset();
        $('.modal-title').text('Add Unit');
        $('#unit_id').val("");
        var validator = $("#addUpdateUnit").validate();
        validator.resetForm();
    });
  
    $("#unit-listing").on("click", ".UpdateUnit", function() {
        $('.loader').show();
        $('.modal-title').text('Edit Unit');
        var unit_name = $(this).attr('data-name');
        $('#unit_id').val($(this).attr('data-id'));
        $('#unit_name').val(unit_name);
        $('.loader').hide();
    });
  
    $("#addUpdateUnit").validate({
        rules: {
        unit_name: {
                required: true,
                remote: {
                    url: '{{ route("CheckExistUnit") }}',
                    type: "post",
                    data: {
                        unit_name: function () { return $("#unit_name").val(); },
                        unit_id: function () { return $("#unit_id").val(); },
                    }
                }
            }
        },
        messages: {
        unit_name: {
                required: "Please Enter Unit Name",
                remote: "Unit already Exist"
            }
        }
    });
  
    $(document).on('submit', '#addUpdateUnit', function (e) {
      e.preventDefault();
      
      var formdata = new FormData($("#addUpdateUnit")[0]);
      $('.loader').show();
      $.ajax({
          url: '{{ route("addUpdateUnit") }}',
          type: 'POST',
          data: formdata,
          dataType: "json",
          contentType: false,
          cache: false,
          processData: false,
          success: function (data) {
              $('.loader').hide();
              $('#unitModal').modal('hide');
              if (data.success == 1) {
  
                $('#unit-listing').DataTable().ajax.reload(null, false);
                $('.total_unit').text(data.total_unit);
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

    $(document).on('click', '#DeleteUnit', function (e) {
      e.preventDefault();
      var unit_id = $(this).attr('data-id');
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
                url: '{{ route("deleteUnit") }}',
                type: 'POST',
                data: {"unit_id":unit_id},
                dataType: "json",
                cache: false,
                success: function (data) {
                    $('.loader').hide();
                    $('#unit-listing').DataTable().ajax.reload(null, false);
                    $('.total_unit').text(data.total_unit);
                    if (data.success == 1) {
                      swal("Confirm!", "Your unit has been deleted!", "success");
                    } else {
                      swal("Confirm!", "Your unit has not been deleted!", "error");
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

