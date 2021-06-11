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
              <h4>Address List  (<span class="total_address">{{($total_city+$total_area)}}</span>)</h4>
            </div>

            <div class="card-body">

              <div class="pull-right">
                  <div class="buttons"> 
                    <button class="btn btn-info text-light" data-toggle="modal" data-target="#areaModal" data-whatever="@mdo">Add Area</button>
                  </div>
                </div>

                <div class="pull-right">
                  <div class="buttons"> 
                    <button class="btn btn-primary text-light" data-toggle="modal" data-target="#cityModal" data-whatever="@mdo">Add City</button>
                  </div>
                </div>

                <ul class="nav nav-pills" id="myTab3" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="city-tab3" data-toggle="tab" href="#city3" role="tab"
                      aria-controls="city" aria-selected="true">City <span class="badge badge-transparent total_city">{{$total_city}}</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="area-tab3" data-toggle="tab" href="#area3" role="tab"
                      aria-controls="area" aria-selected="false">Area <span class="badge badge-transparent total_area">{{$total_area}}</span></a>
                  </li>
                </ul>
                <div class="tab-content" id="myTabContent2">
                  <div class="tab-pane fade show active" id="city3" role="tabpanel" aria-labelledby="city-tab3">
                    <div class="table-responsive">
                      <table class="table table-striped" id="city-listing">
                        <thead>
                          <tr>
                            <th>City</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="area3" role="tabpanel" aria-labelledby="area-tab3">
                    <div class="table-responsive">
                        <table class="table table-striped" id="area-listing" style="width:100%">
                          <thead>
                            <tr>
                              <th>City</th>
                              <th>Area</th>
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
          </div>
        </div>
    </section>

