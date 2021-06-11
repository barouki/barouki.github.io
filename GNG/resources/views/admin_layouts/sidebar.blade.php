<div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="{{ url('/dashboard') }}"> <img alt="image" src="{{asset('assets/dist/img/logo-impilo.png')}}" class="header-logo" /> 
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown {{ active_class(['dashboard']) }}">
              <a href="{{ url('/dashboard') }}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown {{ active_class(['user/list']) }}">
              <a href="{{ url('/user/list') }}" class="nav-link"><i data-feather="users"></i><span>User</span></a>
            </li>
            <li class="menu-header">Product</li>
            <li class="dropdown {{ active_class(['product/list']) }}">
              <a href="{{ url('/product/list') }}" class="nav-link"><i data-feather="package"></i><span>Product</span></a>
            </li>
            <li class="dropdown {{ active_class(['product/category']) }}">
              <a href="{{ url('/product/category') }}" class="nav-link"><i data-feather="box"></i><span>Category</span></a>
            </li>
            <li class="dropdown {{ active_class(['product/unit']) }}">
              <a href="{{ url('/product/unit') }}" class="nav-link"><i data-feather="list"></i><span>Unit</span></a>
            </li>
            <li class="dropdown {{ active_class(['coupon/list']) }}">
              <a href="{{ url('/coupon/list') }}" class="nav-link"><i data-feather="gift"></i><span>Coupon</span></a>
            </li>
            <li class="menu-header">Orders</li>
            <li class="dropdown {{ active_class(['order/list']) }}">
              <a href="{{ url('/order/list') }}" class="nav-link"><i data-feather="package"></i><span>Orders</span></a>
            </li>
            
            <li class="dropdown {{ active_class(['delivery/user/list']) }}">
              <a href="{{ url('/delivery/user/list') }}" class="nav-link"><i data-feather="users"></i><span>Delivery Boy</span></a>
            </li>
            
            <li class="dropdown {{ active_class(['order/complaint/list']) }}">
              <a href="{{ url('/order/complaint/list') }}" class="nav-link"><i data-feather="message-square"></i><span>Complaints</span></a>
            </li>

            <li class="dropdown {{ active_class(['order/reviewratings/list']) }}">
              <a href="{{ url('/order/reviewratings/list') }}" class="nav-link"><i data-feather="star"></i><span>Reviews & Rating</span></a>
            </li>

            <li class="dropdown {{ active_class(['delivery/list']) }}" style="display:none;">
              <a href="{{ url('/delivery/list') }}" class="nav-link"><i data-feather="truck"></i><span>Delivery Options</span></a>
            </li>

            
            <li class="menu-header">Settings</li>
            <li class="dropdown {{ active_class(['settings/banner/list']) }}">
              <a href="{{ url('/settings/banner/list') }}" class="nav-link"><i data-feather="file"></i><span>Banner</span></a>
            </li>
            <li class="dropdown {{ active_class(['settings/faq/list']) }}">
              <a href="{{ url('/settings/faq/list') }}" class="nav-link"><i data-feather="help-circle"></i><span>FAQ</span></a>
            </li>
            <li class="dropdown {{ active_class(['settings/address/list']) }}">
              <a href="{{ url('/settings/address/list') }}" class="nav-link"><i data-feather="map"></i><span>Address</span></a>
            </li>
            <li class="dropdown {{ active_class(['settings/notification/list']) }}">
              <a href="{{ url('/settings/notification/list') }}" class="nav-link"><i data-feather="bell"></i><span>Notification</span></a>
            </li>
            <li class="dropdown {{ active_class(['settings/others']) }}">
              <a href="{{ url('/settings/others') }}" class="nav-link"><i data-feather="settings"></i><span>Other Settings</span></a>
            </li>

          </ul>
        </aside>
      </div>