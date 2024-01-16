$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
});
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
                    $(".modal").style.display = "none";
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
