setTimeout(function () {
    $(".alert").hide();
}, 5000);
$(document).ready(function () {
    setTimeout(function () {
        $('.alert.alert-danger , .alert.alert-success').fadeOut('slow');
    }, 2500);

    $(".splandnumberAvoid").on("keypress", function (e) {
        var charCode = e.which;
        var currentLength = $(this).val().length;
        if ((charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122) && charCode !== 32) {
            e.preventDefault();
            toastr.error("Numeric and special characters are not allowed!");
        } else if (currentLength >= 20) {
            e.preventDefault();
            toastr.error("Maximum length of input value reached!");
        }
    });

    $(".splandAvoidUsername").on("keypress", function (e) {
        var charCode = e.which;
        var currentLength = $(this).val().length;
    
        // Allow letters (A-Z, a-z), numbers (0-9), and space (charCode 32)
        if ((charCode < 48 || charCode > 57) && // Numbers (0-9)
            (charCode < 65 || charCode > 90) && // Letters (A-Z)
            (charCode < 97 || charCode > 122) && // Letters (a-z)
            charCode !== 32) { // Space
            e.preventDefault();
            toastr.error("Special characters are not allowed!");
        } else if (currentLength >= 20) {
            e.preventDefault();
            toastr.error("Maximum length of input value reached!");
        }
    });
});

