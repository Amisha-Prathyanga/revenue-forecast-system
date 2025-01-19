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
                <li><a href="#customersDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Customers</a>
                  <ul id="customersDropdown" class="collapse list-unstyled ">
                    <li><a href="{{ url('/customers') }}">Customers</a></li>
                  </ul>
                </li>
                <li><a href="#categoryDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Category</a>
                  <ul id="categoryDropdown" class="collapse list-unstyled ">
                    <li><a href="{{ url('/categories') }}">Categories</a></li>
                    <li><a href="{{ url('/subCategories') }}">Sub Categories</a></li>
                  </ul>
                </li>
                <li><a href="#customerOpportunityDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Customer Opportunity</a>
                  <ul id="customerOpportunityDropdown" class="collapse list-unstyled ">
                    <li><a href="{{ url('/cusOpportunities') }}">Customer Opportunities</a></li>
                  </ul>
                </li>
                <li><a href="#reportsDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Reports</a>
                  <ul id="reportsDropdown" class="collapse list-unstyled ">
                    <li><a href="{{ url('/sales-projection') }}">Sales Projection Report</a></li>
                  </ul>
                </li>
        </ul>
      </nav>
      <!-- Sidebar Navigation end-->
  
    