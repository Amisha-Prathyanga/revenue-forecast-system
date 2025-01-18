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

            <!-- Table Section -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <h5 class="mt-2">Customer Details</h5>
                </div>

                 <div class="col-lg-9 col-md-6 col-sm-12">
                     <form action="employeeSearch" method="get" role="search">
                @csrf
            </form>
                </div>
            </div>

        @if ($errors->any())
        <div class="alert alert-danger mt-4 mb-5">
            @foreach($errors->all() as $error)
            <li class="text-red-500 list-name">
                {{ $error }}
            </li>
            @endforeach
        </div>

        @else

        <div class="table-responsive mt-4 mb-5">

            <table class="table align-middle table-striped table-bordered table-hover mb-0" id="customer_list">
                <thead class="text-center">
                    <tr class="text-dark">
                        <th scope="col" class="table-info">Client Name</th>
                        <th scope="col" class="table-info">Industry Sector</th>
                        <th scope="col" class="table-info">Controlling Ministry</th>
                        <th scope="col" class="table-info">Ministry Contact</th>
                        <th scope="col" class="table-info">Key Client Contact</th>
                        <th scope="col" class="table-info">Key Client Designation</th>
                        <th scope="col" class="table-info">Account Servicing Personal Initial</th>
                        <th scope="col" class="table-info">Action</th>
                    </tr>
                </thead>
                <tbody class="table-stripped text-center">

                    @if ($customers)
                    <?php
                    // $count = 1;
                    ?>
                    @foreach ($customers as $customer)
                    <tr>
                        <td class="my-1">{{ $customer->client_name }}</td>
                        <td class="my-1">{{ $customer->industry_sector}}</td>
                        <td class="my-1">{{ $customer->controlling_ministry }} </td>
                        <td class="my-1">{{ $customer->ministry_contact }}</td>
                        <td class="my-1">{{ $customer->key_client_contact_name }}</td>
                        <td class="my-1">{{ $customer->key_projects_or_sales_activity }}</td>
                        <td class="my-1">{{ $customer->account_servicing_persons_initials }}</td>
                        <td class="my-1">
                            <div class="item-flex-center">  
                                <button type="button" class="btn btn-custom-primary2" onclick="editCustomer({{ $customer->id }} );"><i class="fas fa-pen"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif

                </tbody>
            </table>

        </div>

        <div class="d-flex justify-content-center">
            <div> {!! $customers->links() !!} </div>
        </div>

        @endif
        </div>
    </div>

<!-- item edit modal -->
<div class="modal fade" id="customerEditModal" tabindex="-1" aria-labelledby="customerEditModal" aria-hidden="true">
    <div class="modal-md modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <!-- form start -->
                <form method="POST" id="customer_edit_form" action="">

                    @csrf

                    <input type="hidden" class="form-control" id="edit_customer_id" name="show_customer_id">
                    
                    <div class="form-group row mb-3 mx-3">
                        <label for="empNo" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Customer Name</label>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" id="editCusName" name="editCusName" required>
                        </div>
                    </div>
                    <div class="form-group row mb-3 mx-3">
                        <label for="forFirstName" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Industry Sector</label>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" id="editCusIndustry" name="editCusIndustry">
                        </div>
                    </div>
                    <div class="form-group row mb-3 mx-3">
                        <label for="forContactNumber" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Controlling Ministry</label>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" id="editCusMinistry" name="editCusMinistry">
                        </div>
                    </div>
                    <div class="form-group row mb-3 mx-3">
                        <label for="forJobRole" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Ministry Contact</label>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" id="editCusMinContact" name="editCusMinContact">
                        </div>
                    </div>
                    <div class="form-group row mb-3 mx-3">
                        <label for="forJobRole" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Key client Contact</label>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" id="editCusContact" name="editCusContact">
                        </div>
                    </div>
                    <div class="form-group row mb-3 mx-3">
                        <label for="forJobRole" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Key client Designation</label>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" id="editCusDesignation" name="editCusDesignation">
                        </div>
                    </div>
                    <div class="form-group row mb-3 mx-3">
                        <label for="forJobRole" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Key Projects</label>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" id="editCusProjects" name="editCusProjects">
                        </div>
                    </div>
                    <div class="form-group row mb-3 mx-3">
                        <label for="forJobRole" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Acount Servicing Personal Initials</label>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" id="editCusAccPersIni" name="editCusAccPersIni">
                        </div>
                    </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="deleteCustomerButton">
                    <i class="fas fa-trash"></i> Delete
                </button>
                <button type="submit" class="btn btn-primary">Update changes</button>
            </div>

            </form>
            <!-- form end -->

        </div>
    </div>
</div>
<!-- item edit modal end-->

</div>

<style>          
    .swal2-container:not(.in) {
        pointer-events: visible !important;
        z-index: 9999 !important;
    }
</style>

@endsection