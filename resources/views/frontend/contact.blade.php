<x-frontend.layout>

    <!-- CONTACT SECTION -->
    <section class="section section-contact"
        style="background: url({{ asset('frontend/assets/img/bg-product-buku.png') }})">
        <div class="container">
            <div class="row g-5">
                <!-- Left Column: Contact Info & Map -->
                <div class="col-lg-6">
                    <div class="contact-info">
                        <h1>
                            {{ languageText($config->contact_title, $config->contact_judul) }}
                        </h1>
                        <p class="contact-description">
                            {{ languageText($config->contact_description, $config->contact_deskripsi) }}
                        </p>

                        <div class="contact-details row">
                            @if ($config && $config->address)
                                <div class="contact-item col-md-6">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>{{ $config->address }}</span>
                                </div>
                            @endif
                            @if ($config && $config->notelp)
                                <div class="contact-item col-md-6">
                                    <i class="bi bi-telephone-fill"></i>
                                    <span>{{ $config->notelp }}</span>
                                </div>
                            @endif
                            @if ($config && $config->email)
                                <div class="contact-item col-md-6">
                                    <i class="bi bi-envelope-fill"></i>
                                    <span>{{ $config->email }}</span>
                                </div> 
                            @endif
                            @if ($config && $config->wa)
                                <div class="contact-item col-md-6">
                                    <i class="bi bi-whatsapp"></i>
                                    <span>{{ $config->wa }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Map Section -->
                        <div class="contact-map-container">
                            @if ($config && $config->map)
                                {!! $config->map !!}
                            @else
                                <div class="contact-map" id="contactMap"></div>
                                <div class="branch-card-overlay" id="branchCardOverlay">
                                    <h5>{{ languageText('Head Office', 'Kantor Pusat') }}</h5>
                                    @if ($config && $config->address)
                                        <p class="branch-address">{{ $config->address }}</p>
                                    @endif
                                    @if ($config && $config->notelp)
                                        <p class="branch-phone">{{ languageText('Tel', 'Telp') }}:
                                            {{ $config->notelp }}</p>
                                    @endif
                                    @if ($config && $config->email)
                                        <p class="branch-email">{{ languageText('Email', 'Email') }}:
                                            {{ $config->email }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Contact Form -->
                <div class="col-lg-6">
                    <div class="contact-form-wrapper">
                        <form class="contact-form" id="contactForm" method="POST"
                            action="{{ route('submitContact') }}">
                            @csrf
                            <div class="form-group">
                                <label for="fullName">{{ languageText('Full Name', 'Nama Lengkap') }}</label>
                                <input type="text" class="form-control" id="fullName" name="fullName" required />
                            </div>

                            <div class="form-group">
                                <label for="email">{{ languageText('Email Address', 'Alamat Email') }}</label>
                                <input type="email" class="form-control" id="email" name="email" required />
                            </div>

                            <div class="form-group">
                                <label for="phone">{{ languageText('Phone Number', 'Nomor Telpon') }}</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required />
                            </div>

                            <div class="form-group">
                                <label for="topic">{{ languageText('Message Topic', 'Topik Pesan') }}</label>
                                <select class="form-control" id="topic" name="topic" required>
                                    <option value="">{{ languageText('Select Topic', 'Pilih Topik') }}</option>
                                    <option value="pertanyaan">
                                        {{ languageText('General Question', 'Pertanyaan Umum') }}</option>
                                    <option value="produk">{{ languageText('Product Question', 'Pertanyaan Produk') }}
                                    </option>
                                    <option value="pemesanan">{{ languageText('Order', 'Pemesanan') }}</option>
                                    <option value="kerjasama">{{ languageText('Partnership', 'Kerjasama') }}</option>
                                    <option value="lainnya">{{ languageText('Other', 'Lainnya') }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="message">{{ languageText('Message', 'Isi Pesan') }}</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>

                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                @error('g-recaptcha-response')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div id="contactFormMessage" class="alert" style="display: none;"></div>

                            <button type="submit" class="btn btn-primary btn-block" id="submitBtn">
                                {{ languageText('Send Message', 'Kirim Pesan') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .contact-map-container iframe {
            width: 100% !important;
            height: 400px;
            border-radius: 30px;
        }
    </style>
    @if (!$config || !$config->map)
        <x-slot name="css">

            <!-- Leaflet CSS -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
                integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        </x-slot>
        <x-slot name="js">
            <!-- Google reCAPTCHA Script -->
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>

            <!-- Leaflet JS -->
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
                integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Initialize Leaflet map focused on Indonesia
                    var map = L.map("contactMap", {
                        center: [-2.5, 118.0], // Center of Indonesia
                        zoom: 5,
                        minZoom: 4,
                        maxZoom: 8,
                        maxBounds: [
                            [-11.0, 95.0], // Southwest corner
                            [6.0, 141.0], // Northeast corner
                        ],
                    });

                    // Add tile layer
                    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        maxZoom: 8,
                    }).addTo(map);

                    // Jakarta branch location
                    var jakartaBranch = {
                        name: "{{ languageText('Head Office', 'Kantor Pusat') }}",
                        address: "{{ $config->address ?? 'Jakarta, Indonesia' }}",
                        phone: "{{ $config->notelp ?? '' }}",
                        email: "{{ $config->email ?? '' }}",
                        lat: -6.2088,
                        lng: 106.8456,
                    };

                    // Custom red icon for marker
                    var redIcon = L.icon({
                        iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png",
                        shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png",
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41],
                    });

                    // Add marker for Jakarta branch
                    var marker = L.marker([jakartaBranch.lat, jakartaBranch.lng], {
                        icon: redIcon,
                    }).addTo(map);

                    // Add popup to marker
                    marker.bindPopup(
                        '<div style="text-align: center;"><strong>' +
                        jakartaBranch.name +
                        "</strong><br>" +
                        jakartaBranch.address +
                        "</div>"
                    );

                    // Center map on Jakarta branch
                    map.setView([jakartaBranch.lat, jakartaBranch.lng], 6);
                });

                // Contact form submission
                document
                    .getElementById("contactForm")
                    .addEventListener("submit", function(e) {
                        e.preventDefault();

                        const form = this;
                        const submitBtn = document.getElementById('submitBtn');
                        const messageDiv = document.getElementById('contactFormMessage');

                        // Check if reCAPTCHA is completed
                        const recaptchaResponse = grecaptcha.getResponse();
                        if (!recaptchaResponse) {
                            messageDiv.className = 'alert alert-danger';
                            messageDiv.textContent =
                                "{{ languageText('Please complete the reCAPTCHA verification.', 'Mohon selesaikan verifikasi reCAPTCHA.') }}";
                            messageDiv.style.display = 'block';
                            return;
                        }

                        const formData = new FormData(form);

                        // Disable submit button
                        submitBtn.disabled = true;
                        submitBtn.textContent = "{{ languageText('Sending...', 'Mengirim...') }}";
                        messageDiv.style.display = 'none';

                        fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    messageDiv.className = 'alert alert-success';
                                    messageDiv.textContent = data.message;
                                    messageDiv.style.display = 'block';
                                    form.reset();
                                    // Reset reCAPTCHA and reload page
                                    if (typeof grecaptcha !== 'undefined') {
                                        grecaptcha.reset();
                                    }
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 2000);
                                } else {
                                    messageDiv.className = 'alert alert-danger';
                                    messageDiv.textContent = data.message;
                                    messageDiv.style.display = 'block';
                                    submitBtn.disabled = false;
                                    submitBtn.textContent = "{{ languageText('Send Message', 'Kirim Pesan') }}";
                                    // Reset reCAPTCHA on error
                                    if (typeof grecaptcha !== 'undefined') {
                                        grecaptcha.reset();
                                    }
                                }
                            })
                            .catch(error => {
                                messageDiv.className = 'alert alert-danger';
                                messageDiv.textContent =
                                    "{{ languageText('An error occurred. Please try again.', 'Terjadi kesalahan. Silakan coba lagi.') }}";
                                messageDiv.style.display = 'block';
                                submitBtn.disabled = false;
                                submitBtn.textContent = "{{ languageText('Send Message', 'Kirim Pesan') }}";
                                // Reset reCAPTCHA on error
                                if (typeof grecaptcha !== 'undefined') {
                                    grecaptcha.reset();
                                }
                            });
                    });
            </script>
        </x-slot>
    @else
        <x-slot name="js">
            <!-- Google reCAPTCHA Script -->
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>

            <script>
                // Contact form submission
                document
                    .getElementById("contactForm")
                    .addEventListener("submit", function(e) {
                        e.preventDefault();

                        const form = this;
                        const submitBtn = document.getElementById('submitBtn');
                        const messageDiv = document.getElementById('contactFormMessage');

                        // Check if reCAPTCHA is completed
                        const recaptchaResponse = grecaptcha.getResponse();
                        if (!recaptchaResponse) {
                            messageDiv.className = 'alert alert-danger';
                            messageDiv.textContent =
                                "{{ languageText('Please complete the reCAPTCHA verification.', 'Mohon selesaikan verifikasi reCAPTCHA.') }}";
                            messageDiv.style.display = 'block';
                            return;
                        }

                        const formData = new FormData(form);

                        // Disable submit button
                        submitBtn.disabled = true;
                        submitBtn.textContent = "{{ languageText('Sending...', 'Mengirim...') }}";
                        messageDiv.style.display = 'none';

                        fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    messageDiv.className = 'alert alert-success';
                                    messageDiv.textContent = data.message;
                                    messageDiv.style.display = 'block';
                                    form.reset();
                                    // Reset reCAPTCHA and reload page
                                    if (typeof grecaptcha !== 'undefined') {
                                        grecaptcha.reset();
                                    }
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 2000);
                                } else {
                                    messageDiv.className = 'alert alert-danger';
                                    messageDiv.textContent = data.message;
                                    messageDiv.style.display = 'block';
                                    submitBtn.disabled = false;
                                    submitBtn.textContent = "{{ languageText('Send Message', 'Kirim Pesan') }}";
                                    // Reset reCAPTCHA on error
                                    if (typeof grecaptcha !== 'undefined') {
                                        grecaptcha.reset();
                                    }
                                }
                            })
                            .catch(error => {
                                messageDiv.className = 'alert alert-danger';
                                messageDiv.textContent =
                                    "{{ languageText('An error occurred. Please try again.', 'Terjadi kesalahan. Silakan coba lagi.') }}";
                                messageDiv.style.display = 'block';
                                submitBtn.disabled = false;
                                submitBtn.textContent = "{{ languageText('Send Message', 'Kirim Pesan') }}";
                                // Reset reCAPTCHA on error
                                if (typeof grecaptcha !== 'undefined') {
                                    grecaptcha.reset();
                                }
                            });
                    });
            </script>
        </x-slot>
    @endif
</x-frontend.layout>
