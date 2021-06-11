<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>GNG</title>

  @yield('pageSpecificCss')

  <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
  <link href="{{asset('assets/css/components.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet">
  <link rel='shortcut icon' type='image/x-icon' href="{{asset('assets/img/favicon.ico')}}" />
  <link rel="stylesheet" href="{{asset('assets/bundles/sweetalert/css/sweetalert.css')}}">
  
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar sticky">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                <i data-feather="maximize"></i>
              </a>
            </li>
          </ul>
        </div>
        <?php
        $data = \App\Admin::first();
        ?>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown"
              class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image" src="{{env('DEFAULT_IMAGE_URL').$data['profile_image']}}"
                class="user-img-radious-style author-box-profile"> <span class="d-sm-none d-lg-inline-block"></span></a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
              <div class="dropdown-title">Hello {{$data['username']}}</div>
                <a href="{{route('my-profile')}}" class="dropdown-item has-icon"> <i class="far
                      fa-user"></i> Profile
                </a>
                
              <div class="dropdown-divider"></div>
              <a href="{{ route('logout',['flag' => 0]) }}" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                Logout
              </a>
            </div>
          </li>
        </ul>
      </nav>
      
  @include('admin_layouts.sidebar')
    
  <div class="main-content">
      @yield('content')
  </div>

  <footer class="main-footer">
    <div class="footer-left">
      <a href="#">2020 &copy; GNG </a></a>
    </div>
    <div class="footer-right">
    </div>
  </footer>
</div>
</div>

<script src="{{asset('assets/js/app.min.js')}}"></script>
<script src="{{asset('assets/dist/js/jquery.validate.js')}}"></script>
<script src="{{asset('assets/bundles/sweetalert/js/sweetalert.js')}}"></script>
<script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @yield('pageSpecificJs')

    <script src="{{asset('assets/js/scripts.js')}}"></script>
<script src="{{asset('assets/js/custom.js')}}"></script>
</body>
</html>