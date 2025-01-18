@extends('admin.admin_layout')

@section('content')
<nav aria-label="breadcrumb" class="mx-4 my-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
        {{-- <li class="breadcrumb-item"><i class="fas fa-angle-right"></i></li> --}}
        <li class="breadcrumb-item active" aria-current="page"><strong>Customers</strong></li>
    </ol>
</nav>

<div class="tab-content" id="nav-tabContent">
    <!-- Customer Registration Start -->
    <div class="container-fluid px-4">
        <div class="rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="text-center mb-3">New Customer</h3>
            </div>

            <div class="row mt-3">

                <div class="col-lg-9 col-md-9 col-sm-12">

                    <!-- form start -->
                    <form method="POST" id="customer_form" action="{{ route('data.customer_form') }}">

                        @csrf

                        <div class="form-group row mb-3 mx-3">
                            <label for="empNo" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Customer Name</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <input type="text" class="form-control" id="cusName" name="cusName" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3 mx-3">
                            <label for="forFirstName" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Industry Sector</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <input type="text" class="form-control" id="cusIndustry" name="cusIndustry">
                            </div>
                        </div>
                        <div class="form-group row mb-3 mx-3">
                            <label for="forContactNumber" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Controlling Ministry</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <input type="text" class="form-control" id="cusMinistry" name="cusMinistry">
                            </div>
                        </div>
                        <div class="form-group row mb-3 mx-3">
                            <label for="forJobRole" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Ministry Contact</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" id="cusMinContact" name="cusMinContact" >
                            </div>
                        </div>
                        <div class="form-group row mb-3 mx-3">
                            <label for="forJobRole" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Key client Contact</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" id="cusContact" name="cusContact" >
                            </div>
                        </div>
                        <div class="form-group row mb-3 mx-3">
                            <label for="forJobRole" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Key client Designation</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" id="cusDesignation" name="cusDesignation" >
                            </div>
                        </div>
                        <div class="form-group row mb-3 mx-3">
                            <label for="forJobRole" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Key Projects</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" id="cusProjects" name="cusProjects" >
                            </div>
                        </div>
                        <div class="form-group row mb-3 mx-3">
                            <label for="forJobRole" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Acount Servicing Personal Initials</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" id="cusAccPersIni" name="cusAccPersIni" >
                            </div>
                        </div>
                        <div class="form-group row my-5 mx-2">
                            <div class="col-lg-12 item-flex-end">
                                <button type="submit" name="submit_customer" class="btn btn-primary btn-custom mb-3" id="customerSubmit">Submit</button>
                            </div>
                        </div>

                    </form>
                    <!-- form end -->

                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                </div>

            </div>
        </div>
    </div>



</div>

@endsection