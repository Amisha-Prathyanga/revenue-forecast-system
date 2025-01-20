$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

function editCustomerOpportunity(id) {
    $.ajax({
        type: "post",
        url: "/editCusOpportunity", 
        dataType: "json",
        data: {
            id: id,
        },
        success: function (data) {
            
            $("#edit_opportunity_id").val(data["id"]);
            $("#editCustomerId").val(data["customer_id"]);
            $("#editAccMngrId").val(data["accMngr_id"]);
            $("#editCustomerName").val(data["customer_name"]); 
            $("#editAccountManagerName").val(data["account_manager_name"]); 
            $("#editCategory").val(data["category_id"]);
            $("#editSubCategory").val(data["sub_category_id"]);
            $("#editCategoryName").val(data["category_name"]); 
            $("#editSubCategoryName").val(data["sub_category_name"]); 
            $("#editRevenue").val(data["revenue"]);
            $("#editForeignCosts").val(data["foreign_costs"]);
            $("#editLocalCosts").val(data["local_costs"]);
            $("#editDate").val(data["date"]);

            
            $("#editCategory").val(data["category_id"]);

            
            loadSubCategories(data["category_id"], data["sub_category_id"]);

            $("#deleteCustomerOpportunityButton").attr("data-id", data["id"]);

            
            $("#customerOpportunityEditModal").modal("show");
        },
        error: function (error) {
            alert("Error occurred: " + JSON.stringify(error));
        },
    });
}

// Function to dynamically load subcategories based on selected category
function loadSubCategories(categoryId, selectedSubCategoryId) {
    $.ajax({
        type: "GET",
        url: "/getSubCategories", 
        data: {
            category_id: categoryId,
        },
        success: function (subCategories) {
            var subCategorySelect = $("#editSubCategory");
            subCategorySelect.empty(); 
            subCategorySelect.append(
                '<option value="" disabled selected>Select Sub Category</option>'
            );

            $.each(subCategories, function (index, subCategory) {
                var option = $("<option></option>")
                    .attr("value", subCategory.id)
                    .text(subCategory.name);

                    if (selectedSubCategoryId && subCategory.id === selectedSubCategoryId) {
                        option.prop("selected", true);
                    }

                subCategorySelect.append(option);
            });
        },
        error: function (error) {
            alert(
                "Error occurred while fetching subcategories: " +
                    JSON.stringify(error)
            );
        },
    });
}

$("#customerOpportunity_edit_form").submit(function (event) {
    
    event.preventDefault();

    
    var token = $('meta[name="csrf-token"]').attr("content");

    
    var formData = {
        _token: token,
        edit_opportunity_id: $("#edit_opportunity_id").val().trim(),
        customer_id: $("#editCustomerId").val(), 
        accMngr_id: $("#editAccMngrId").val(), 
        category_id: $("#editCategory").val(),
        sub_category_id: $("#editSubCategory").val(),
        revenue: $("#editRevenue").val().trim(),
        foreign_costs: $("#editForeignCosts").val().trim(),
        local_costs: $("#editLocalCosts").val().trim(),
        date: $("#editDate").val().trim(),
    };

    $.ajax({
        type: "post",
        url: "/updateCusOpportunity", 
        dataType: "json",
        data: formData,
        success: function (data) {
            $("#customerOpportunityEditModal").modal("hide");
            
            
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'Customer Opportunity updated successfully.',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                location.reload();
            });
        },
        error: function (xhr, status, error) {
            
            if (xhr.responseText.indexOf("<!DOCTYPE html>") !== -1) {
                swal_alert(
                    "error",
                    "Session Expired",
                    "Your session has expired. Please refresh the page and try again."
                );
            } else {
                alert("Error occurred: " + JSON.stringify(xhr.responseJSON));
            }
        },
    });
});

function deleteCustomerOpportunity(opportunityId) {
    
    Swal.fire({
        title: "Are you sure?",
        text: "This action will delete the customer opportunity. You won't be able to undo this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            
            $.ajax({
                url: "/deleteCusOpportunity", 
                type: "POST",
                data: {
                    id: opportunityId,
                    _token: $('meta[name="csrf-token"]').attr("content"), 
                },
                success: function (response) {
                    if (response) {
                        Swal.fire(
                            "Deleted!",
                            "Customer Opportunity has been deleted.",
                            "success"
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            "Error!",
                            "Unable to delete the customer opportunity.",
                            "error"
                        );
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire("Error!", "Something went wrong.", "error");
                },
            });
        }
    });
}


$(document).on("click", "#deleteCustomerOpportunityButton", function () {
    var opportunityId = $(this).attr("data-id");
    deleteCustomerOpportunity(opportunityId);
});

$(document).ready(function () {
    $("#category").on("change", function () {
        var categoryId = $(this).val();

        if (categoryId) {
            $.ajax({
                url: getSubcategoriesUrl, 
                type: "POST",
                data: {
                    category_id: categoryId,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (data) {
                    var subcategoryDropdown = $("#subcategory");
                    subcategoryDropdown.empty();
                    subcategoryDropdown.append(
                        '<option selected disabled value="">Select Sub Category</option>'
                    );

                    $.each(data, function (key, subcategory) {
                        subcategoryDropdown.append(
                            '<option value="' +
                                subcategory.id +
                                '">' +
                                subcategory.name +
                                "</option>"
                        );
                    });
                },
                error: function () {
                    alert("Error fetching subcategories.");
                },
            });
        } else {
            $("#subcategory")
                .empty()
                .append(
                    '<option selected disabled value="">Select Sub Category</option>'
                );
        }
    });

    
    $("#editCategory").on("change", function () {
        var categoryId = $(this).val();
        if (categoryId) {
            
            loadSubCategories(categoryId);
        } else {
            $("#editSubCategory")
                .empty()
                .append('<option selected disabled value="">Select Sub Category</option>');
        }
    });
});
