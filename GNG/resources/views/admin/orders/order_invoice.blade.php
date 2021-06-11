@extends('admin_layouts/main')
@section('pageSpecificCss')
<link href="{{asset('assets/bundles/datatables/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@stop
@section('content')
<section class="section">
    <div class="section-body">
        <div class="invoice">
            <div class="invoice-print">
            <div class="row">
                <div class="col-lg-12">
                <div class="invoice-title">
                    <h2>View Order</h2>
                    <div class="invoice-number">Order {{$order_data['order_id']}}</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                    <address>
                        <strong>Assign To:</strong><br>
                        {{$order_data['fullname']}}<br>
                        {{$order_data['dmobile_no']}}
                    </address>
                    </div>
                    <div class="col-md-6 text-md-right">
                    <address>
                        <strong>Shipped To:</strong><br>
                        {{$order_data['first_name']}} {{$order_data['last_name']}}<br>
                        {{$order_data['home_no']}}, {{$order_data['society']}}<br>
                        {{$order_data['landmark']}},  {{$order_data['street']}}<br>
                        {{$order_data['area']}},  {{$order_data['city']}}<br>
                        {{$order_data['pincode']}}
                    </address>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                    <address>
                        <strong>Payment Method:</strong><br>
                        @if($order_data['payment_type'] == 1){{"Cash on Delivery"}}@else{{"Card Payment"}}@endif<br>
                        {{$order_data['card_holder_email']}}
                    </address>
                    </div>
                    <div class="col-md-6 text-md-right">
                    <address>
                        <strong>Order Date:</strong><br>
                        {{date('M d, Y',strtotime($order_data['ordered_at']))}}<br><br>
                    </address>
                    </div>
                </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                <div class="section-title">Order Summary</div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-md">
                    <tr>
                        <th data-width="40">#</th>
                        <th>Item</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Totals</th>
                    </tr>
                    <?php $i = 1; $total_amount=0;?>
                    @foreach($item_data as $value)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$value['name']}}</td>
                        <td class="text-center">{{$value['unit']}} {{$value['unit_name']}}</td>
                        <td class="text-center">${{$value['price']}}</td>
                        <td class="text-center">{{$value['quantity']}}</td>
                        <td class="text-right">${{$value['price']*$value['quantity']}}</td>
                    </tr>
                    <?php $total_amount+=($value['price']*$value['quantity']); $i++; ?>
                    @endforeach
                    </table>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-8">
                    
                    </div>
                    <div class="col-lg-4 text-right">
                    <div class="invoice-detail-item">
                        <div class="invoice-detail-name">Subtotal</div>
                        <div class="invoice-detail-value">${{$total_amount}}</div>
                    </div>
                    <div class="invoice-detail-item">
                        <div class="invoice-detail-name">Shipping</div>
                        <div class="invoice-detail-value">${{$order_data['shipping_charge']}}</div>
                    </div>
                    <div class="invoice-detail-item">
                        <div class="invoice-detail-name">Coupon Discount</div>
                        <div class="invoice-detail-value">${{$order_data['coupon_discount']}}</div>
                    </div>
                    <hr class="mt-2 mb-2">
                    <div class="invoice-detail-item">
                        <div class="invoice-detail-name">Total</div>
                        <div class="invoice-detail-value invoice-detail-value-lg">${{ ($total_amount+$order_data['shipping_charge']) - $order_data['coupon_discount'] }}</div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <hr>

            <div class="text-md-right pb-5">
            <div class="float-lg-left mb-lg-0 mb-3">
                @if($order_data['status'] == 1 || $order_data['status'] == 4)
                <button data-toggle="modal" data-target="#assignUserModal" data-id="{{$order_data['order_id']}}" data-delivery_boy_user_id="{{$order_data['delivery_boy_user_id']}}" class="btn btn-primary btn-icon icon-left assignUser"><i class="fas fa-truck"></i> Start Delivery </button>
                @endif
                <a href="{{route('order/list')}}" class="btn btn-danger btn-icon icon-left text-light"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="assignUserModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel"> Assign User </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="assignDeliveryBoy" method="post" enctype="multipart">
        {{ csrf_field() }}
        <div class="modal-body">

          <div class="form-group">
            <label for="delivery_boy_user_id">DeliveryBoy</label>
            <select id="delivery_boy_user_id" name="delivery_boy_user_id" class="form-control form-control-danger">
                <option value="">Select</option>
            </select>
          </div>
          </div>
          <div class="modal-footer">
              <input type="hidden" name="order_id" id="order_id" value="">
            <button type="submit" class="btn btn-success">Confirm</button>
            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
</div>

   
@endsection

@section('pageSpecificJs')

<script src="{{asset('assets/bundles/jquery-ui/jquery-ui.min.js')}}"></script>

<script>
$(document).ready(function (){

    $('#assignUserModal').on('hidden.bs.modal', function(e) {
        $("#assignDeliveryBoy")[0].reset();
        $('.modal-title').text('Assign User');
        $('#delivery_boy_user_id').val("");
        $('#order_id').val("");
        var validator = $("#assignDeliveryBoy").validate();
        validator.resetForm();
    });
  
    $(document).on("click", ".assignUser", function() {
        $('.loader').show();
        $('.modal-title').text('Assign User');
        var order_id = $(this).attr('data-id')
        var user_id = $(this).attr('data-delivery_boy_user_id');
        $.ajax({
          url: '{{ route("getDeliveryBoyList") }}',
          type: 'POST',
          data: {},
          dataType: "json",
          cache: false,
          success: function (data) {
              $('.loader').hide();
              var html = '<option value="">Select</option>';
              if (data.success == 1) {
                $('#order_id').val(order_id);
                $(data.data).each(function( index,value ) {
                  if(user_id == value.user_id){
                    var selected = 'selected';
                  }else{
                    var selected = '';
                  }
                  html += '<option value="'+value.user_id+'" '+selected+'>'+value.fullname+'</option>';
                });
              } 
              $('#delivery_boy_user_id').html(html);
          },
          error: function (jqXHR, textStatus, errorThrown) {
              alert(errorThrown);
          }
      });
    });


    $("#assignDeliveryBoy").validate({
        rules: {
            delivery_boy_user_id: {
                required: true,
            },
        },
        messages: {
            delivery_boy_user_id: {
                required: "Please Select User",
            },
        }
    });

    $(document).on('submit', '#assignDeliveryBoy', function (e) {
      e.preventDefault();
      
      var formdata = new FormData($("#assignDeliveryBoy")[0]);
      $('.loader').show();

      $.ajax({
          url: '{{ route("assignDeliveryBoy") }}',
          type: 'POST',
          data: formdata,
          dataType: "json",
          contentType: false,
          cache: false,
          processData: false,
          success: function (data) {
              $('.loader').hide();
              if (data.success == 1) {
                $('#assignUserModal').modal('hide');
                iziToast.success({
                    title: 'Success!',
                    message: data.message,
                    position: 'topRight'
                });
                window.locatio.href = '{{ route("order/list") }}'
              
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
