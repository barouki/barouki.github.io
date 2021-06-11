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
              <h4>Banner List (<span class="total_banner">{{$total_banner}}</span>)</h4>
            </div>
            <div class="card-body">
              <div class="pull-right">
                <div class="buttons"> 
                  <button class="btn btn-primary text-light" data-toggle="modal" data-target="#bannerModal" data-whatever="@mdo">Add Banner</button>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-striped" id="banner-listing">
                  <thead>
                    <tr>
                      <th>Banner Image</th>
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

<div class="modal fade" id="bannerModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel"> Add Banner </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addUpdateBanner" method="post" enctype="multipart">
        {{ csrf_field() }}
        <div class="modal-body">

          <div class="form-group">
            <label for="banner_img">Banner Image</label>
            <input type="file" id="banner_img" name="banner_img" class="form-control">
            <div id="photo_gallery" class="col-md-10 mt-4">
            </div>
            <input type="hidden" name="hidden_banner_img" id="hidden_banner_img" value="">
          </div>
          </div>
          <div class="modal-footer">
              <input type="hidden" name="banner_id" id="banner_id" value="">
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

  var dataTable = $('#banner-listing').dataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        "order": [[ 0, "desc" ]],
        'columnDefs': [ {
          'targets': [0,1], /* column index */
          'orderable': false, /* true or false */
        }],
        'ajax': {
            'url':'{{ route("showBannerList") }}',
            'data': function(data){
                // Read values
                // var user_id = $('#user_id').val();

                // Append to data
                // data.user_id = user_id;
            }
        }
        });


    $('#banner_img').on('change', function() {
      imagesPreview(this, '#photo_gallery');
    });

    var imagesPreview = function(input, placeToInsertImagePreview) {

      if (input.files) {
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.jfif)$/i;
          if(!allowedExtensions.exec(input.value)){
            iziToast.error({
              title: 'Error!',
              message: 'Please upload file having extensions .jpeg/.jpg/.png only.',
              position: 'topRight'
            });
            input.value = '';
            return false;
          }else{

            var reader = new FileReader();

            reader.onload = function(event) {
              $(placeToInsertImagePreview).html('<div class="borderwrap" data-href="'+event.target.result+'"><div class="filenameupload"><img src="'+event.target.result+'" width="130" height="130"> </div></div>');
            }

            reader.readAsDataURL(input.files[0]);
          }
      }
    };

    $('#bannerModal').on('hidden.bs.modal', function(e) {
        $("#addUpdateBanner")[0].reset();
        $('.modal-title').text('Add Banner');
        $('#banner_id').val("");
        $('#photo_gallery').html("");
        $('#hidden_banner_img').val("");
        var validator = $("#addUpdateBanner").validate();
        validator.resetForm();
    });
  
    $("#banner-listing").on("click", ".UpdateBanner", function() {
        $('.loader').show();
        $('.modal-title').text('Edit Banner');
        var banner_img = $(this).attr('data-img');
        $('#banner_id').val($(this).attr('data-id'));
        var html = '<div class="borderwrap"><div class="filenameupload"><img src="{{env("DEFAULT_IMAGE_URL")}}'+banner_img+'" width="130" height="130"> </div>  </div>';
        $('#photo_gallery').html(html);
        $('#hidden_banner_img').val(banner_img);
        $('.loader').hide();
    });
  
    $("#addUpdateBanner").validate({
        rules: {
          banner_img:{
            required: {
                depends: function(element) {
                    return ($('#banner_id').val() == 0)
                } 
            }  
        } 
        },
        messages: {
          banner_img: {
                required: "Please Select Banner Image",
            }
        }
    });
  
    $(document).on('submit', '#addUpdateBanner', function (e) {
      e.preventDefault();
      
      var formdata = new FormData($("#addUpdateBanner")[0]);
      $('.loader').show();
      $.ajax({
          url: '{{ route("addUpdateBanner") }}',
          type: 'POST',
          data: formdata,
          dataType: "json",
          contentType: false,
          cache: false,
          processData: false,
          success: function (data) {
              $('.loader').hide();
              $('#bannerModal').modal('hide');
              if (data.success == 1) {
  
                $('#banner-listing').DataTable().ajax.reload(null, false);
                $('.total_banner').text(data.total_banner);
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

    $(document).on('click', '#DeleteBanner', function (e) {
      e.preventDefault();
      var banner_id = $(this).attr('data-id');
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
                url: '{{ route("deleteBanner") }}',
                type: 'POST',
                data: {"banner_id":banner_id},
                dataType: "json",
                cache: false,
                success: function (data) {
                    $('.loader').hide();
                    $('#banner-listing').DataTable().ajax.reload(null, false);
                    $('.total_banner').text(data.total_banner);
                    if (data.success == 1) {
                      swal("Confirm!", "Banner has been deleted!", "success");
                    } else {
                      swal("Confirm!", "Banner has not been deleted!", "error");
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

