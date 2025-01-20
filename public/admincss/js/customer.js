$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

function editCustomer(id) {
    $.ajax({
        type: "post",
        url: "editCustomer",
        dataType: "json",
        data: {
            id: id,
        },
        success: function (data) {
            // location.reload();
            // swal_alert("success", "Saved!", "New Route Saved Successfully.");
            $("#edit_customer_id").val(data["id"]);
            $("#editCusName").val(data["client_name"]);
            $("#editCusIndustry").val(data["industry_sector"]);
            $("#editCusMinistry").val(data["controlling_ministry"]);
            $("#editCusMinContact").val(data["ministry_contact"]);
            $("#editCusContact").val(data["key_client_contact_name"]);
            $("#editCusDesignation").val(
                data["key_client_contact_designation"]
            );
            $("#editCusProjects").val(data["key_projects_or_sales_activity"]);
            $("#editCusAccPersIni").val(
                data["account_servicing_persons_initials"]
            );

            $("#deleteCustomerButton").attr("data-id", data["id"]);

            $("#customerEditModal").modal("show");
        },
        error: function (error) {
            alert("error occured " + JSON.stringify(error));
        },
    });
}

$("#customer_edit_form").submit(function (event) {
    
    event.preventDefault();
    var edit_customer_id = $("#edit_customer_id").val().trim();
    var editCusName = $("#editCusName").val().trim();
    var editCusIndustry = $("#editCusIndustry").val().trim();
    var editCusMinistry = $("#editCusMinistry").val().trim();
    var editCusMinContact = $("#editCusMinContact").val().trim();
    var editCusContact = $("#editCusContact").val().trim();
    var editCusDesignation = $("#editCusDesignation").val().trim();
    var editCusProjects = $("#editCusProjects").val().trim();
    var editCusAccPersIni = $("#editCusAccPersIni").val().trim();
    var deleteCustomerButton = $("#deleteCustomerButton").val().trim();

    $.ajax({
        type: "post",
        url: "updateCustomer",
        dataType: "json",
        data: {
            edit_customer_id: edit_customer_id,
            editCusName: editCusName,
            editCusIndustry: editCusIndustry,
            editCusMinistry: editCusMinistry,
            editCusMinContact: editCusMinContact,
            editCusContact: editCusContact,
            editCusDesignation: editCusDesignation,
            editCusProjects: editCusProjects,
            editCusAccPersIni: editCusAccPersIni,
        },
        success: function (data) {
            $("#customerEditModal").modal("hide");
            location.reload();
            swal_alert("success", "Saved!", " Item Updated Successfully.");
        },
        error: function (error) {
            alert("error occured " + JSON.stringify(error));
        },
    });
});

function deleteCustomer(customerId) {
    
    Swal.fire({
        title: "Are you sure?",
        text: "This action will delete the customer. You won't be able to undo this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            
            $.ajax({
                url: "/deleteCustomer",
                type: "POST",
                data: {
                    id: customerId,
                    _token: $('meta[name="csrf-token"]').attr("content"), 
                },
                success: function (response) {
                    if (response) {
                        Swal.fire(
                            "Deleted!",
                            "Customer has been deleted.",
                            "success"
                        ).then(() => {
                            location.reload(); 
                        });
                    } else {
                        Swal.fire(
                            "Error!",
                            "Unable to delete the customer.",
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

$(document).on("click", "#deleteCustomerButton", function () {
    var customerId = $(this).attr("data-id");
    deleteCustomer(customerId);
});
