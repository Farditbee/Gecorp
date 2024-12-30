<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="GSS" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ env('APP_NAME') ?? 'GSS' }}</title>
    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- prism css -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">

    @include('layouts.css.style_css')
    <style>
        .loader {
            top: calc(50% - 32px);
            left: calc(50% - 32px);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            perspective: 800px;
        }

        .inner {
            position: absolute;
            box-sizing: border-box;
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }

        .inner.one {
            left: 0%;
            top: 0%;
            animation: rotate-one 1s linear infinite;
            border-bottom: 3px solid #1abc9c;
        }

        .inner.two {
            right: 0%;
            top: 0%;
            animation: rotate-two 1s linear infinite;
            border-right: 3px solid #1abc9c;
        }

        .inner.three {
            right: 0%;
            bottom: 0%;
            animation: rotate-three 1s linear infinite;
            border-top: 3px solid #1abc9c;
        }

        @keyframes rotate-one {
            0% {
                transform: rotateX(35deg) rotateY(-45deg) rotateZ(0deg);
            }

            100% {
                transform: rotateX(35deg) rotateY(-45deg) rotateZ(360deg);
            }
        }

        @keyframes rotate-two {
            0% {
                transform: rotateX(50deg) rotateY(10deg) rotateZ(0deg);
            }

            100% {
                transform: rotateX(50deg) rotateY(10deg) rotateZ(360deg);
            }
        }

        @keyframes rotate-three {
            0% {
                transform: rotateX(35deg) rotateY(55deg) rotateZ(0deg);
            }

            100% {
                transform: rotateX(35deg) rotateY(55deg) rotateZ(360deg);
            }
        }

        .alert-custom {
            background: linear-gradient(135deg, #004d3d, #066854, #0f8f75, #1ec7a5, #6bf1d7);
            color: #ffffff;
        }
    </style>
    @yield('css')
    <script>
        document.onreadystatechange = function() {
            var state = document.readyState;
            if (state == 'complete') {
                document.getElementById('load-screen').style.display = 'none';
                if (window.initPageLoad) {
                    initPageLoad();
                }
            }
        }
    </script>
</head>

<body>
    <div>
        <!-- [ navigation menu ] start -->
        @include('layouts.navbar')
        <!-- [ navigation menu ] end -->

        <!-- [ Header ] start -->
        @include('layouts.header')
        <!-- [ Header ] end -->

        <!-- [ Main Content ] start -->
        @yield('content')
        <!-- [ Main Content ] end -->

        @include('layouts.footer')
    </div>

    <!-- Warning Section Ends -->
    @include('layouts.js.style_js')
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/axios.js') }}"></script>
    <script src="{{ asset('js/restAPI.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/notification.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    @yield('asset_js')

    <script>
        function loadingPage(value) {
            if (value == true) {
                document.getElementById('load-screen').style.display = '';
            } else {
                document.getElementById('load-screen').style.display = 'none';
            }
            return;
        }

        function loadingData() {
            let html = `
            <tr class="text-dark loading-row">
                <td class="text-center" colspan="${$('.tb-head th').length}">
                    <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="162px" height="24px"
                        viewBox="0 0 128 19" xml:space="preserve"><rect x="0" y="0" width="100%" height="100%" fill="#FFFFFF" /><path fill="#1abc9c" d="M0.8,2.375H15.2v14.25H0.8V2.375Zm16,0H31.2v14.25H16.8V2.375Zm16,0H47.2v14.25H32.8V2.375Zm16,0H63.2v14.25H48.8V2.375Zm16,0H79.2v14.25H64.8V2.375Zm16,0H95.2v14.25H80.8V2.375Zm16,0h14.4v14.25H96.8V2.375Zm16,0h14.4v14.25H112.8V2.375Z"/><g><path fill="#c7efe7" d="M128.8,2.375h14.4v14.25H128.8V2.375Z"/><path fill="#c7efe7" d="M144.8,2.375h14.4v14.25H144.8V2.375Z"/><path fill="#9fe3d5" d="M160.8,2.375h14.4v14.25H160.8V2.375Z"/><path fill="#72d6c2" d="M176.8,2.375h14.4v14.25H176.8V2.375Z"/><animateTransform attributeName="transform" type="translate" values="0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;-16 0;-32 0;-48 0;-64 0;-80 0;-96 0;-112 0;-128 0;-144 0;-160 0;-176 0;-192 0" calcMode="discrete" dur="2160ms" repeatCount="indefinite"/></g><g><path fill="#c7efe7" d="M-15.2,2.375H-0.8v14.25H-15.2V2.375Z"/><path fill="#c7efe7" d="M-31.2,2.375h14.4v14.25H-31.2V2.375Z"/><path fill="#9fe3d5" d="M-47.2,2.375h14.4v14.25H-47.2V2.375Z"/><path fill="#72d6c2" d="M-63.2,2.375h14.4v14.25H-63.2V2.375Z"/><animateTransform attributeName="transform" type="translate" values="16 0;32 0;48 0;64 0;80 0;96 0;112 0;128 0;144 0;160 0;176 0;192 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0" calcMode="discrete" dur="2160ms" repeatCount="indefinite"/></g>
                    </svg>
                </td>
            </tr>`;

            return html;
        }

        function formatRupiah(value) {
            let number = parseFloat(value) || 0;
            let roundedNumber = Math.round(number);
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2,
            }).format(roundedNumber);
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

        async function selectMulti(optionsArray) {
            const auth_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            for (const {
                    id,
                    isUrl,
                    placeholder
                }
                of optionsArray) {
                let selectOption = {
                    ajax: {
                        url: isUrl,
                        dataType: 'json',
                        delay: 500,
                        headers: {
                            Authorization: `Bearer ` + auth_token
                        },
                        data: function(params) {
                            let query = {
                                search: params.term,
                                page: params.page || 1,
                                limit: 30,
                                ascending: 1,
                            };
                            return query;
                        },
                        processResults: function(res, params) {
                            let data = res.data;
                            let filteredData = $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.optional ? `${item.optional} / ${item.text}` : item.text
                                };
                            });
                            return {
                                results: filteredData,
                                pagination: {
                                    more: res.pagination && res.pagination.more
                                }
                            };
                        },
                    },
                    allowClear: true,
                    placeholder: placeholder,
                    multiple: true,
                };

                await $(id).select2(selectOption);
            }
        }
    </script>
    <!-- Required Js -->
    @yield('js')
    <!-- Close Js -->
</body>

</html>
