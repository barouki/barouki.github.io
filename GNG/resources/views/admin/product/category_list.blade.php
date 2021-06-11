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
              <h4>Category List (<span class="total_category">{{$total_category}}</span>)</h4>
            </div>
            <div class="card-body">
              <div class="pull-right">
                <div class="buttons"> 
                  <button class="btn btn-primary text-light" data-toggle="modal" data-target="#categoryModal" data-whatever="@mdo">Add Category</button>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-striped" id="category-listing">
                  <thead>
                    <tr>
                      <th>Category Name</th>
                      <th>View Products</th>
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
<script src="{{asset('assets/js/fnStandingRedraw.js')}}"></script>
<script src="{{asset('assets/bundles/izitoast/js/iziToast.min.js')}}"></script>

<script>
$(document).ready(function (){

  var dataTable = $('#category-listing').dataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        "order": [[ 0, "desc" ]],
        'columnDefs': [ {
          'targets': [1], /* column index */
          'orderable': false, /* true or false */
        }],
        'ajax': {
            'url':'{{ route("showCategoryList") }}',
            'data': function(data){
                // Read values
                // var user_id = $('#user_id').val();

                // Append to data
                // data.user_id = user_id;
            }
        }
        });


    $('#categoryModal').on('hidden.bs.modal', function(e) {
        $("#addUpdateCategory")[0].reset();
        $('.modal-title').text('Add Category');
        $('#category_id').val("");
        var validator = $("#addUpdateCategory").validate();
        validator.resetForm();
    });
  
    $("#category-listing").on("click", ".UpdateCategory", function() {
        $('.loader').show();
        $('.modal-title').text('Edit Category');
        var category_name = $(this).attr('data-name');
        $('#category_id').val($(this).attr('data-id'));
        $('#category_name').val(category_name);
        $('.loader').hide();
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
              $('#categoryModal').modal('hide');
              if (data.success == 1) {
  
                $('#category-listing').DataTable().ajax.reload(null, false);
                $('.total_category').text(data.total_category);
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

    $(document).on('click', '#DeleteCategory', function (e) {
      e.preventDefault();
      var category_id = $(this).attr('data-id');
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
                url: '{{ route("deleteCategory") }}',
                type: 'POST',
                data: {"category_id":category_id},
                dataType: "json",
                cache: false,
                success: function (data) {
                    $('.loader').hide();
                    $('#category-listing').DataTable().ajax.reload(null, false);
                    $('.total_category').text(data.total_category);

                    if (data.success == 1) {
                      swal("Confirm!", "Your category has been deleted!", "success");
                    } else {
                      swal("Confirm!", "Your category has not been deleted!", "error");
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

