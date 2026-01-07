<x-frontend.layout>
    <!-- TEACHERS HUB LOGIN -->
    <section class="section section-teachers-hub"
        style="background-image: url({{ asset('frontend/assets/img/bg-teacher.jpg') }});background-size: cover;background-position: center;">
        <div class="container">
            <div class="row align-items-center g-5">
                <!-- LEFT COLUMN: ILLUSTRATION -->
                <div class="col-lg-6">
                    <div class="teachers-hub-illustration">
                        <div class="illustration-circle">
                            <img src="{{ asset('frontend/assets/img/login.png') }}" alt="Teacher with Laptop"
                                class="illustration-main" />
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: LOGIN FORM -->
                <div class="col-lg-6">
                    <div class="teachers-hub-form-wrapper">
                        <h1 class="form-title">Login Teachers Hub</h1>
                        <p class="form-subtitle">
                            Dapatkan informasi khusus guru dan pelatihan dengan mendaftar
                            Teachers Hub Yudhistira!
                        </p>
                        <form class="teachers-hub-form" id="loginForm" action="{{ route('teacher.postLogin') }}"
                            method="POST">
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="email">Alamat Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Masukan alamat email" value="{{ old('email') }}" required />
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukan password" required />
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">
                                Login
                            </button>

                            <p class="form-footer-text">
                                Belum mempunyai akun?
                                <a href="{{ route('teacher.register') }}" class="login-link">Daftar Disini</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-frontend.layout>
