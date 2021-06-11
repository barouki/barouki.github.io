@extends('admin_layouts/main')
@section('pageSpecificCss')
<style>
.small-text{
    font-size: 15px;
}
.card-icon2 {
    width: 50px;
    height: 50px;
    line-height: 50px;
    font-size: 22px;
    margin: 25px 65px;
    box-shadow: 5px 3px 10px 0 rgba(21,15,15,0.3);
    border-radius: 10px;
    background: #6777ef;
    text-align: center;
}
.card-icon2 i{
    font-size: 22px;
    color: #fff;
}
</style>
@stop
@section('content')

<section class="section">
    <div class="row">
       <div class="col-md-12"> <h4>Completed Orders</h4> </div>
    </div>

    <div class="row ">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Today</h5>
                          <h2 class="mb-3 font-18">{{$todayOrder}}</h2>
                          <p class="mb-0"><span class="col-green">${{$totaltodayOrder}}</span></p>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="card-icon2 bg-cyan">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15"> This Month</h5>
                          <h2 class="mb-3 font-18">{{$thisMonthOrder}}</h2>
                          <p class="mb-0"><span class="col-orange">${{$totalthisMonthOrder}}</span> </p>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="card-icon2 bg-purple">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">This Year</h5>
                          <h2 class="mb-3 font-18">{{$thisYearOrder}}</h2>
                          <p class="mb-0"><span class="col-green">${{$totalthisYearOrder}}</span> </p>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="card-icon2 bg-orange">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">All Time</h5>
                          <h2 class="mb-3 font-18">{{$allOrder}}</h2>
                          <p class="mb-0"><span class="col-green">${{$totalallOrder}}</span> </p>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="card-icon2 bg-green">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>


    <div class="row">
       <div class="col-md-12"> <h4>Orders</h4> </div>
    </div>
    <div class="row ">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Processing</h5>
                          <h2 class="mb-3 font-18">{{ number_format($processingOrders) }}</h2>
                          <p class="mb-0"><span class="col-green">${{ number_format($totalprocessingOrders) }}</span> </p>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                      <div class="card-icon2 bg-info">
                            <i class="fas fa-chart-line"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15"> Confirmed</h5>
                          <h2 class="mb-3 font-18">{{ number_format($confirmedOrders) }}</h2>
                          <p class="mb-0"><span class="col-orange">${{ number_format($totalconfirmedOrders) }}</span> </p>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                      <div class="card-icon2 bg-primary">
                            <i class="fas fa-check"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">On Hold</h5>
                          <h2 class="mb-3 font-18">{{ number_format($onholdOrders) }}</h2>
                          <p class="mb-0"><span class="col-dark">${{ number_format($totalonholdOrders) }}</span>
                            </p>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                      <div class="card-icon2 bg-cyan">
                            <i class="fas fa-pause"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Cancelled</h5>
                          <h2 class="mb-3 font-18">{{ number_format($cancelledOrders) }}</h2>
                          <p class="mb-0"><span class="col-green">${{ number_format($totalcancelledOrders) }}</span> </p>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                      <div class="card-icon2 bg-danger">
                            <i class="fas fa-times"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    <div class="row">
       <div class="col-md-12"> <h4>Products</h4> </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-icon shadow-primary bg-cyan">
            <i class="fas fa-boxes"></i>
            </div>
            <div class="card-wrap">
            <div class="card-header">
                <h4 class="pull-right">Categories</h4>
            </div>
            <div class="card-body pull-right">
            {{ number_format($totalCategory) }}
            </div>
            </div>
            <div class="card-chart">
            <canvas id="chart-1" height="80"></canvas>
            </div>
        </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-icon shadow-primary bg-purple">
            <i class="fas fa-box"></i>
            </div>
            <div class="card-wrap">
            <div class="card-header">
                <h4 class="pull-right">Products</h4>
            </div>
            <div class="card-body pull-right">
            {{ number_format($totalProduct) }}
            </div>
            </div>
            <div class="card-chart">
            <canvas id="chart-2" height="80"></canvas>
            </div>
        </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-icon shadow-primary bg-green">
            <i class="fas fa-box-open"></i>
            </div>
            <div class="card-wrap">
            <div class="card-header">
                <h4 class="pull-right">Out of Stock Products</h4>
            </div>
            <div class="card-body pull-right">
            {{ number_format($outofstockProduct) }}
            </div>
            </div>
            <div class="card-chart">
            <canvas id="chart-3" height="80"></canvas>
            </div>
        </div>
        </div>
    </div>


    <div class="row">
       <div class="col-md-12"> <h4>Miscellaneous</h4> </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-icon shadow-primary bg-purple">
            <i class="fas fa-users"></i>
            </div>
            <div class="card-wrap">
            <div class="card-header">
                <h4 class="pull-right">Delivery Boys</h4>
            </div>
            <div class="card-body pull-right">
              {{ number_format($deliveryBoy) }}
            </div>
            </div>
            <div class="card-chart">
            <canvas id="chart-4" height="80"></canvas>
            </div>
        </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-icon shadow-primary bg-orange">
            <i class="fas fa-rupee-sign"></i>
            </div>
            <div class="card-wrap">
            <div class="card-header">
                <h4 class="pull-right">Total Delivery Boy Payment</h4>
            </div>
            <div class="card-body pull-right">
                ${{ number_format($deliveryBoyPayData) }}
            </div>
            </div>
            <div class="card-chart">
            <canvas id="chart-5" height="80"></canvas>
            </div>
        </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-icon shadow-primary bg-danger">
            <i class="far fa-comment-alt"></i>
            </div>
            <div class="card-wrap">
            <div class="card-header">
                <h4 class="pull-right">Open Complaints</h4>
            </div>
            <div class="card-body pull-right">
              {{ number_format($openComplaint) }}
            </div>
            </div>
            <div class="card-chart">
            <canvas id="chart-6" height="80"></canvas>
            </div>
        </div>
        </div>
    </div>

</section>

@endsection
@section('pageSpecificJs')
<script src="{{asset('assets/bundles/chartjs/chart.min.js')}}"></script>
<script src="{{asset('assets/dist/js/custom.js')}}"></script>
@stop