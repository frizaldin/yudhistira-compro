<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="dark" data-bs-theme="light">

<head>


    <meta charset="utf-8" />
    <title>Dashboard | Yudhistira</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Yudhistira" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('/logo-aneka.png') }}">



    <!-- App css -->
    <link href="{{ asset('/') }}backend/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}backend/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}backend/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/all.css') }}?v={{ strtotime(now()) }}">

    @stack('css')
    @if (isset($css))
        {{ $css }}
    @endif

    <style>
        .startbar .startbar-menu .menu-icon {
            margin-right: 10px;
        }

        .startbar .brand .logo-lg {
            -webkit-transition: .3s;
            transition: .3s;
            margin-left: 10px;
            height: 55px;
            margin-top: 15px;
        }

        .startbar .brand .logo-sm {
            height: 35px;
            margin-top: 20px;
        }

        .slick-list {
            width: 100% !important;
            height: 100% !important;
            min-height: 400px
        }

        .slick-initialized .slick-slide {
            width: 100% !important;
        }

        .slick-list {
            width: 100% !important;
            min-height: 400px;
        }
    </style>
</head>

<body>

    <x-backend.nav.navbar.navbar />
    <x-backend.nav.sidebar.div />

    <div class="startbar-overlay d-print-none"></div>
    <!-- end leftbar-tab-menu-->

    <div class="page-wrapper">

        <!-- Page Content-->
        <div class="page-content" style="    max-width: 100%;overflow: hidden;">
            <div class="container-fluid">
                {{ $slot }}
            </div><!-- container -->

            <!--Start Rightbar-->
            <!--Start Rightbar/offcanvas-->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="Appearance" aria-labelledby="AppearanceLabel">
                <div class="offcanvas-header border-bottom justify-content-between">
                    <h5 class="m-0 font-14" id="AppearanceLabel">Appearance</h5>
                    <button type="button" class="btn-close text-reset p-0 m-0 align-self-center"
                        data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <h6>Account Settings</h6>
                    <div class="p-2 text-start mt-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="settings-switch1">
                            <label class="form-check-label" for="settings-switch1">Auto updates</label>
                        </div><!--end form-switch-->
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="settings-switch2" checked>
                            <label class="form-check-label" for="settings-switch2">Location Permission</label>
                        </div><!--end form-switch-->
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="settings-switch3">
                            <label class="form-check-label" for="settings-switch3">Show offline Contacts</label>
                        </div><!--end form-switch-->
                    </div><!--end /div-->
                    <h6>General Settings</h6>
                    <div class="p-2 text-start mt-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="settings-switch4">
                            <label class="form-check-label" for="settings-switch4">Show me Online</label>
                        </div><!--end form-switch-->
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="settings-switch5" checked>
                            <label class="form-check-label" for="settings-switch5">Status visible to all</label>
                        </div><!--end form-switch-->
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="settings-switch6">
                            <label class="form-check-label" for="settings-switch6">Notifications Popup</label>
                        </div><!--end form-switch-->
                    </div><!--end /div-->
                </div><!--end offcanvas-body-->
            </div>
            <!--end Rightbar/offcanvas-->
            <!--end Rightbar-->
            <!--Start Footer-->

            <x-backend.nav.footer.footer />

            <!--end footer-->
        </div>
        <!-- end page content -->
    </div>
    <!-- end page-wrapper -->

    <!-- Javascript  -->
    <!-- vendor js -->

    <script src="{{ asset('/') }}backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('/') }}backend/assets/libs/simplebar/simplebar.min.js"></script>

    <script src="{{ asset('/') }}backend/assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="https://apexcharts.com/samples/assets/stock-prices.js"></script>
    <script src="{{ asset('/') }}backend/assets/js/pages/index.init.js"></script>
    <script src="{{ asset('/') }}backend/assets/js/DynamicSelect.js"></script>
    <script src="{{ asset('/') }}backend/assets/js/app.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/ldrs/dist/auto/tailChase.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="module" src="https://cdn.jsdelivr.net/npm/ldrs/dist/auto/hatch.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script src="{{ asset('js/all.js') }}"></script>
    @if (auth()->check() && auth()->user()->authority_id == 2 && request()->route()->getName() !== 'profile')
        @php
            $userData = $user_data;
            $missingBankInfo = empty($userData->bank_name) || empty($userData->account_number);
        @endphp
        @if ($missingBankInfo)
            <script>
                $(document).ready(function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Lengkapi Data Diri',
                        text: 'Silakan lengkapi data Anda untuk melanjutkan.',
                        confirmButtonText: 'Isi Sekarang'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ url('profile') }}";
                        }
                    });
                });
            </script>
        @endif
    @endif

    @if (request()->has('focus'))
        <script>
            $(document).ready(function() {
                const focusName = "{{ request('focus') }}";
                const focusElement = document.querySelector(`[name="${focusName}"]`);
                if (focusElement) {
                    focusElement.focus();

                    // Trik buat geser kursor ke paling kanan
                    const val = focusElement.value;
                    focusElement.value = '';
                    focusElement.value = val;
                }
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            setTimeout(() => {
                // Cari ul sebelum #loader-sidebar
                $('#sidebar-menu').fadeIn(1000); // Lebih lambat muncul

                // Hapus #loader-sidebar
                $('#loader-sidebar').remove();
            }, 1000);

            let debounceTimer;

            $(".form-filter").on("blur", function() {
                const url = new URL(window.location.href);
                url.searchParams.delete('focus');
                window.history.replaceState({}, '', url);
            });

            $(".form-filter").on("input", function() {
                const $this = $(this);
                const search = $this.val();
                const attrname = $this.attr('name');

                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(function() {
                    const url = new URL(window.location.href);

                    if (search.trim() !== "") {
                        url.searchParams.set(attrname, search);
                        url.searchParams.set('focus', attrname);
                    } else {
                        url.searchParams.delete(attrname);
                        url.searchParams.delete('focus');
                    }

                    window.history.replaceState({}, '', url);
                    location.reload();
                }, 500); // Bisa lo atur delay-nya
            });
        });
    </script>


    @stack('js')
    @if (isset($js))
        {{ $js }}
    @endif
</body>
<!--end body-->

</html>
