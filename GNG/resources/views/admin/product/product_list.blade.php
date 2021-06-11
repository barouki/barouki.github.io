@extends('admin_layouts/main')
@section('pageSpecificCss')
<link href="{{asset('assets/bundles/datatables/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<style>


.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

</style>
@stop
@section('content')
<input type="hidden" class="category_id" value="@if($data){{$data['id']}} @endif">
<section class="section">
  <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
            @if($data && $data['id'])
            <h4>{{$data['category_name']}} Product List ({{$total_product}})</h4>
            @else
              <h4>Product List (<span class="total_product">{{$total_product}}</span>)</h4>
            @endif
            </div>
            <div class="card-body">
              <div class="pull-right">
                <div class="buttons">
                  <a href="{{route('product/add')}}" class="btn btn-primary">Add Product</a>
                </div>
              </div>

              <div class="tab" role="tabpanel">
              <ul class="nav nav-pills border-b mb-0 p-3">
								<li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#Section1" aria-controls="home" role="tab" data-toggle="tab">All Product <span class="badge badge-transparent total_product">{{$total_product}}</span></a></li>
								<li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section2" role="tab" data-toggle="tab">Out of Stock <span class="badge badge-transparent outofstock">{{$total_outofstock_product}}</span></a></li>
							</ul>
              <div class="tab-content tabs" id="home">
							
              <div role="tabpanel" class="tab-pane active" id="Section1">
                <div class="card-body">	
                  <div class="table-responsive">
                      <table class="table table-striped" id="product-listing">
                        <thead>
                          <tr>
                          <th> Image </th>
                          <th> Name </th>
                          <th> Unit-Price </th>
                          <th> In Stock </th>
                          <th> Category </th>
                          <th>Action</th>
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
                    <table class="table table-striped" id="outofstock-product-listing" width="100%">
                      <thead>
                        <tr>
                        <th> Image </th>
                        <th> Name </th>
                        <th> Unit-Price </th>
                        <th> In Stock </th>
                        <th> Category </th>
                        <th>Action</th>
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
  var dataTable = $('#product-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    'columnDefs': [ {
        'targets': [0,5], /* column index */
        'orderable': false, /* true or false */
     }],
    'ajax': {
        'url':'{{ route("showProductList") }}',
        'data': function(data){
            data.category_id = $('.category_id').val();
            data.flag = 1;
        }
    }
  });

  var dataTable = $('#outofstock-product-listing').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    'columnDefs': [ {
        'targets': [0,5], /* column index */
        'orderable': false, /* true or false */
     }],
    'ajax': {
        'url':'{{ route("showProductList") }}',
        'data': function(data){
            data.category_id = $('.category_id').val();
            data.flag = 0;
        }
    }
  });

  $(document).on('click', '#changeProductStock', function (e) {
      e.preventDefault();
      var product_id = $(this).attr('data-id');
      var status = $(this).attr('data-status');
      var category_id = $('.category_id').val();
      if(status == 1){
        status = 0;
      }else{
        status = 1;
      }
      var text = 'You will not be able to recover this data!';   
      var confirmButtonText = 'Yes, Change Stock Status!';
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
                url: '{{ route("changeProductStock") }}',
                type: 'POST',
                data: {"product_id":product_id,"status":status,"category_id":category_id},
                dataType: "json",
                cache: false,
                success: function (data) {
                    $('.loader').hide();
                    $('#product-listing').DataTable().ajax.reload(null, false);
                    $('#outofstock-product-listing').DataTable().ajax.reload(null, false);
                    $('.total_product').text(data.total_count);
                    $('.outofstock').text(data.count);
                    if(status == 0){
                      swal("Confirm!", "Your product has been out of stock!", "success");
                    }else{
                      swal("Confirm!", "Your product has been in stock!", "success");
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

  $(document).on('click', '#productDelete', function (e) {
      e.preventDefault();
      var product_id = $(this).attr('data-id');
      var category_id = $('.category_id').val();
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
                url: '{{ route("deleteProduct") }}',
                type: 'POST',
                data: {"product_id":product_id,"category_id":category_id},
                dataType: "json",
                cache: false,
                success: function (data) {
                    $('.loader').hide();
                    $('#product-listing').DataTable().ajax.reload(null, false);
                    $('#outofstock-product-listing').DataTable().ajax.reload(null, false);
                    $('.total_product').text(data.total_count);
                    $('.outofstock').text(data.count);
                    if (data.success == 1) {                      
                      swal("Confirm!", "Your product has been deleted!", "success");
                    } else {
                      swal("Confirm!", "Your product has not been deleted!", "error");
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
