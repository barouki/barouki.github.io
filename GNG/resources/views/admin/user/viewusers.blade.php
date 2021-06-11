@extends('admin_layouts/main')
@section('pageSpecificCss')
<style type="text/css">
  .ul-font>li {
    font-weight: 400;
    font-size: 15px;
  }

  .form-group {
    margin-bottom: 15px !important;
  }
</style>
@stop
@section('content')

<div class="row bg-title">
  <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
    <?php if ($data->user_type == 1) {
      $userrole = 'Buyer';
    } else {
      $userrole = 'Seller';
    }
    ?>

    <h4 class="page-title"><b>{{$userrole}}</b> Detail </h4>
  </div>
  <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
    <ol class="breadcrumb">
      <li class="active"><a href="{{ route('users/list') }}">List</a></li>
    </ol>
  </div>
</div>


<div class="col-sm-12">
  <div class="white-box">
    <div class="row">

      <div class="col-md-6 message-center">
        <div class="mail-contnet">
          <h5>{{$data->fullname }}</h5>
        </div>
        <div class="Contact">
          <h5><a class="contact-content" href="tel:{{$data->mobile_no }}">CALL: {{$data->mobile_no }}</a></h5>
        </div>
      </div>

      <div class="col-md-6 message-center">
        <div class="user-img">
          <img id="myImg" src="@if(!empty($data->profile_img) && (file_exists(env('API_PATH') . 'uploads/users/' . $data->user_id . '/' . $data->profile_img)) ) {{env('API_URL').'/uploads/users/'.$data->user_id.'/'.$data->profile_img }} @else https://ui-avatars.com/api/?name={{$data->fullname}}&rounded=true&background=0D8ABC&color=fff @endif" class="img-circle vc-img">
        </div>
      </div>

    </div>
  </div>
</div>

<ul class="nav customtab2 nav-tabs profiletab" role="tablist">
  <li role="presentation" class="nav-item"><a href="#vehicle" class="nav-link active" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Vehicle Detail</span></a></li>
  @if($data->user_type == 1)
  <li role="presentation" class="nav-item"><a href="#tradein" class="nav-link" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> Trade In Detail</span></a></li>
  @endif

  @if($data->user_type == 2)
  <li role="presentation" class="nav-item"><a href="#dealership" class="nav-link" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> DealerShip Agent Detail</span></a></li>
  <li role="presentation" class="nav-item"><a href="#privatesale" class="nav-link" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> Private Sale Detail</span></a></li>
  <li role="presentation" class="nav-item"><a href="#sellervehicle" class="nav-link" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> Seller Vehicle Detail</span></a></li>
  @endif

</ul>


