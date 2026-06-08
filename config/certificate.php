<?php

return [
    /*
    | Template gambar sertifikat (landscape, area tengah untuk teks).
    | Letakkan file di public/images/ atau path lengkap.
    */
    'template_path' => env('CERTIFICATE_TEMPLATE_PATH', public_path('images/certificate-event-template.png')),

    /*
    | Font TrueType (.ttf) untuk teks di sertifikat.
    | Letakkan font di storage/app/fonts/ (mis. OpenSans-Regular.ttf).
    */
    'font_path' => env('CERTIFICATE_FONT_PATH', storage_path('app/fonts/OpenSans-Regular.ttf')),

    /*
    | Teks ucapan selamat di sertifikat.
    */
    'congratulations_text' => env('CERTIFICATE_CONGRATULATIONS', 'Selamat! Anda telah menyelesaikan kuis event ini.'),
];
