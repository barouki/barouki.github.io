@extends('admin_layouts/main')
@section('pageSpecificCss')
<link href="{{asset('assets/css/jquery.timepicker.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/izitoast/css/iziToast.min.css')}}" rel="stylesheet">

<style type="text/css">
  .ul-font>li {
    font-weight: 400;
    font-size: 15px;
  }

  .form-group {
    margin-bottom: 15px !important;
  }
.auto_fill{
  color: #efb45f !important;
}
</style>

@stop
@section('content')
<section class="section">
  <div class="section-body">

<div class="row">
  <div class="col-12 col-md-12 col-lg-12">
    <div class="card">
      <div class="card-body">
        <div class="card-header">
          <h4>{{$title}} Delivery Option</h4>
        </div>
        <div class="card-body">
          <form class="forms-sample" id="addUpdateDelivery">
            {{ csrf_field() }}
            @if($data)
            <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="area_name">Area Name</label>
                    <input type="text"  name="area_name[]" class="form-control" id="area_name" placeholder="Name" value="@if($data){{$data[0]['area_name']}}@endif">
                  </div>
              </div>
            @else
              <input type="hidden" id="hidden_count" value="1">
              <div class="form-row areadiv">
                  <div class="form-group col-md-4">
                    <label for="area_name">Area Name</label>
                    <input type="text"  name="area_name[]" class="form-control" id="area_name" placeholder="Name" value="@if($data){{$data[0]['area_name']}}@endif">
                  </div>
                  <div class="form-group col-md-1">
                    <label for="product_price"></label>
                    <div class="buttons mt-2">
                      <a class="btn btn-info text-light add_delivery"><i class="fa fa-plus"></i></a>
                    </div>
                  </div>
              
              </div>
            @endif
           
            @if($data)
            <?php $i=1; ?>
              @foreach($data as $val)
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="week_day">Week Day</label>
                    <input type="text"  name="week_day[]" class="form-control" id="week_day" placeholder="" value="{{$val['week_day']}}" readonly>
                  </div>
                  <div class="form-group col-md-3">
                    <label for="start_time">Start Time</label>
                    <input type="text"  name="week_days[{{$val['week_day']}}][start_time]" class="form-control" id="start_time{{$i}}" value="@if($val['start_time']){{$val['start_time']}}@endif">
                    @if($i==1)
                    <p class="error"></p>
                    <p>Auto Fill Other Day Start Time <a class="auto_fill" data-id="1">Click here</a></p>
                    @endif
                  </div>
                  <div class="form-group col-md-3">
                    <label for="end_time">End Time</label>
                    <input type="text"  name="week_days[{{$val['week_day']}}][end_time]" class="form-control" id="end_time{{$i}}" value="@if($val['end_time']){{$val['end_time']}}@endif">
                    @if($i==1)
                    <p class="error"></p>
                    <p>Auto Fill Other Day End Time <a class="auto_fill" data-id="2">Click here</a></p>
                    @endif
                  </div>
                  <div class="form-group col-md-2">
                      <label for="is_offday">Is Off Day</label>
                      <select name="week_days[{{$val['week_day']}}][is_offday]" class="form-control" id="is_offday">
                          <option value="0" @if($val['is_offday'] == 0){{"selected"}}@endif>No</option>
                          <option value="1" @if($val['is_offday'] == 1){{"selected"}}@endif>Yes</option>
                      </select>
                  </div>
                  </div>
                  <?php $i++; ?>
                  @endforeach      
                @else
                <div class="form-row">
                    <div class="form-group col-md-4">
                      <label for="week_day">Week Day</label>
                      <input type="text"  name="week_day[]" class="form-control" id="week_day" placeholder="" value="Monday" readonly>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="start_time">Start Time</label>
                      <input type="text"  name="week_days[Monday][start_time]" class="form-control" id="start_time1" value="@if($data){{$data['start_time']}}@endif">
                      <p class="error"></p>
                      <p>Auto Fill Other Day Start Time <a class="auto_fill" data-id="1">Click here</a></p>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="end_time">End Time</label>
                      <input type="text"  name="week_days[Monday][end_time]" class="form-control" id="end_time1" value="@if($data){{$data['end_time']}}@endif">
                      <p class="error"></p>
                      <p>Auto Fill Other Day End Time <a class="auto_fill" data-id="2">Click here</a></p>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="is_offday">Is Off Day</label>
                        <select name="week_days[Monday][is_offday]" class="form-control" id="is_offday">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                  </div>

                  <div class="form-row">
                      <div class="form-group col-md-4">
                        <label for="week_day">Week Day</label>
                        <input type="text"  name="week_day[]" class="form-control" id="week_day" placeholder="" value="Tuesday" readonly>
                      </div>
                      <div class="form-group col-md-3">
                        <label for="start_time">Start Time</label>
                        <input type="text"  name="week_days[Tuesday][start_time]" class="form-control" id="start_time2" value="@if($data){{$data['start_time']}}@endif">
                      </div>
                      <div class="form-group col-md-3">
                        <label for="end_time">End Time</label>
                        <input type="text"  name="week_days[Tuesday][end_time]" class="form-control" id="end_time2" value="@if($data){{$data['end_time']}}@endif">
                      </div>
                      <div class="form-group col-md-2">
                          <label for="is_offday">Is Off Day</label>
                          <select name="week_days[Tuesday][is_offday]" class="form-control" id="is_offday">
                              <option value="0">No</option>
                              <option value="1">Yes</option>
                          </select>
                      </div>
                  </div>

                  <div class="form-row">
                      <div class="form-group col-md-4">
                        <label for="week_day">Week Day</label>
                        <input type="text"  name="week_day[]" class="form-control" id="week_day" placeholder="" value="Wednesday" readonly>
                      </div>
                      <div class="form-group col-md-3">
                        <label for="start_time">Start Time</label>
                        <input type="text"  name="week_days[Wednesday][start_time]" class="form-control" id="start_time3" value="@if($data){{$data['start_time']}}@endif">
                      </div>
                      <div class="form-group col-md-3">
                        <label for="end_time">End Time</label>
                        <input type="text"  name="week_days[Wednesday][end_time]" class="form-control" id="end_time3" value="@if($data){{$data['end_time']}}@endif">
                      </div>
                      <div class="form-group col-md-2">
                          <label for="is_offday">Is Off Day</label>
                          <select name="week_days[Wednesday][is_offday]" class="form-control" id="is_offday">
                              <option value="0">No</option>
                              <option value="1">Yes</option>
                          </select>
                      </div>
                  </div>

                  <div class="form-row">
                      <div class="form-group col-md-4">
                        <label for="week_day">Week Day</label>
                        <input type="text"  name="week_day[]" class="form-control" id="week_day" placeholder="" value="Thursday" readonly>
                      </div>
                      <div class="form-group col-md-3">
                        <label for="start_time">Start Time</label>
                        <input type="text"  name="week_days[Thursday][start_time]" class="form-control" id="start_time4" value="@if($data){{$data['start_time']}}@endif">
                      </div>
                      <div class="form-group col-md-3">
                        <label for="end_time">End Time</label>
                        <input type="text"  name="week_days[Thursday][end_time]" class="form-control" id="end_time4" value="@if($data){{$data['end_time']}}@endif">
                      </div>
                      <div class="form-group col-md-2">
                          <label for="is_offday">Is Off Day</label>
                          <select name="week_days[Thursday][is_offday]" class="form-control" id="is_offday">
                              <option value="0">No</option>
                              <option value="1">Yes</option>
                          </select>
                      </div>
                  </div>

                  <div class="form-row">
                      <div class="form-group col-md-4">
                        <label for="week_day">Week Day</label>
                        <input type="text"  name="week_day[]" class="form-control" id="week_day" placeholder="" value="Friday" readonly>
                      </div>
                      <div class="form-group col-md-3">
                        <label for="start_time">Start Time</label>
                        <input type="text"  name="week_days[Friday][start_time]" class="form-control" id="start_time5" value="@if($data){{$data['start_time']}}@endif">
                      </div>
                      <div class="form-group col-md-3">
                        <label for="end_time">End Time</label>
                        <input type="text"  name="week_days[Friday][end_time]" class="form-control" id="end_time5" value="@if($data){{$data['end_time']}}@endif">
                      </div>
                      <div class="form-group col-md-2">
                          <label for="is_offday">Is Off Day</label>
                          <select name="week_days[Friday][is_offday]" class="form-control" id="is_offday">
                              <option value="0">No</option>
                              <option value="1">Yes</option>
                          </select>
                      </div>
                  </div>

                  <div class="form-row">
                      <div class="form-group col-md-4">
                        <label for="week_day">Week Day</label>
                        <input type="text"  name="week_day[]" class="form-control" id="week_day" placeholder="" value="Saturday" readonly>
                      </div>
                      <div class="form-group col-md-3">
                        <label for="start_time">Start Time</label>
                        <input type="text"  name="week_days[Saturday][start_time]" class="form-control" id="start_time6" value="@if($data){{$data['start_time']}}@endif">
                      </div>
                      <div class="form-group col-md-3">
                        <label for="end_time">End Time</label>
                        <input type="text"  name="week_days[Saturday][end_time]" class="form-control" id="end_time6" value="@if($data){{$data['end_time']}}@endif">
                      </div>
                      <div class="form-group col-md-2">
                          <label for="is_offday">Is Off Day</label>
                          <select name="week_days[Saturday][is_offday]" class="form-control" id="is_offday">
                              <option value="0">No</option>
                              <option value="1">Yes</option>
                          </select>
                      </div>
                  </div>

                  <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="week_day">Week Day</label>
                  <input type="text"  name="week_day[]" class="form-control" id="week_day" placeholder="" value="Sunday" readonly>
                </div>
                <div class="form-group col-md-3">
                  <label for="start_time">Start Time</label>
                  <input type="text"  name="week_days[Sunday][start_time]" class="form-control" id="start_time7" value="@if($data){{$data['start_time']}}@endif">
                </div>
                <div class="form-group col-md-3">
                  <label for="end_time">End Time</label>
                  <input type="text"  name="week_days[Sunday][end_time]" class="form-control" id="end_time7" value="@if($data){{$data['end_time']}}@endif">
                </div>
                <div class="form-group col-md-2">
                    <label for="is_offday">Is Off Day</label>
                    <select name="week_days[Sunday][is_offday]" class="form-control" id="is_offday">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                @endif
            </div>   

            <input type="hidden" name="area_id" id="area_id" value="@if($data){{$data[0]['area_id']}}@endif">
            <input type="hidden" name="action" id="action" value="@if($data){{'update'}}@else{{'add'}}@endif">
            <button type="submit" class="btn btn-primary mr-2">Submit</button>
            <a class="btn btn-light" href="{{route('product/list')}}">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>

  </div>
