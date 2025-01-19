@extends('admin.admin_layout')

@section('content')
<nav aria-label="breadcrumb" class="mx-4 my-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page"><strong>Customer Opportunities</strong></li>
    </ol>
</nav>

<div class="tab-content" id="nav-tabContent">
    <!-- Customer Opportunity Start -->
    <div class="container-fluid px-4">
        <div class="rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="text-center mb-3">New Customer Opportunity</h3>
            </div>

            <div class="row mt-3">
                <div class="col-lg-9 col-md-9 col-sm-12">
                    <!-- form start -->
                    <form method="POST" id="customerOpportunity_form" action="{{ route('data.cusOpportunity_form') }}">
                        @csrf

                        <div class="form-group row mb-3 mx-3">
                            <label for="customer" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Customer Name</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <select class="form-select" aria-label="Customer Name" id="customer_id" name="customer_id" required>
                                    <option selected disabled value="">Select Customer</option>
                                    @if ($customers)
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->client_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mx-3">
                            <label for="category" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Category</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <select class="form-select" id="category" name="category_id" required>
                                    <option selected disabled value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group row mb-3 mx-3">
                            <label for="subcategory" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Sub Category</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <select class="form-select" id="subcategory" name="sub_category_id" required>
                                    <option selected disabled value="">Select Sub Category</option>
                                </select>
                            </div>
                        </div>
                        

                        <div class="form-group row mb-3 mx-3">
                            <label for="date" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Date</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mx-3">
                            <label for="revenue" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Revenue</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <input type="number" step="0.01" class="form-control" id="revenue" name="revenue" required>
                            </div>
                        </div> 

                        <div class="form-group row mb-3 mx-3">
                            <label for="foreign_costs" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Foreign Costs</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <input type="number" step="0.01" class="form-control" id="foreign_costs" name="foreign_costs" required>
                            </div>
                        </div>
                    
                        <div class="form-group row mb-3 mx-3">
                            <label for="local_costs" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Local Costs</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <input type="number" step="0.01" class="form-control" id="local_costs" name="local_costs" required>
                            </div>
                        </div>

                        <div class="form-group row my-5 mx-2">
                            <div class="col-lg-12 item-flex-end">
                                <button type="submit" name="submit_customerOpportunity" class="btn btn-primary btn-custom mb-3" id="customerOpportunitySubmit">Submit</button>
                            </div>
                        </div>
                    </form>
                    <!-- form end -->
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12"></div>
            </div>

            <!-- Table Section -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <h5 class="mt-2">Opportunity Details</h5>
                </div>

                <div class="col-lg-9 col-md-6 col-sm-12">
                    <form action="opportunitySearch" method="get" role="search">
                        @csrf
                    </form>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mt-4 mb-5">
                    @foreach($errors->all() as $error)
                        <li class="text-red-500 list-name">{{ $error }}</li>
                    @endforeach
                </div>
            @else
                <div class="table-responsive mt-4 mb-5">
                    <table class="table align-middle table-striped table-bordered table-hover mb-0" id="customerOpportunity_list">
                        <thead class="text-center">
                            <tr class="text-dark">
                                <th scope="col" class="table-info">Customer Name</th>
                                <th scope="col" class="table-info">Category</th>
                                <th scope="col" class="table-info">Sub Category</th>
                                <th scope="col" class="table-info">Revenue</th>
                                <th scope="col" class="table-info">Foreign Costs</th>
                                <th scope="col" class="table-info">Local Costs</th>
                                <th scope="col" class="table-info">Date</th>
                                <th scope="col" class="table-info">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-stripped text-center">
                            @if ($customerOpportunities)
                                @foreach ($customerOpportunities as $opportunity)
                                    <tr>
                                        <td>{{ $opportunity->customer ? $opportunity->customer->client_name : 'N/A' }}</td>
                                        <td>{{ $opportunity->category ? $opportunity->category->name : 'N/A' }}</td>
                                        <td>{{ $opportunity->subCategory ? $opportunity->subCategory->name : 'N/A' }}</td>
                                        <td>{{ $opportunity->revenue }}</td>
                                        <td>{{ $opportunity->foreign_costs }}</td>
                                        <td>{{ $opportunity->local_costs }}</td>
                                        <td>{{ $opportunity->date }}</td>
                                        <td class="my-1">
                                            <div class="item-flex-center">
                                                <button type="button" class="btn btn-custom-primary2" onclick="editCustomerOpportunity({{ $opportunity->id }});">
                                                    <i class="fas fa-pen"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    <div>{!! $customerOpportunities->links() !!}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Opportunity Modal -->
    <div class="modal fade" id="customerOpportunityEditModal" tabindex="-1" aria-labelledby="customerOpportunityEditModal" aria-hidden="true">
        <div class="modal-md modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Customer Opportunity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- form start -->
                    <form method="POST" id="customerOpportunity_edit_form" action="">
                        @csrf
                        <input type="hidden" class="form-control" id="edit_opportunity_id" name="edit_opportunity_id">
                        <input type="hidden" class="form-control" id="edit_opportunity_id" name="edit_opportunity_id">
                        <input type="hidden" class="form-control" id="editCustomerId" name="customer_id">
                        <input type="hidden" class="form-control" id="editAccMngrId" name="accMngr_id">

                        <div class="form-group row mb-3 mx-3">
                            <label for="customerName" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Customer Name</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <input type="text" class="form-control" id="editCustomerName" name="editCustomerName" readonly>
                            </div>
                        </div>

                        <!-- Category (Editable) -->
                    <div class="form-group row mb-3 mx-3">
                        <label for="category" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Category</label>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <select class="form-select" id="editCategory" name="editCategory" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Sub Category (Editable) -->
                    <div class="form-group row mb-3 mx-3">
                        <label for="sub_category" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Sub Category</label>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <select class="form-select" id="editSubCategory" name="editSubCategory" required>
                                <option value="" disabled selected>Select Sub Category</option>
                                <!-- Subcategories will be dynamically loaded based on selected Category -->
                            </select>
                        </div>
                    </div>

                     <div class="form-group row mb-3 mx-3">
                            <label for="date" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Date</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <input type="date" class="form-control" id="editDate" name="editDate" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mx-3">
                            <label for="revenue" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Revenue</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <input type="number" step="0.01" class="form-control" id="editRevenue" name="editRevenue" required>
                            </div>
                        </div>

                        <!-- Foreign Costs -->
                    <div class="form-group row mb-3 mx-3">
                        <label for="foreign_costs" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Foreign Costs</label>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <input type="number" step="0.01" class="form-control" id="editForeignCosts" name="editForeignCosts" required>
                        </div>
                    </div>

                    <!-- Local Costs -->
                    <div class="form-group row mb-3 mx-3">
                        <label for="local_costs" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Local Costs</label>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <input type="number" step="0.01" class="form-control" id="editLocalCosts" name="editLocalCosts" required>
                        </div>
                    </div>

                       
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="deleteCustomerOpportunityButton">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    <button type="submit" class="btn btn-primary">Update changes</button>
                </div>
                    </form>
                <!-- form end -->
            </div>
        </div>
    </div>
    <!-- Edit Opportunity Modal End -->
</div>

<style>
    .swal2-container:not(.in) {
        pointer-events: visible !important;
        z-index: 9999 !important;
    }
</style>

<script>
    var getSubcategoriesUrl = "{{ route('get.subcategories') }}";
</script>

@endsection
