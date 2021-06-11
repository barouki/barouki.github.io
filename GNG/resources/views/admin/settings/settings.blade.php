@extends('admin_layouts/main')
@section('pageSpecificCss')
<link href="{{asset('assets/bundles/izitoast/css/iziToast.min.css')}}" rel="stylesheet">

<style type="text/css">
  .borderwrap {
    float: left;
    /* border: 1px dashed #000; */
    margin-right: 10px;
    border-radius: 6px;
    position: relative;
  }
  .middle {
	transition: .5s ease;
	opacity: 1;
	position: absolute;
	top: 4px;
  right: -22px;
	transform: translate(-50%, -50%);
	-ms-transform: translate(-50%, -50%)
}

.remove_img {
	color: #ffa117 !important;
  cursor:pointer;
}

</style>
@stop
@section('content')
<section class="section">
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="box-title">Add Shipping Charge</h4>
                </div>
                <div class="card-body">
                    <form class="forms-sample" id="addUpdateShipping">
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="shipping_charge">Shipping Charge</label>
                                <input type="text"  name="shipping_charge" class="form-control" id="shipping_charge" placeholder="" value="@if($data){{$data['shipping_charge']}}@endif" >
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Add</button>
                    </form>
                </div>
            </div>
        </div>

    </div>


    <div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="box-title">Notify Users</h4>
            </div>
            <div class="card-body">
                <form class="forms-sample" id="sendNotification">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="notification_topic">Select Topic</label>
                            <select name="notification_topic" class="form-control" id="notification_topic" >
                            <option value="Veggi">Veggi</option>
                            </select>
                        </div>

                        
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="notification_title">Title</label>
                            <input type="text" name="notification_title" class="form-control" id="notification_title" placeholder="Enter Title">
                        </div>

                        
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="notification_message">Message</label>
                            <textarea type="text" name="notification_message" class="form-control" id="notification_message" placeholder="Enter Message"></textarea>
                        </div>
                        <div class="form-group col-md-4">
                            <label> Image</label>
                            <input type="file" name="notify_image" class="form-control" id="notify_image">
                            <div id="photo_gallery" class="col-md-10 mt-4" style="display:none;">
                                
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                           
                        </div>
                    </div>
                   
                    <button type="submit" class="btn btn-primary mr-2">Send</button>
                </form>
            </div>
        </div>
    </div>

</div>
    
</section>


@endsection

@section('pageSpecificJs')

<script src="{{asset('assets/bundles/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/bundles/izitoast/js/iziToast.min.js')}}"></script>
<script>
$(document).ready(function (){
    $("#addUpdateShipping").validate({
      rules: {
        shipping_charge: {
          required: true,
        },
      },
      messages: {
        shipping_charge: {
          required: "Please Enter shpping charge",
        },
      },

    });

    $(document).on('change', '#notify_image', function() {
      var flag = $(this).attr('data-flag');
      imagesPreview(this, '#photo_gallery');
    });

    var imagesPreview = function(input, placeToInsertImagePreview) {

      if (input.files) {
        var filesAmount = input.files.length;
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.jfif|\.webp)$/i;
        if(!allowedExtensions.exec(input.value)){
            iziToast.error({
              title: 'Error!',
              message: 'Please upload file having extensions .jpeg/.jpg/.png only.',
              position: 'topRight'
            });
            input.value = '';
            return false;
          }else{
            $(placeToInsertImagePreview).attr('style','display:block');
            var reader = new FileReader();

            reader.onload = function(event) {
              $(placeToInsertImagePreview).append('<div class="borderwrap" data-href="'+event.target.result+'"><div class="filenameupload"><img src="'+event.target.result+'" width="130" height="130"> <div class="middle"><i class="material-icons remove_img">cancel</i></div> </div></div>');
            }

            reader.readAsDataURL(input.files[0]);
          }
      }
    };

    $(document).on('click','.remove_img', function(){

        var img_len = $('.borderwrap').length-1;
        var p_img = $(this).closest("div").parent().parent().attr('data-href');
        $(this).closest("div").parent().parent().remove();

    });

    $(document).on('submit', '#addUpdateShipping', function (e) {
        e.preventDefault();
        var formdata = new FormData($("#addUpdateShipping")[0]);
        $('.loader').show();
        $.ajax({
            url: '{{ route("addUpdateShipping") }}',
            type: 'POST',
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                $('.loader').hide();
                if (data.success == 1) {
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

    $("#sendNotification").validate({
      rules: {
        notification_topic: {
          required: true,
        },
        notification_message: {
          required: true,
        },
      },
      messages: {
        notification_topic: {
          required: "Please Select Topic",
        },
        notification_message: {
          required: "Please Enter Message",
        },
      },

    });

    $(document).on('submit', '#sendNotification', function (e) {
        e.preventDefault();
        var formdata = new FormData($("#sendNotification")[0]);
        $('.loader').show();
        $.ajax({
            url: '{{ route("sendNotification") }}',
            type: 'POST',
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                $('.loader').hide();
                $('#notification_message').val('');
                $("#photo_gallery").attr('style','display:none')
                if (data.success == 1) {
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

});
</script>

@endsection