<div class="tab-content">

  <div role="tabpanel" class="tab-pane fade active in" id="vehicle">


    <div class="row">
      @foreach($vehicledetail as $val)
      <?php
      if ($val->v_fuel == 1) {
        $fuel = "Petrol";
      } else if ($val->v_fuel == 2) {
        $fuel = "Diesel";
      } else {
        $fuel = "Any";
      }
      if ($val->v_engine == 1) {
        $engine = "Automatic";
      } else if ($val->v_engine == 2) {
        $engine = "Manual";
      } else {
        $engine = "Automatic/Manual";
      }
      ?>
      <div class="col-lg-4" id="{{$val->id}}">
        <div class="box">
          <!-- @if($data->user_type == 1)
            <div class="box-tool">
              <a class="nav-link text-muted active viewtradein" data-id="{{$val->id}}" data-userid="{{$val->user_id}}" data-toggle="modal" data-target="#ViewTradeIn" aria-hidden="true">
                    Trade In <i class="ti-reload"></i>
              </a>
            </div>
          @endif -->
          <div class="media p-0 m-0" style="border:none;">
            <img src="{{asset('/images/type-icon/thumbnail/'.$val->icon)}}" class="carimg" alt="image">
            <div class="media-body">
              <div class="emloyee-content">
                <h2 class="m-0"> {{ $val->body_type }} </h2>
                <p>({{$fuel}} - {{ $engine }})</p>
                <p><span class=""> Budget: </span>{{ $val->v_budget }}</p>
                <p><span class=""> Milage: </span>{{ $val->v_mileage }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      @endforeach
    </div>

  </div>
  @if($data->user_type == 1)
  <div role="tabpanel" class="tab-pane fade" id="tradein">

    <div class="row">

      @foreach($data_new['response_data'] as $val)

      <div class="col-lg-6" id="{{$val['id']}}">
        <div class="box">
          <div class="media p-0 m-0" style="border:none;">
            <img src="{{ $val['vehicle_icon'] }}" class="carimg" alt="image">
            <div class="media-body">
              <div class="emloyee-content">
                <h2 class="m-0"> {{ $val['body_type_id'] }} </h2>
                <p>({{$val['fuel']}} - {{ $val['engine'] }})</p>
                <p><span class=""> Settlement: </span>{{ $val['settlement'] }}</p>
                <p><span class=""> Milage: </span>{{ $val['mileage'] }}</p>
              </div>
            </div>
            <div class="media-body">
              <img src="{{ $val['cover_photo'] }}" class="carimg vc-img cr-img" id="c_img" alt="image">
            </div>
          </div>
          <div class="media p-0 m-0" style="border:none;">
            <div class="media-body">
              <div class="emloyee-content">
                <h2 class="m-0"> {{ 'Vehicle Video And Photos' }} </h2>
                <a target="_blank" rel="noopener noreferrer" href="{{ $val['video'] }}">
                  <img src="{{ env('API_URL').'/video-player.png' }}" class="carimg vc-car" alt="image">
                </a>
                <?php $i = 1; ?>
                @foreach($val['car_photo'] as $car_photo )
                <img src="{{ $car_photo }}" class="carimg vc-car vc-img" id="{{ 'vs_'.$i}}" alt="image">
                <?php $i++; ?>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

  </div>
  @endif

  @if($data->user_type == 2)
  <div role="tabpanel" class="tab-pane fade" id="dealership">
    <div class="white-box">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="dealer_fullname">Full Name</label>
            <input type="text" class="form-control" id="dealer_fullname" value="@if (!empty($dealershipdetail)){{$dealershipdetail->dealer_fullname}} @endif" readonly>
          </div>
          <div class="form-group">
            <label for="dealer_address">Address</label>
            <textarea class="form-control" id="dealer_address" readonly>@if(!empty($dealershipdetail)){{$dealershipdetail->dealer_address}} @endif</textarea>
          </div>
          <div class="form-group">
            <label for="dealer_email">Email</label>
            <input type="text" class="form-control" id="dealer_email" value="@if (!empty($dealershipdetail)){{$dealershipdetail->dealer_email}} @endif" readonly>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="dealer_province">Province</label>
            <input type="text" class="form-control" id="dealer_province" value="@if (!empty($dealershipdetail)){{$dealershipdetail->dealer_province}} @endif" readonly>
          </div>
          <div class="form-group">
            <label for="dealer_companyregno">Company Reg. No</label>
            <input type="text" class="form-control" id="dealer_companyregno" value="@if (!empty($dealershipdetail)){{$dealershipdetail->company_reg_no}} @endif" readonly>
          </div>
          @if (!empty($dealershipdetail))
          <div class="form-group">
            <img src="{{ env('API_URL').'/uploads/users/'.$data->user_id.'/'.$dealershipdetail->document_img }}" class="previewimg" width="96" height="96">
          </div>
          @endif
        </div>

      </div>
    </div>
  </div>
  @endif


  @if($data->user_type == 2)
  <div role="tabpanel" class="tab-pane fade" id="privatesale">
    <div class="white-box">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="idnumber">Id Number</label>
            <input type="text" class="form-control" id="idnumber" value="@if (!empty($privatedetail)){{$privatedetail->id_number}} @endif" readonly>
          </div>
          <div class="form-group">
            <label for="seller_address">Address</label>
            <textarea class="form-control" id="seller_address" readonly>@if(!empty($privatedetail)){{$privatedetail->seller_address}} @endif</textarea>
          </div>
          <div class="form-group">
            <label for="seller_email">Email</label>
            <input type="text" class="form-control" id="seller_email" value="@if (!empty($privatedetail)){{$privatedetail->seller_email}} @endif" readonly>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="seller_province">Province</label>
            <input type="text" class="form-control" id="seller_province" value="@if (!empty($privatedetail)){{$privatedetail->seller_province}} @endif" readonly>
          </div>

          @if (!empty($privatedetail))
          <div class="form-group">
            <img src="{{ env('API_URL').'/uploads/users/'.$data->user_id.'/'.$privatedetail->document_img }}" class="previewimg" width="96" height="96">
          </div>
          @endif
        </div>

      </div>
    </div>
  </div>
  @endif

  @if($data->user_type == 2)
  <div role="tabpanel" class="tab-pane fade" id="sellervehicle">
    <div class="row">
      @foreach($vehicle_data as $val)

      <div class="col-lg-6" id="{{$val['folder_id']}}">
        <div class="box">
          <div class="media p-0 m-0" style="border:none;">
            <div class="media-body">
              <div class="emloyee-content">
                <h2 class="m-0"> {{ $val['folder_name'] }} </h2>
                <p><span class=""> Summary: </span>{{ $val['summary'] }}</p>
                <p><span class=""> Price: </span>{{ $val['price'] }}</p>
              </div>
            </div>
            <div class="media-body">
              <img src="{{ $val['cover_photo'] }}" class="carimg vc-img cr-img" id="c_img" alt="image">
            </div>
          </div>
          <div class="media p-0 m-0" style="border:none;">
            <div class="media-body">
              <div class="emloyee-content">
                <h2 class="m-0"> {{ 'Vehicle Video And Photos' }} </h2>
                <a target="_blank" rel="noopener noreferrer" href="{{ $val['video'] }}">
                  <img src="{{ env('API_URL').'/video-player.png' }}" class="carimg vc-car" alt="image">
                </a>
                <?php $i = 1; ?>
                @if(!empty($val['photos']))
                @foreach($val['photos'] as $car_photo )
                <img src="{{ $car_photo }}" class="carimg vc-car vc-img" id="{{ 'vs_'.$i}}" alt="image">
                <?php $i++; ?>
                @endforeach
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

</div>


@stop
<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>
@section('pageSpecificJs')
<script type="text/javascript">
  $(document).ready(function() {
    var modal = document.getElementById("myModal");
    var img = document.getElementById("myImg");
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    $(".vc-img").click(function() {

      var img = document.getElementById($(this).attr('id'));


      img.onclick = function() {
        modal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
      }
      var span = document.getElementsByClassName("close")[0];
      span.onclick = function() {
        modal.style.display = "none";
      }
    });

  });
</script>



@stop