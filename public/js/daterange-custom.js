function initializeDateRangePicker(isParamater = '#daterange') {
    let monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    let datePickerOptions = {
        autoApply: true,
        showDropdowns: true,
        autoUpdateInput: false,
        locale: {
            format: 'YYYY-MM-DD',
            cancelLabel: 'Batal',
            applyLabel: 'Pilih',
            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            monthNames: monthNames
        },
        opens: 'center',
        ranges: {},
        showCustomRangeLabel: false,
        alwaysShowCalendars: true,
        linkedCalendars: true
    };

    monthNames.forEach((monthName, index) => {
        let startOfMonth = moment().month(index).startOf('month');
        let endOfMonth = moment().month(index).endOf('month');
        datePickerOptions.ranges[monthName] = [startOfMonth, endOfMonth];
    });

    datePickerOptions.ranges = {
        ...datePickerOptions.ranges
    };

    let dateRangePicker = $(isParamater).daterangepicker(datePickerOptions);

    $(isParamater).attr('placeholder', 'Pilih rentang tanggal');
    $(isParamater).attr('readonly', true);

    $(isParamater).on('show.daterangepicker', function () {
        setTimeout(function () {
            $('.daterangepicker .ranges').addClass('scrollable-ranges');
        }, 10);
    });

    $(isParamater).on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });

    $(isParamater).on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });

    return dateRangePicker;
}
