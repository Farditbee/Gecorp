function showNotification(type, message, title = '') {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    switch (type) {
        case 'success':
            toastr.success(message, title);
            break;
        case 'info':
            toastr.info(message, title);
            break;
        case 'warning':
            toastr.warning(message, title);
            break;
        case 'error':
            toastr.error(message, title);
            break;
        default:
            console.error('Unknown toastr type: ' + type);
            break;
    }
}
