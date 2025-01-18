<div class="d-flex align-items-stretch">
      <!-- Sidebar Navigation-->
      <nav id="sidebar">
        <!-- Sidebar Header-->
        <div class="sidebar-header d-flex align-items-center">
          <div class="avatar"><img src="{{asset('admincss/img/avatar-6.jpg')}}" alt="..." class="img-fluid rounded-circle"></div>
          <div class="title">
            @if (auth()->check())
                <h1 class="h5">{{ auth()->user()->name }}</h1>

                <p>
                    {{ auth()->user()->usertype === 'accMngr' ? 'Account Manager' : 'Supervisor' }}
                </p>
            @else
                <h1 class="h5">Guest</h1>
                <p>Please log in to see your details.</p>
            @endif

          </div>
        </div>
        <!-- Sidebar Navidation Menus--><span class="heading">Main</span>
        <ul class="list-unstyled">
                <li class="active"><a href="{{ url('/dashboard') }}"> <i class="icon-home"></i>Home </a></li>
                <li><a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Customers</a>
                  <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
                    <li><a href="{{ url('/customers') }}">Customers</a></li>
                  </ul>
                </li>
                <li><a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Category</a>
                  <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
                    <li><a href="{{ url('/categories') }}">Categories</a></li>
                    <li><a href="{{ url('/subCategories') }}">Sub Categories</a></li>
                  </ul>
                </li>
                <li><a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Customer Opportunity</a>
                  <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
                    <li><a href="#">View Orders</a></li>
                    <li><a href="#">Add Order</a></li>
                  </ul>
                </li>
                <li><a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Reports</a>
                  <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
                    <li><a href="#">View Orders</a></li>
                    <li><a href="#">Add Order</a></li>
                  </ul>
                </li>
        </ul>
      </nav>
      <!-- Sidebar Navigation end-->