<div class="modal fade" id="cityModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel"> Add City </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addUpdateCity" method="post" enctype="multipart">
        {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <label for="city_name">City Name</label>
              <input id="city_name" name="city_name" type="text" class="form-control form-control-danger">
            </div>
          </div>
          <div class="modal-footer">
              <input type="hidden" name="city_id" id="city_id" value="">
            <button type="submit" class="btn btn-success">Save</button>
            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="areaModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel"> Add Area </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addUpdateArea" method="post" enctype="multipart">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <label for="city_idd">City</label>
              <select id="city_idd" name="city_idd" class="form-control form-control-danger">
                <option>Select</option>
                @foreach($data as $val)
                  <option value="{{$val->id}}">{{$val->city_name}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="area_name">Area Name</label>
              <input id="area_name" name="area_name" type="text" class="form-control form-control-danger">
            </div>

          </div>
          <div class="modal-footer">
              <input type="hidden" name="area_id" id="area_id" value="">
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

  var dataTable = $('#city-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
     'columnDefs': [ {
          'targets': [1], /* column index */
          'orderable': false, /* true or false */
        }],
    'ajax': {
        'url':'{{ route("showCityList") }}',
        'data': function(data){
            // Read values
            // var user_id = $('#user_id').val();

            // Append to data
            // data.user_id = user_id;
        }
    }
  });

  var dataTable1 = $('#area-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
     'columnDefs': [ {
          'targets': [2], /* column index */
          'orderable': false, /* true or false */
        }],
    'ajax': {
        'url':'{{ route("showAreaList") }}',
        'data': function(data){
            // Read values
            // var user_id = $('#user_id').val();

            // Append to data
            // data.user_id = user_id;
        }
    }
  });
  
  $('#cityModal').on('hidden.bs.modal', function(e) {
      $("#addUpdateCity")[0].reset();
      $('.modal-title').text('Add Area');
      $('#city_id').val("");
      var validator = $("#addUpdateCity").validate();
      validator.resetForm();
  });

  $("#city-listing").on("click", ".UpdateCity", function() {
      $('.loader').show();
      $('.modal-title').text('Edit City');
      $('#city_id').val($(this).attr('data-id'));
      $('#city_name').val($(this).attr('data-name'));
      $('.loader').hide();
  });

  $("#addUpdateCity").validate({
      rules: {
        city_name:{
            required: true,
              remote: {
                  url: '{{ route("CheckExistCity") }}',
                  type: "post",
                  data: {
                      city_name: function () { return $("#city_name").val(); },
                      city_id: function () { return $("#city_id").val(); },
                  }
              }
          } 
      },
      messages: {
        city_name: {
              required: "Please Enter City",
              remote: "City Name Already Exist.",
          }
      }
  });

  $(document).on('submit', '#addUpdateCity', function (e) {
    e.preventDefault();
    
    var formdata = new FormData($("#addUpdateCity")[0]);
    $('.loader').show();
    $.ajax({
        url: '{{ route("addUpdateCity") }}',
        type: 'POST',
        data: formdata,
        dataType: "json",
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            $('.loader').hide();
            $('#cityModal').modal('hide');
            if (data.success == 1) {

              $('#city-listing').DataTable().ajax.reload(null, false);
              $('.total_city').text(data.total_city);
              $('.total_area').text(data.total_area);
              $('.total_address').text((data.total_city+data.total_area));
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

  $(document).on('click', '#DeleteCity', function (e) {
    e.preventDefault();
    var city_id = $(this).attr('data-id');
    var text = 'You will not be able to recover City and Area data!';   
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
                url: '{{ route("deleteCity") }}',
                type: 'POST',
                data: {"city_id":city_id},
                dataType: "json",
                cache: false,
                success: function (data) {
                    $('.loader').hide();
                    $('#city-listing').DataTable().ajax.reload(null, false);
                    $('#area-listing').DataTable().ajax.reload(null, false);
                    $('.total_city').text(data.total_city);
                    $('.total_area').text(data.total_area);
                    $('.total_address').text((data.total_city+data.total_area));
                    if (data.success == 1) {
                      swal("Confirm!", "City and Area has been deleted!", "success");
                    } else {
                      swal("Confirm!", "City and Area has not been deleted!", "error");
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

  $('#areaModal').on('hidden.bs.modal', function(e) {
      $("#addUpdateArea")[0].reset();
      $('.modal-title').text('Add Area');
      $('#area_id').val("");
      var validator = $("#addUpdateArea").validate();
      validator.resetForm();
  });

  $("#area-listing").on("click", ".UpdateArea", function() {
      $('.loader').show();
      $('.modal-title').text('Edit Area');
      $('#area_id').val($(this).attr('data-id'));
      $("#city_idd").val($(this).attr('data-city_id'));
      $('#area_name').val($(this).attr('data-name'));
      $('.loader').hide();
  });

  $("#addUpdateArea").validate({
      rules: {
        area_name:{
            required: true,
              remote: {
                  url: '{{ route("CheckExistArea") }}',
                  type: "post",
                  data: {
                      area_name: function () { return $("#area_name").val(); },
                      area_id: function () { return $("#area_id").val(); },
                      city_id: function () { return $("#city_idd").val(); },
                  }
              }
          } 
      },
      messages: {
        area_name: {
              required: "Please Enter area",
              remote: "area Name Already Exist.",
          }
      }
  });

  $(document).on('submit', '#addUpdateArea', function (e) {
    e.preventDefault();
    
    var formdata = new FormData($("#addUpdateArea")[0]);
    $('.loader').show();
    $.ajax({
        url: '{{ route("addUpdateArea") }}',
        type: 'POST',
        data: formdata,
        dataType: "json",
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            $('.loader').hide();
            $('#areaModal').modal('hide');
            if (data.success == 1) {

              $('#area-listing').DataTable().ajax.reload(null, false);
              $('.total_city').text(data.total_city);
              $('.total_area').text(data.total_area);
              $('.total_address').text((data.total_city+data.total_area));

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

  $(document).on('click', '#DeleteArea', function (e) {
    e.preventDefault();
    var area_id = $(this).attr('data-id');
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
                url: '{{ route("deleteArea") }}',
                type: 'POST',
                data: {"area_id":area_id},
                dataType: "json",
                cache: false,
                success: function (data) {
                    $('.loader').hide();
                    $('#area-listing').DataTable().ajax.reload(null, false);
                    $('.total_city').text(data.total_city);
                    $('.total_area').text(data.total_area);
                    $('.total_address').text((data.total_city+data.total_area));
                    if (data.success == 1) {
                      swal("Confirm!", "Area has been deleted!", "success");
                    } else {
                      swal("Confirm!", "Area has not been deleted!", "error");
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

