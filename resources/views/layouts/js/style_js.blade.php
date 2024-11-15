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


<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