</section>
<div class="modal fade" id="DefualtDeliveryModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel"> Add Defualt Times </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addUpdateTimes" method="post" enctype="multipart">
        {{ csrf_field() }}
        <div class="modal-body">

          <div class="form-group">
            <label for="category_name">Category Name</label>
            <input id="category_name" name="category_name" type="text" class="form-control form-control-danger">
          </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Save</button>
            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
@section('pageSpecificJs')

<script src="{{asset('assets/bundles/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.timepicker.min.js')}}"></script>
<script src="{{asset('assets/bundles/izitoast/js/iziToast.min.js')}}"></script>

<script>

  $(document).ready(function() {
    for (let i = 1; i <= 7; i++) {
      $('#start_time'+ i).timepicker({
          listWidth: 1,
          step: 60
      });
      $('#end_time'+ i).timepicker({
          listWidth: 1,
          step: 60
      });
        
      var minTime;
      $(document).on("change", "#start_time"+ i, function () {
        $('.error').text('');
          minTime = $(this).val();
          var startHour = parseInt(minTime.substring(0, 2));
          var startMinutes = minTime.substring(2, 8);
          minTime = (startHour + 1).toString().concat(startMinutes);
          $('#end_time'+ i).timepicker('option', { 'minTime': minTime, 'maxTime': '11:00pm' });
      });
      $(document).on("change", "#end_time"+ i, function () {
        $('.error').text('');
      });
    }
    
    $(document).on('click', '.auto_fill', function (e) {
      if($(this).attr('data-id') == 1){
        if($("#start_time1").val() == ""){
          $(this).parent().parent().find('.error').text('Please Select First Monday Start Time.');
        }else{
          $(this).parent().parent().find('.error').text('');
          for (let i = 2; i <= 7; i++) {
            $("#start_time"+i).val($("#start_time1").val());
          }
        }
      }
      if($(this).attr('data-id') == 2){
        if($("#end_time1").val() == ""){
          $(this).parent().parent().find('.error').text('Please Select First Monday End Time.');
        }else{
          $(this).parent().parent().find('.error').text('');
          for (let i = 2; i <= 7; i++) {
            $("#end_time"+i).val($("#end_time1").val());
          }
        }
      }
    });

    $("form").on('click', '.add_delivery', function (e) {
      var counter = $("#hidden_count").val();
      counter = parseInt(counter);
      var html = $(".areadiv:first").clone();
      $(html).find('input').val('');
      $(html).find('.area_name').attr('name','area_name['+counter+']');
      $(html).find('.add_delivery').removeClass('btn-info');
      $(html).find('.add_delivery').addClass('btn-danger');
      $(html).find('.add_delivery').html('<i class="fa fa-minus"></i>');
      $(html).find('.add_delivery').addClass('remove_delivery');
      $(html).find('.add_delivery').removeClass('add_delivery');
      $(html).insertAfter(".areadiv:last");
     
      $('input[name="area_name['+counter+']"]').rules("add", {
        required: true,
        messages: {
          required: "Please Select Product Unit",
        },
      });

      $("#hidden_count").val(counter+1);

    });

    $(document).on('click', '.remove_delivery', function (e) {
      $(this).parent().parent().parent().remove();
    });


    $("#addUpdateDelivery").validate({
      rules: {
        "area_name[0]": {
          required: true,
        },
      },
      messages: {
        "area_name[0]": {
          required: "Please Enter area",
        },
      },

    });

  $(document).on('submit', '#addUpdateDelivery', function (e) {
            e.preventDefault();
            var formdata = new FormData($("#addUpdateDelivery")[0]);
            $('.loader').show();
            $.ajax({
                url: '{{ route("addUpdateDelivery") }}',
                type: 'POST',
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    $('.loader').hide();
                    if (data.success == 1) {
                      window.location.href = '{{ route("delivery/list") }}';
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