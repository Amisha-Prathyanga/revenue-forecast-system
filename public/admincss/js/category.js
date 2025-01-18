$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

function editCategory(id) {
    $.ajax({
        type: "post",
        url: "editCategory",
        dataType: "json",
        data: {
            id: id,
        },
        success: function (data) {
            // location.reload();
            // swal_alert("success", "Saved!", "New Route Saved Successfully.");
            $("#edit_category_id").val(data["id"]);
            $("#editCatName").val(data["name"]);

            $("#deleteCategoryButton").attr("data-id", data["id"]);

            $("#categoryEditModal").modal("show");
        },
        error: function (error) {
            alert("error occured " + JSON.stringify(error));
        },
    });
}

$("#category_edit_form").submit(function (event) {
    // stop the default execution
    event.preventDefault();
    var edit_category_id = $("#edit_category_id").val().trim();
    var editCatName = $("#editCatName").val().trim();
    var deleteCategoryButton = $("#deleteCategoryButton").val().trim();

    $.ajax({
        type: "post",
        url: "updateCategory",
        dataType: "json",
        data: {
            edit_category_id: edit_category_id,
            editCatName: editCatName,
        },
        success: function (data) {
            $("#categoryEditModal").modal("hide");
            location.reload();
            swal_alert("success", "Saved!", " Category Updated Successfully.");
        },
        error: function (error) {
            alert("error occured " + JSON.stringify(error));
        },
    });
});

function deleteCategory(categoryId) {
    // Show confirmation dialog
    Swal.fire({
        title: "Are you sure?",
        text: "This action will delete the category. You won't be able to undo this!",
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
                url: "/deleteCategory",
                type: "POST",
                data: {
                    id: categoryId,
                    _token: $('meta[name="csrf-token"]').attr("content"), // Add CSRF token
                },
                success: function (response) {
                    if (response) {
                        Swal.fire(
                            "Deleted!",
                            "Category has been deleted.",
                            "success"
                        ).then(() => {
                            location.reload(); // Refresh the page after successful deletion
                        });
                    } else {
                        Swal.fire(
                            "Error!",
                            "Unable to delete the category.",
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

$(document).on("click", "#deleteCategoryButton", function () {
    var categoryId = $(this).attr("data-id");
    deleteCategory(categoryId);
});
