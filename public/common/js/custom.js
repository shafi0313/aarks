$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    // $(".alert").alert();
    // window.setTimeout(function() {
    //     $(".alert").alert("close");
    // }, 9e3);
    // initSelect2(['#month', '#year', '#class_id', '#section_id']);
    // $('.select-2').select2();
    // $('#dataTable').dataTable();

    // $(document).on('click', '._delete', function(e) {
    //     e.preventDefault();
    //     let url = $(this).data('route');
    //     let title = $(this).data('title');
    //     $('#_delete').modal('show');
    //     $('#_deleteTitle').html(title);
    //     $('#_deleteForm').attr('action', url);
    // });
    // $(document).on('click', '#checkAll', function() {
    //     const chk = $('.check');
    //     if ($(this).is(':checked')) {
    //         chk.prop('checked', true);
    //     } else {
    //         chk.prop('checked', false);
    //     }
    // });
    // $(".hasChildOptions").on("change", function () {
    //     if ($(this).prop("checked") == true) {
    //         if ($(this).data("show_child")) {
    //             if ($(this).data("show_child") == 1) {
    //                 $("#" + $(this).data("child_id")).show();
    //             } else {
    //                 $("#" + $(this).data("child_id")).hide();
    //             }
    //         } else $("#" + $(this).data("child_id")).show();
    //     } else {
    //         $("#" + $(this).data("child_id"))
    //             .find("input")
    //             .prop("checked", false);
    //         $("#" + $(this).data("child_id")).hide();
    //     }
    // });
});
// Checkbox checked
// function checkcheckbox() {
//     // Total checkboxes
//     var length = $('.check').length;
//     // Total checked checkboxes
//     var totalchecked = 0;
//     $('.check').each(function() {
//         if ($(this).is(':checked')) {
//             totalchecked += 1;
//         }
//     });

//     // Checked unchecked checkbox
//     if (totalchecked == length) {
//         $("#checkAll").prop('checked', true);
//     } else {
//         $('#checkAll').prop('checked', false);
//     }
// }

// function changeStatus(arg) {
//     let status = $(arg);
//     swal({
//             title: "Are you sure?",
//             text: "This change will affect all records!",
//             icon: "warning",
//             buttons: true,
//             dangerMode: true,
//         })
//         .then((willDelete) => {
//             if (willDelete) {
//                 $.ajax({
//                     url: status.data('route'),
//                     type: 'post',
//                     data: {
//                         status: status.data('value'),
//                     },
//                     success: res => {
//                         swal({
//                             icon: 'success',
//                             title: 'Success',
//                             text: res.message
//                         });
//                         $('.table').DataTable().ajax.reload();
//                     },
//                     error: err => {
//                         swal({
//                             icon: 'error',
//                             title: 'Oops...',
//                             text: err.responseJSON.message
//                         });
//                     }
//                 });
//             }
//         });
// }

function ajaxDelete(arg, type) {
    let args = $(arg);
    swal({
        title: "Are you sure?",
        text: "This action will delete this record and cannot be undone!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: args.data("route"),
                type: "delete",
                data: {
                    id: args.data("value"),
                },
                success: (res) => {
                    swal({
                        icon: "success",
                        title: "Success",
                        text: res.message,
                    }).then((confirm) => {
                        if (confirm) {
                            if (type == "dt") {
                                $(".table").DataTable().ajax.reload();
                            } else {
                                window.location.reload();
                            }
                        }
                    });
                },
                error: (err) => {
                    swal({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message,
                    });
                },
            });
        }
    });
}

function ajaxAllDelete(arg, type) {
    let args = $(arg);
    var del_ids = [];
    // Read all checked checkboxes
    $("input:checkbox[class=check]:checked").each(function () {
        del_ids.push($(this).val());
    });
    // Check checkbox checked or not
    if (del_ids.length > 0) {
        swal({
            title: "Are you sure?",
            text: "This action will delete all records and cannot be undone!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: args.data("route"),
                    type: "delete",
                    data: {
                        ids: del_ids,
                    },
                    success: (res) => {
                        swal({
                            icon: "success",
                            title: "Success",
                            text: res.message,
                        }).then((confirm) => {
                            if (confirm) {
                                if (type == "dt") {
                                    $(".table").DataTable().ajax.reload();
                                } else {
                                    window.location.reload();
                                }
                            }
                        });
                    },
                    error: (err) => {
                        swal({
                            icon: "error",
                            title: "Oops...",
                            text: err.responseJSON.message,
                        });
                    },
                });
            }
        });
    }
}

function ajaxEdit(arg, type) {
    let args = $(arg);
    $.ajax({
        url: args.data("route"),
        type: "get",
        data: {
            id: args.data("value"),
        },
        success: (res) => {
            $("#ajax_modal_container").html(res.modal);
            $("#editModal").modal("show");
        },
        error: (err) => {
            swal({
                icon: "error",
                title: "Oops...",
                text: err.responseJSON.message,
            });
        },
    });
}

