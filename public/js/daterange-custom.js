function initializeDateRangePicker() {
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
        maxSpan: {
            days: 31
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

    let dateRangePicker = $('#daterange').daterangepicker(datePickerOptions);

    $('#daterange').attr('placeholder', 'Pilih rentang tanggal');
    $('#daterange').attr('readonly', true);

    $('#daterange').on('show.daterangepicker', function () {
        setTimeout(function () {
            $('.daterangepicker .ranges').addClass('scrollable-ranges');
        }, 10);
    });

    $('#daterange').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });

    $('#daterange').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });

    return dateRangePicker;
}
