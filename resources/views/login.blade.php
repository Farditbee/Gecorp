<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="author" content="GSS">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | {{ env('APP_NAME') ?? 'GSS' }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/login/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/login/aos.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/login/output.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/login/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/login/loading.css') }}" />
    <script>
        document.onreadystatechange = function() {
            var state = document.readyState;
            if (state == 'complete') {
                setTimeout(function() {
                    document.getElementById('preloaderLoadingPage').style.display = 'none';
                    const htmlElement = document.documentElement;
                    if (htmlElement.classList.contains('dark')) {
                        htmlElement.classList.remove('dark');
                        htmlElement.classList.add('light');
                    }
                }, 100);
            }
        }
    </script>
    <style>
        .loader {
            position: absolute;
            top: calc(50% - 32px);
            left: calc(50% - 32px);
            width: 64px;
            height: 64px;
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
    </style>
</head>

<body>
    <div id="preloaderLoadingPage">
        <div class="sk-three-bounce">
            <div class="centerpreloader">
                <div class="loader">
                    <div class="inner one"></div>
                    <div class="inner two"></div>
                    <div class="inner three"></div>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-white dark:bg-darkblack-500">
        <div class="flex flex-col lg:flex-row justify-between min-h-screen">
            <div class="lg:w-1/2 px-5 xl:pl-12 pt-10">
                <div class="max-w-[450px] m-auto pt-24 pb-16">
                    <header class="text-center mb-8">
                        <center>
                            <img src="{{ asset('images/logo/logo-slogan.png') }}" class="block dark:hidden"
                                style="width: 90%" />
                            <img src="{{ asset('images/logo/logo-slogan.png') }}" class="hidden dark:block"
                                style="width: 90%" />
                        </center>
                        <p class="font-urbanis text-base font-medium text-bgray-600 pt-2 dark:text-bgray-50">
                            <b>MASUK KE APLIKASI {{ env('APP_NAME') ?? 'GSS' }}</b>
                        </p>
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        @endif
                    </header>

                    <div class="mb-4">
                        <input type="text" id="username" autocomplete="off"
                            class="text-bgray-800 text-base border border-bgray-300 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white h-14 w-full focus:border-success-300 focus:ring-0 rounded-lg px-4 py-3.5 placeholder:text-bgray-500 placeholder:text-base"
                            placeholder="Username" />
                    </div>
                    <div class="mb-6 relative">
                        <input type="password" id="password"
                            class="text-bgray-800 text-base border border-bgray-300 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white h-14 w-full focus:border-success-300 focus:ring-0 rounded-lg px-4 py-3.5 placeholder:text-bgray-500 placeholder:text-base"
                            placeholder="Kata sandi" />
                        <button type="button" id="togglePassword" class="absolute top-4 right-4 bottom-4">
                            <svg id="eyeIcon" width="22" height="20" viewBox="0 0 22 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 1L20 19" stroke="#718096" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M9.58445 8.58704C9.20917 8.96205 8.99823 9.47079 8.99805 10.0013C8.99786 10.5319 9.20844 11.0408 9.58345 11.416C9.95847 11.7913 10.4672 12.0023 10.9977 12.0024C11.5283 12.0026 12.0372 11.7921 12.4125 11.417"
                                    stroke="#718096" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M8.363 3.36506C9.22042 3.11978 10.1082 2.9969 11 3.00006C15 3.00006 18.333 5.33306 21 10.0001C20.222 11.3611 19.388 12.5241 18.497 13.4881M16.357 15.3491C14.726 16.4491 12.942 17.0001 11 17.0001C7 17.0001 3.667 14.6671 1 10.0001C2.369 7.60506 3.913 5.82506 5.632 4.65906"
                                    stroke="#718096" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                    {{-- <div class="flex justify-between mb-7">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox"
                                class="w-5 h-5 dark:bg-darkblack-500 focus:ring-transparent rounded-full border border-bgray-300 focus:accent-success-300 text-success-300"
                                name="remember" id="remember" />
                            <label for="remember" class="text-bgray-900 dark:text-white text-base font-semibold">Ingat
                                saya</label>
                        </div>
                        <div>
                            <a href="#" data-target="#multi-step-modal" style="color: rgb(33 70 156);"
                                class="modal-open text-success-300 font-semibold text-base underline">Lupa kata
                                sandi?</a>
                        </div>
                    </div> --}}
                    <button type="button" onclick="submitLogin()" style="background-color: rgb(33 70 156);"
                        class="py-3.5 flex items-center justify-center text-white font-bold bg-success-300 hover:bg-success-400 transition-all rounded-lg w-full">
                        Masuk
                    </button>
                    <p class="text-bgray-600 dark:text-white text-center text-sm mt-6">
                        &copy; {{ now()->year }} {{ env('APP_NAME') ?? 'GSS' }}<br>All Right Reserved
                    </p>
                </div>
            </div>
            <div class="lg:w-1/2 lg:block hidden bg-[#F6FAFF] dark:bg-darkblack-600 p-20 relative">
                <ul>
                    <li class="absolute top-10 left-8">
                        <img src="{{ asset('images/shapes/vline.svg') }}" alt="" />
                    </li>
                    <li class="absolute right-12 top-14">
                        <img src="{{ asset('images/shapes/square.svg') }}" alt="" />
                    </li>
                    <li class="absolute bottom-7 left-8">
                        <img src="{{ asset('images/shapes/dotted.svg') }}" alt="" />
                    </li>
                </ul>
                <div>
                    <img src="{{ asset('images/shapes/login.svg') }}" />
                </div>
                <div>
                    <div class="text-center max-w-lg px-1.5 m-auto">
                        <h3 class="text-bgray-900 dark:text-white font-semibold font-popins text-4xl mb-4">
                            Transaksi, Pengiriman dan Penjualan
                        </h3>
                        <p class="text-bgray-600 dark:text-bgray-50 text-sm font-medium">
                            Aplikasi yang dibangun untuk mencatat transaksi, melakukan pengiriman dan penjualan, serta
                            menyajikan laporan pendapatan yang terperinci.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--scripts -->

    <script src="{{ asset('js/jquery.js') }}"></script>

    <script src="{{ asset('js/login/aos.js') }}"></script>
    <script src="{{ asset('js/login/slick.min.js') }}"></script>
    <script>
        AOS.init();
    </script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/axios.js') }}"></script>
    <script src="{{ asset('js/restAPI.js') }}"></script>

    <script>
        document.querySelectorAll('#username, #password').forEach(input => {
            input.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    submitLogin();
                }
            });
        });

        const passwordInput = document.getElementById('password');
        const togglePasswordButton = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePasswordButton.addEventListener('click', () => {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

            if (isPassword) {
                eyeIcon.innerHTML = `<path
                d="M8.363 3.36506C9.22042 3.11978 10.1082 2.9969 11 3.00006C15 3.00006 18.333 5.33306 21 10.0001C20.222 11.3611 19.388 12.5241 18.497 13.4881M16.357 15.3491C14.726 16.4491 12.942 17.0001 11 17.0001C7 17.0001 3.667 14.6671 1 10.0001C2.369 7.60506 3.913 5.82506 5.632 4.65906"
                stroke="#718096" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round" />
            `;
            } else {
                eyeIcon.innerHTML = `<path d="M2 1L20 19" stroke="#718096" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M9.58445 8.58704C9.20917 8.96205 8.99823 9.47079 8.99805 10.0013C8.99786 10.5319 9.20844 11.0408 9.58345 11.416C9.95847 11.7913 10.4672 12.0023 10.9977 12.0024C11.5283 12.0026 12.0372 11.7921 12.4125 11.417"
                                    stroke="#718096" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M8.363 3.36506C9.22042 3.11978 10.1082 2.9969 11 3.00006C15 3.00006 18.333 5.33306 21 10.0001C20.222 11.3611 19.388 12.5241 18.497 13.4881M16.357 15.3491C14.726 16.4491 12.942 17.0001 11 17.0001C7 17.0001 3.667 14.6671 1 10.0001C2.369 7.60506 3.913 5.82506 5.632 4.65906"
                                    stroke="#718096" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />`;
            }
        });

        function loadingPage(show) {
            if (show) {
                document.getElementById('preloaderLoadingPage').style.display = '';
            } else {
                document.getElementById('preloaderLoadingPage').style.display = 'none';
            }
        }

        function notificationAlert(type, title, message) {
            swal(
                title,
                message,
                type
            );
        }

        async function submitLogin() {
            loadingPage(true);
            let getDataRest = await renderAPI(
                'POST',
                '{{ route('post_login') }}', {
                    username: $('#username').val(),
                    password: $('#password').val()
                }
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                loadingPage(false);
                let resp = error.response;
                notificationAlert('error', 'Error', resp.data.message);
                return resp;
            });
            if (getDataRest.status == 200) {
                let rest_data = getDataRest.data.data;
                setTimeout(function() {
                    window.location.href = rest_data.route_redirect
                }, 500);
            }
        }
    </script>
</body>

</html>
