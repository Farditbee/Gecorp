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
    <title>@yield('title') | {{ env('APP_NAME') ?? 'GSS' }}</title>
    <!-- Favicon icon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- prism css -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
    @include('layouts.css.style_css')
    <style>
        /* Elegant Finance-Themed Header */
        .header-corp {
            background: linear-gradient(135deg, #1A2E40 0%, #1D4E89 50%, #A69364 100%);
            color: white;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid #A69364;
        }

        .header-corp .b-brand b {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            letter-spacing: 1px;
            color: #FFD700;
            /* Gold color for brand name */
        }

        .header-corp .navbar-nav .dropdown-toggle,
        .header-corp .navbar-nav .dropdown-item {
            color: #FFF;
            font-weight: 500;
        }

        .header-corp .navbar-nav .dropdown-item:hover {
            background-color: rgba(255, 215, 0, 0.2);
            /* Gold with transparency */
            color: #FFD700;
        }

        .pro-head {
            background-color: #1D4E89;
            /* Navy Blue */
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #A69364;
        }

        .pro-head h5,
        .pro-head p {
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .pro-body .dropdown-item {
            color: #1A2E40;
        }

        .pro-body .dropdown-item:hover {
            background-color: #A69364;
            color: #FFF;
        }
    </style>
    @yield('css')
    <script>
        document.onreadystatechange = function() {
            var state = document.readyState;
            if (state == 'complete') {
                if (window.initPageLoad) {
                    initPageLoad();
                }
            }
        }
    </script>
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
    @include('layouts.js.style_js')
    <script src="{{ asset('js/axios.js') }}"></script>
    <script src="{{ asset('js/restAPI.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/notification.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    @yield('asset_js')
    <script>
        function formatRupiah(amount) {
            return `Rp. ${amount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).replace('Rp', '').trim()}`;
        }

        function notificationAlert(tipe, title, message) {
            swal(
                title,
                message,
                tipe
            );
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

        async function setDynamicButton() {
            const buttons = document.querySelectorAll('.btn-dynamic');

            buttons.forEach((button) => {
                button.addEventListener('click', () => {
                    if (button.classList.contains('btn-primary')) {
                        button.classList.remove('btn-primary');
                        button.classList.add('btn-outline-primary');
                    } else {
                        button.classList.remove('btn-outline-primary');
                        button.classList.add('btn-primary');
                    }
                });
            });
        }
    </script>
    <!-- Required Js -->
    @yield('js')
    <!-- Close Js -->
</body>

</html>
