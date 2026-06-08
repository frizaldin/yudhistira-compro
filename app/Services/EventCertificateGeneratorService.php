<?php

namespace App\Services;

use App\Models\Configuration;
use App\Models\EventTeacherHub;
use App\Models\Teacher;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use RuntimeException;

class EventCertificateGeneratorService
{
    protected ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Generate gambar sertifikat (PNG) untuk event + guru.
     * Mengembalikan binary string PNG.
     *
     * @throws RuntimeException
     */
    public function generatePng(EventTeacherHub $event, Teacher $teacher): string
    {
        $templatePath = config('certificate.template_path');
        $fontPath = config('certificate.font_path');

        if (! File::isFile($templatePath)) {
            throw new RuntimeException(
                'Template sertifikat tidak ditemukan. Letakkan file di: ' . ($templatePath ?: 'public/images/certificate-event-template.png')
            );
        }

        if (! File::isFile($fontPath)) {
            throw new RuntimeException(
                'Font sertifikat tidak ditemukan. Letakkan file .ttf di: ' . ($fontPath ?: 'storage/app/fonts/OpenSans-Regular.ttf')
            );
        }

        $image = $this->imageManager->read($templatePath);
        $w = $image->width();
        $h = $image->height();
        $centerX = (int) ($w / 2);
        $fontFile = $fontPath;

        $eventTitle = $event->title ?: $event->judul ?: 'Event Teacher Hub';
        $teacherName = $teacher->name ?: 'Peserta';
        $congratulations = config('certificate.congratulations_text', 'Selamat! Anda telah menyelesaikan kuis event ini.');

        // Nama event (atas, lebih kecil)
        $image->text($eventTitle, $centerX, (int) ($h * 0.32), function ($font) use ($fontFile) {
            $font->file($fontFile);
            $font->size(28);
            $font->color('#1e3a5f');
            $font->align('center');
            $font->valign('middle');
        });

        // Nama user (tengah, besar)
        $image->text($teacherName, $centerX, (int) ($h * 0.48), function ($font) use ($fontFile) {
            $font->file($fontFile);
            $font->size(42);
            $font->color('#1e3a5f');
            $font->align('center');
            $font->valign('middle');
        });

        // Ucapan selamat (bawah)
        $image->text($congratulations, $centerX, (int) ($h * 0.62), function ($font) use ($fontFile) {
            $font->file($fontFile);
            $font->size(20);
            $font->color('#4b5563');
            $font->align('center');
            $font->valign('middle');
        });

        return $image->toPng()->toString();
    }

    /**
     * Ambil HTML template dari field certificate_temp di tabel configurations.
     * Placeholder yang didukung:
     *   {{name}}        → nama guru
     *   {{event_title}} → judul event
     *   {{event_date}}  → tanggal event
     *   {{date}}        → tanggal hari ini (generate)
     */
    public function getHtmlTemplate(EventTeacherHub $event, Teacher $teacher): ?string
    {
        $config = Configuration::first();
        $template = $config?->certificate_temp ?? null;

        if (empty($template)) {
            return null;
        }

        $eventTitle = $event->title ?: $event->judul ?: 'Event Teacher Hub';
        $teacherName = $teacher->name ?: 'Peserta';
        $eventDate = $event->date
            ? \Carbon\Carbon::parse($event->date)->locale('id')->isoFormat('D MMMM Y')
            : '-';
        $today = \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y');

        return str_replace(
            ['{{name}}', '{{event_title}}', '{{event_date}}', '{{date}}'],
            [$teacherName, $eventTitle, $eventDate, $today],
            $template
        );
    }

    /**
     * Generate sertifikat PDF.
     *
     * Prioritas:
     *  1. Jika `certificate_temp` di Configuration berisi HTML → render langsung sebagai PDF.
     *  2. Jika tidak → fallback ke generate dari template gambar PNG (cara lama).
     *
     * Mengembalikan binary string PDF.
     *
     * @throws RuntimeException
     */
    public function generatePdf(EventTeacherHub $event, Teacher $teacher): string
    {
        // Coba HTML template dari DB terlebih dahulu
        $html = $this->getHtmlTemplate($event, $teacher);

        if ($html !== null) {
            $pdf = app('dompdf.wrapper');
            return $pdf->loadHTML($html)
                ->setPaper('a4', 'landscape')
                ->output();
        }

        // Fallback: generate dari template gambar PNG
        $png = $this->generatePng($event, $teacher);
        $base64 = base64_encode($png);

        $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><style>body{margin:0;padding:0;} img{width:100%;height:100%;object-fit:contain;display:block;}</style></head><body><img src="data:image/png;base64,' . $base64 . '" /></body></html>';

        $pdf = app('dompdf.wrapper');
        return $pdf->loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->output();
    }
}
