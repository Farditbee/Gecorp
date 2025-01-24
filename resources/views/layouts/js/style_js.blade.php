<script src="{{ asset('flat-able-lite/dist/assets/js/vendor-all.min.js') }}"></script>
<script src="{{ asset('flat-able-lite/dist/assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('flat-able-lite/dist/assets/js/pcoded.min.js') }}"></script>

<!-- prism Js -->
<script src="{{ asset('flat-able-lite/dist/assets/js/plugins/prism.js') }}"></script>
<script src="{{ asset('flat-able-lite/dist/assets/js/horizontal-menu.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
    (function() {
        if ($('#layout-sidenav').hasClass('sidenav-horizontal') || window.layoutHelpers.isSmallScreen()) {
            return;
        }
        try {
            window.layoutHelpers._getSetting("Rtl")
            window.layoutHelpers.setCollapsed(
                localStorage.getItem('layoutCollapsed') === 'true',
                false
            );
        } catch (e) {}
    })();
    $(function() {
        $('#layout-sidenav').each(function() {
            new SideNav(this, {
                orientation: $(this).hasClass('sidenav-horizontal') ? 'horizontal' : 'vertical'
            });
        });
        $('body').on('click', '.layout-sidenav-toggle', function(e) {
            e.preventDefault();
            window.layoutHelpers.toggleCollapsed();
            if (!window.layoutHelpers.isSmallScreen()) {
                try {
                    localStorage.setItem('layoutCollapsed', String(window.layoutHelpers.isCollapsed()));
                } catch (e) {}
            }
        });
    });
    $(document).ready(function() {
        $("#pcoded").pcodedmenu({
            themelayout: 'horizontal',
            MenuTrigger: 'hover',
            SubMenuTrigger: 'hover',
        });
    });
</script>
<script src="{{ asset('flat-able-lite/dist/assets/js/analytics.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        // Mencegah submit form saat menekan Enter pada input dengan id #search
        $("#search").on("keydown", function(event) {
            if (event.key === "Enter") {
                event.preventDefault(); // Mencegah submit form
            }
        });

        // Event pencarian pada input
        $("#search").on("keyup", function() {
            console.log('kons');
            var value = $(this).val().toLowerCase(); // Ambil nilai input
            $("#jsTable tbody tr").filter(function() {
                // Show/hide baris berdasarkan pencarian pada kolom yang ada
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>

<script>
    // Fungsi Paginate
    $(document).ready(function() {
        var rowsPerPage = 10; // Jumlah baris per halaman
        var rows = $('#jsTable tbody tr'); // Mengambil semua baris dari tabel
        var rowsCount = rows.length; // Menghitung jumlah total baris
        var pageCount = Math.ceil(rowsCount / rowsPerPage); // Menghitung jumlah halaman
        var numbers = $('#pagination'); // Elemen untuk tombol pagination
        var currentPage = 1; // Halaman aktif saat ini

        // Fungsi untuk menampilkan baris sesuai halaman
        function showPage(page) {
            var start = (page - 1) * rowsPerPage;
            var end = start + rowsPerPage;
            rows.hide(); // Sembunyikan semua baris
            rows.slice(start, end).show(); // Tampilkan baris sesuai halaman yang dipilih
        }

        // Fungsi untuk membuat tombol pagination
        function createPaginationButtons() {
            numbers.empty(); // Hapus semua tombol pagination yang ada
            numbers.append(
                '<button class="prev-btn btn btn-sm btn-outline-primary mx-1">Previous</button>'
                ); // Tombol Previous

            for (var i = 1; i <= pageCount; i++) {
                numbers.append('<button class="page-btn btn btn-sm btn-primary mx-1" data-page="' + i + '">' +
                    i + '</button>');
            }

            numbers.append(
                '<button class="next-btn btn btn-sm btn-outline-primary mx-1">Next</button>'); // Tombol Next

            // Menonaktifkan tombol Previous jika berada di halaman pertama
            if (currentPage === 1) {
                $('.prev-btn').prop('disabled', true);
            } else {
                $('.prev-btn').prop('disabled', false);
            }

            // Menonaktifkan tombol Next jika berada di halaman terakhir
            if (currentPage === pageCount) {
                $('.next-btn').prop('disabled', true);
            } else {
                $('.next-btn').prop('disabled', false);
            }

            // Menyoroti tombol halaman yang aktif
            $('.page-btn').removeClass('active');
            $('.page-btn[data-page="' + currentPage + '"]').addClass('active');
        }

        // Inisialisasi tampilan halaman pertama
        showPage(currentPage);
        createPaginationButtons();

        // Ketika tombol halaman diklik
        numbers.on('click', '.page-btn', function() {
            currentPage = $(this).data('page');
            showPage(currentPage);
            createPaginationButtons();
        });

        // Ketika tombol Previous diklik
        numbers.on('click', '.prev-btn', function() {
            if (currentPage > 1) {
                currentPage--;
                showPage(currentPage);
                createPaginationButtons();
            }
        });

        // Ketika tombol Next diklik
        numbers.on('click', '.next-btn', function() {
            if (currentPage < pageCount) {
                currentPage++;
                showPage(currentPage);
                createPaginationButtons();
            }
        });
    });

    // Fungsi Show Data
    $(document).ready(function() {
        // Mengambil semua baris dari tabel
        var rows = $('#jsTable tbody tr');
        var totalData = rows.length; // Total semua data di tabel

        // Tampilkan total data di awal
        $('#total-count').text(totalData);

        // Fungsi untuk memperbarui jumlah data yang terlihat
        function updateDataCount() {
            var visibleRows = rows.filter(':visible').length; // Hitung jumlah baris yang terlihat
            $('#current-count').text(visibleRows); // Perbarui jumlah data yang ditampilkan
            $('#total-count').text(totalData); // Menampilkan jumlah total data
        }

        // Inisialisasi jumlah data yang tampil di awal
        updateDataCount();

        // Fungsi pencarian
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();

            rows.filter(function() {
                // Tampilkan hanya baris yang sesuai dengan input pencarian
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });

            // Perbarui jumlah data yang tampil setelah pencarian
            updateDataCount();
        });

        // Panggil updateDataCount() ketika melakukan pagination
        $('#pagination').on('click', '.page-btn', function() {
            // Logika pagination (jika ada)
            updateDataCount(); // Perbarui jumlah data yang terlihat
        });
    });
</script>
