// calendar.js

// Include the CSRF token in AJAX headers
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Your other JavaScript code here, including AJAX requests
// ...
