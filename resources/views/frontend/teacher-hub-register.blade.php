<x-frontend.layout>
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

                <!-- RIGHT COLUMN: REGISTRATION FORM -->
                <div class="col-lg-6">
                    <div class="teachers-hub-form-wrapper">
                        <h1 class="form-title">Selamat Datang di Teachers Hub</h1>
                        <p class="form-subtitle">
                            Dapatkan informasi khusus guru dan pelatihan dengan mendaftar
                            Teachers Hub Yudhistira!
                        </p>
                        <form class="teachers-hub-form" id="registerForm" method="post" action="{{ route('teacher.postRegister') }}">
                            @csrf
                            <div class="form-group">
                                <label for="fullName">Nama Lengkap</label>
                                <input type="text" class="form-control" id="fullName" name="fullName"
                                    placeholder="Masukan nama lengkap" value="{{ old('fullName') }}" required />
                            </div>

                            <div class="form-group">
                                <label for="domisili">Domisili</label>
                                <select class="form-control" id="domisili" name="domisili" required>
                                    <option value="">Pilih domisili</option>
                                    <option value="jakarta" {{ old('domisili') == 'jakarta' ? 'selected' : '' }}>Jakarta
                                    </option>
                                    <option value="bandung" {{ old('domisili') == 'bandung' ? 'selected' : '' }}>Bandung
                                    </option>
                                    <option value="surabaya" {{ old('domisili') == 'surabaya' ? 'selected' : '' }}>
                                        Surabaya</option>
                                    <option value="yogyakarta" {{ old('domisili') == 'yogyakarta' ? 'selected' : '' }}>
                                        Yogyakarta</option>
                                    <option value="medan" {{ old('domisili') == 'medan' ? 'selected' : '' }}>Medan
                                    </option>
                                    <option value="makassar" {{ old('domisili') == 'makassar' ? 'selected' : '' }}>
                                        Makassar</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Alamat Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Masukan alamat email" value="{{ old('email') }}" required />
                            </div>

                            <div class="form-group">
                                <label for="phone">Nomor Telpon</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    placeholder="+62 Masukan nomor telpon" value="{{ old('phone') }}" required />
                            </div>

                            <div class="form-group">
                                <label for="schoolName">Nama Sekolah</label>
                                <input type="text" class="form-control" id="schoolName" name="schoolName"
                                    placeholder="Masukan nama sekolah" value="{{ old('schoolName') }}" required />
                            </div>

                            <div class="form-group">
                                <label for="npsn">NPSN</label>
                                <input type="text" class="form-control" id="npsn" name="npsn" maxlength="100"
                                    placeholder="Nomor Pokok Sekolah Nasional" value="{{ old('npsn') }}" required />
                            </div>

                            <div class="form-group">
                                <label for="teachingField">Bidang Mengajar</label>
                                <select class="form-control" id="teachingField" name="teachingField" required>
                                    <option value="">Bidang Mengajar</option>
                                    <option value="matematika"
                                        {{ old('teachingField') == 'matematika' ? 'selected' : '' }}>Matematika
                                    </option>
                                    <option value="bahasa-indonesia"
                                        {{ old('teachingField') == 'bahasa-indonesia' ? 'selected' : '' }}>Bahasa
                                        Indonesia</option>
                                    <option value="bahasa-inggris"
                                        {{ old('teachingField') == 'bahasa-inggris' ? 'selected' : '' }}>Bahasa Inggris
                                    </option>
                                    <option value="ipa" {{ old('teachingField') == 'ipa' ? 'selected' : '' }}>IPA
                                    </option>
                                    <option value="ips" {{ old('teachingField') == 'ips' ? 'selected' : '' }}>IPS
                                    </option>
                                    <option value="pkn" {{ old('teachingField') == 'pkn' ? 'selected' : '' }}>PKN
                                    </option>
                                    <option value="agama" {{ old('teachingField') == 'agama' ? 'selected' : '' }}>
                                        Agama</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="gender">Jenis Kelamin</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="">Pilih jenis kelamin</option>
                                    <option value="laki-laki" {{ old('gender') == 'laki-laki' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="perempuan" {{ old('gender') == 'perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="birthDate">Tanggal Lahir</label>
                                <div class="date-input-wrapper">
                                    <input type="text" class="form-control" id="birthDate" name="birthDate"
                                        placeholder="dd/mm/yyyy" value="{{ old('birthDate') }}" required />
                                    <i class="bi bi-calendar3 date-icon"></i>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukan password" required />
                            </div>

                            <div class="form-group">
                                <label for="confirmPassword">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="confirmPassword"
                                    name="confirmPassword" placeholder="Masukan konfirmasi password" required />
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">
                                Daftar Akun
                            </button>

                            <p class="form-footer-text">
                                Sudah mempunyai akun?
                                <a href="{{ route('teacher.login') }}" class="login-link">Login Disini</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <x-slot name="js">
        <script>
            // Date picker initialization
            document.addEventListener('DOMContentLoaded', function() {
                // Simple date input mask
                const birthDateInput = document.getElementById('birthDate');
                if (birthDateInput) {
                    birthDateInput.addEventListener('input', function(e) {
                        let value = e.target.value.replace(/\D/g, '');
                        if (value.length >= 2) {
                            value = value.substring(0, 2) + '/' + value.substring(2);
                        }
                        if (value.length >= 5) {
                            value = value.substring(0, 5) + '/' + value.substring(5, 9);
                        }
                        e.target.value = value;
                    });
                }
            });
        </script>
    </x-slot>
</x-frontend.layout>
