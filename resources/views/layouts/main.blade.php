<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon icon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">

    <!-- prism css -->
    @include('layouts.css.style_css')

</head>

<body>

    <!-- [ navigation menu ] start -->
    @include('layouts.navbar')
    <!-- [ navigation menu ] end -->

    <!-- [ Header ] start -->
    @include('layouts.header')
    <!-- [ Header ] end -->

    <br>

    <!-- [ Main Content ] start -->
    @yield('content')
    <!-- [ Main Content ] end -->

    @include('layouts.footer')

    <!-- Warning Section Ends -->

    <script src="{{ asset('js/axios.js') }}"></script>
    <script src="{{ asset('js/restAPI.js') }}"></script>
    <script>
        function formatRupiah(amount) {
            return `Rp. ${amount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).replace('Rp', '').trim()}`;
        }

        async function selectList(selectors) {
            if (!Array.isArray(selectors)) {
                console.error("Selectors must be an array of element IDs.");
                return;
            }

            selectors.forEach(selector => {
                const element = document.getElementById(selector);
                if (element) {
                    if (element.choicesInstance) {
                        element.choicesInstance.destroy();
                    }

                    const choicesInstance = new Choices(element, {
                        removeItemButton: false,
                        searchEnabled: true,
                        shouldSort: false,
                        allowHTML: true,
                        placeholder: true,
                        placeholderValue: '',
                        noResultsText: 'Tidak ada hasil',
                        itemSelectText: '',
                    });

                    element.choicesInstance = choicesInstance;
                } else {
                    console.warn(`Element with ID "${selector}" not found.`);
                }
            });
        }
    </script>
    <!-- Required Js -->
    @include('layouts.js.style_js')
    <!-- Close Js -->
</body>

</html>
