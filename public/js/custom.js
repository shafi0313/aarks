// CSRF-TOKEN
$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
});

// Form Submit Button Disable & Enable
$("form").on("submit", function (e) {
    var form = $(this);
    var buttons = form.find('button[type="submit"], input[type="submit"]');
    buttons.prop("disabled", true);

    setTimeout(function () {
        buttons.prop("disabled", false);
    }, 5000); // 5 seconds in milliseconds
});

// $(document).ready(function () {
//     // Select 2
//     $(".select2Single").select2();
// });

function digitInput(event) {
    event.target.value = event.target.value.replace(/[^\d]/g, "");
}

function floatInput(event) {
    event.target.value = event.target.value.replace(/[^\d.]/g, "");
}
// Toast Notification
function toast(status, header, msg) {
    // $.toast('Here you can put the text of the toast')
    Command: toastr[status](header, msg);
    toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: true,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: true,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "2000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
    };
}