function ajaxStoreModal(e, form, modal) {
    e.preventDefault();
    // let formData = $(form).serialize();
    let formData = new FormData(form);
    $.ajax({
        url: $(form).attr("action"),
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: (res) => {
            Swal.fire({
                icon: "success",
                title: "Success",
                text: res.message,
            }).then((confirm) => {
                if (confirm) {
                    $(".table").DataTable().ajax.reload();
                    $("#" + modal).modal("hide");

                    $(".modal").style.display = 'none';
                    $(form).trigger("reset");
                }
            });
        },
        error: (err) => {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: err.responseJSON.message,
            });
        },
    });
}
function ajaxStore(e, form) {
    e.preventDefault();
    // let formData = $(form).serialize();
    let formData = new FormData(form);
    $.ajax({
        url: $(form).attr("action"),
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: (res) => {
            swal({
                icon: "success",
                title: "Success",
                text: res.message,
            })
            // .then((confirm) => {
            //     if (confirm) {
            //         $(".table").DataTable().ajax.reload();
            //         $("#" + modal).modal("hide");
            //         $(form).trigger("reset");
            //     }
            // });
        },
        error: (err) => {
            swal({
                icon: "error",
                title: "Oops...",
                text: err.responseJSON.message,
            });
        },
    });
}

function changeStatus(arg) {
    let status = $(arg);
    swal({
        title: "Are you sure?",
        text: "This change will affect all records!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: status.data("route"),
                type: "PATCH",
                data: {
                    status: status.data("value"),
                },
                success: (res) => {
                    swal({
                        icon: "success",
                        title: "Success",
                        text: res.message,
                    });
                    $(".table").DataTable().ajax.reload();
                },
                error: (err) => {
                    swal({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message,
                    });
                },
            });
        }
    });
}

function accept(arg) {
    let status = $(arg);
    swal({
        title: "Are you sure?",
        text: "This change will affect this record!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: status.data("route"),
                type: "GET",
                data: {
                    status: status.data("value"),
                },
                success: (res) => {
                    swal({
                        icon: "success",
                        title: "Success",
                        text: res.message,
                    });
                    $(".table").DataTable().ajax.reload();
                },
                error: (err) => {
                    swal({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message,
                    });
                },
            });
        }
    });
}

function reject(arg) {
    let status = $(arg);
    swal({
        title: "Are you sure?",
        text: "This change will affect this record!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: status.data("route"),
                type: "GET",
                data: {
                    status: status.data("value"),
                },
                success: (res) => {
                    swal({
                        icon: "success",
                        title: "Success",
                        text: res.message,
                    });
                    $(".table").DataTable().ajax.reload();
                },
                error: (err) => {
                    swal({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message,
                    });
                },
            });
        }
    });
}

// function select2Ajax(id, placeholder, route, dropdown = 'body') {
//     $('#' + id).select2({
//         placeholder: placeholder,
//         minimumInputLength: 2,
//         dropdownParent: $(dropdown),
//         ajax: {
//             url: route,
//             dataType: 'json',
//             delay: 250,
//             cache: true,
//             data: function(params) {
//                 return {
//                     q: $.trim(params.term)
//                 };
//             },
//             processResults: function(data) {
//                 return {
//                     results: data
//                 };
//             }
//         }
//     });
// }

// $(document).ready(function () {
//     $(".select2single").select2();
//     $(".select2singleModel").select2({
//         dropdownParent: $("#createModal").closest("div"),
//     });

//     var fullDate = new Date();
//     var twoDigitMonth =
//         (fullDate.getMonth() + 1 < 10 ? "0" : "") + (fullDate.getMonth() + 1);
//     var currentDate =
//         (fullDate.getDate() < 10 ? "0" : "") +
//         fullDate.getDate() +
//         "/" +
//         twoDigitMonth +
//         "/" +
//         fullDate.getFullYear();
//     $(".bDP input").val(currentDate);
//     // $('#order_date').val(currentDate);
// });

// $(function () {
//     // var date = new Date();
//     // var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
//     $(".bDP input").datepicker({
//         format: "dd/mm/yyyy",
//         autoclose: true,
//         clearBtn: true,
//         todayHighlight: true,
//         container: ".bDP",
//         defaultViewDate: "today",
//         orientation: "auto",
//     });
// });

function digitInput(event) {
    event.target.value = event.target.value.replace(/[^\d]/g, "");
}

function floatInput(event) {
    event.target.value = event.target.value.replace(/[^\d.]/g, "");
}
function phoneNI(event) {
    event.target.value = event.target.value.replace(/[^\d.+-]/g, "");
}

// For Active Inactive
$(function () {
    document
        .getElementById("is_active_input")
        .addEventListener("click", function () {
            document.getElementById("is_active_label").innerHTML = this.checked
                ? "Active"
                : "Inactive";
        });
});

// Label Asterisk Color Red
$(function () {
    // Get all <label> elements
    const labels = document.getElementsByTagName("label");
    // Iterate through each <label> element
    for (let i = 0; i < labels.length; i++) {
        const label = labels[i];
        // Get the label's text content
        const labelText = label.textContent;
        // Check if the label's text content contains '*'
        if (labelText.includes("*")) {
            // Replace the asterisk (*) with a span element
            const updatedText = labelText.replace(
                /\*/g,
                '<span style="color: red">*</span>'
            );
            // Update the label's HTML with the updated text
            label.innerHTML = updatedText;
        }
    }
});
