@extends('admin.admin_layout')

@section('content')
<nav aria-label="breadcrumb" class="mx-4 my-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
        {{-- <li class="breadcrumb-item"><i class="fas fa-angle-right"></i></li> --}}
        <li class="breadcrumb-item active" aria-current="page"><strong>Sub Categories</strong></li>
    </ol>
</nav>

<div class="tab-content" id="nav-tabContent">
    <!-- Customer Registration Start -->
    <div class="container-fluid px-4">
        <div class="rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="text-center mb-3">New Sub Category</h3>
            </div>

            <div class="row mt-3">

                <div class="col-lg-9 col-md-9 col-sm-12">

                    <!-- form start -->
                    <form method="POST" id="subCategory_form" action="{{ route('data.subCategory_form') }}">

                        @csrf

                        <div class="form-group row mb-3 mx-3">
                            <label for="category" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Category Name</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <select class="form-select" aria-label="Category Name" id="categoryName" name="categoryName" required>
                                    <option selected disabled value="">Select Category</option>
                                    @if ($categories) 
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mx-3">
                            <label for="empNo" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Sub Category Name</label>
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <input type="text" class="form-control" id="subCatName" name="subCatName" required>
                            </div>
                        </div>
                        <div class="form-group row my-5 mx-2">
                            <div class="col-lg-12 item-flex-end">
                                <button type="submit" name="submit_subCategory" class="btn btn-primary btn-custom mb-3" id="subCategorySubmit">Submit</button>
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
                    <h5 class="mt-2">Category Details</h5>
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

            <table class="table align-middle table-striped table-bordered table-hover mb-0" id="subCategory_list">
                <thead class="text-center">
                    <tr class="text-dark">
                        <th scope="col" class="table-info">Category Name</th>
                        <th scope="col" class="table-info">Sub Category Name</th>
                        <th scope="col" class="table-info">Action</th>
                    </tr>
                </thead>
                <tbody class="table-stripped text-center">

                    @if ($subCategories)
                    <?php
                    // $count = 1;
                    ?>
                    @foreach ($subCategories as $subCategory)
                    <tr>
                        <td>{{ $subCategory->category ? $subCategory->category->name : 'N/A' }}</td>
                        <td>{{ $subCategory->name }}</td>
                        <td class="my-1">
                            <div class="item-flex-center">  
                                <button type="button" class="btn btn-custom-primary2" onclick="editSubCategory({{ $subCategory->id }} );"><i class="fas fa-pen"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif

                </tbody>
            </table>

        </div>

        <div class="d-flex justify-content-center">
            <div> {!! $subCategories->links() !!} </div>
        </div>

        @endif
        </div>
    </div>

<!-- item edit modal -->
<div class="modal fade" id="subCategoryEditModal" tabindex="-1" aria-labelledby="subCategoryEditModal" aria-hidden="true">
    <div class="modal-md modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Sub Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <!-- form start -->
                <form method="POST" id="subCategory_edit_form" action="">

                    @csrf

                    <input type="hidden" class="form-control" id="edit_subCategory_id" name="show_subCategory_id">

                    <div class="form-group row mb-3 mx-3">
                        <label for="categoryName" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Category Name</label>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <!-- Display the category name in a non-editable text field -->
                            <input type="text" class="form-control" id="editCategoryName" name="editCategoryName" readonly>
                        </div>
                    </div>
                    
                    <div class="form-group row mb-3 mx-3">
                        <label for="empNo" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-form-label">Sub Category Name</label>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" id="editSubCatName" name="editSubCatName" required>
                        </div>
                    </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="deleteSubCategoryButton">
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