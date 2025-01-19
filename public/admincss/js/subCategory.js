$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

function editSubCategory(id) {
    $.ajax({
        type: "post",
        url: "editSubCategory",
        dataType: "json",
        data: {
            id: id,
        },
        success: function (data) {
            // location.reload();
            // swal_alert("success", "Saved!", "New Route Saved Successfully.");
            $("#edit_subCategory_id").val(data["id"]);
            $("#editSubCatName").val(data["name"]);
            $("#editCategoryName").val(data["category_name"]);

            $("#deleteSubCategoryButton").attr("data-id", data["id"]);

            $("#subCategoryEditModal").modal("show");
        },
        error: function (error) {
            alert("error occured " + JSON.stringify(error));
        },
    });
}

$("#subCategory_edit_form").submit(function (event) {
    // stop the default execution
    event.preventDefault();
    var edit_subCategory_id = $("#edit_subCategory_id").val().trim();
    var editSubCatName = $("#editSubCatName").val().trim();
    var deleteSubCategoryButton = $("#deleteSubCategoryButton").val().trim();

    $.ajax({
        type: "post",
        url: "updateSubCategory",
        dataType: "json",
        data: {
            edit_subCategory_id: edit_subCategory_id,
            editSubCatName: editSubCatName,
        },
        success: function (data) {
            $("#subCategoryEditModal").modal("hide");
            location.reload();
            swal_alert(
                "success",
                "Saved!",
                " Sub Category Updated Successfully."
            );
        },
        error: function (error) {
            alert("error occured " + JSON.stringify(error));
        },
    });
});

function deleteSubCategory(categoryId) {
    // Show confirmation dialog
    Swal.fire({
        title: "Are you sure?",
        text: "This action will delete the sub category. You won't be able to undo this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with deletion if confirmed
            $.ajax({
                url: "/deleteSubCategory",
                type: "POST",
                data: {
                    id: categoryId,
                    _token: $('meta[name="csrf-token"]').attr("content"), // Add CSRF token
                },
                success: function (response) {
                    if (response) {
                        Swal.fire(
                            "Deleted!",
                            "Sub Category has been deleted.",
                            "success"
                        ).then(() => {
                            location.reload(); // Refresh the page after successful deletion
                        });
                    } else {
                        Swal.fire(
                            "Error!",
                            "Unable to delete the sub category.",
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

$(document).on("click", "#deleteSubCategoryButton", function () {
    var categoryId = $(this).attr("data-id");
    deleteSubCategory(categoryId);
});
