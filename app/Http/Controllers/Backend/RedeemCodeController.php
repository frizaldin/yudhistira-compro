<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RedeemCode;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RedeemCodeController extends Controller
{
    public function index()
    {
        $data['collection'] = RedeemCode::when(request('search'), function ($query, $search) {
            return $query->where('book_title', 'like', '%' . $search . '%')
                ->orWhere('serial_code', 'like', '%' . $search . '%');
        })->orderBy('created_at', 'desc')->paginate(20);
        return view('backend.serial_code_ebook.index', $data);
    }

    public function add()
    {
        return view('backend.serial_code_ebook.add');
    }

    public function store(Request $req)
    {
        try {
            RedeemCode::create([
                'book_title' => $req->book_title ?? '',
                'serial_code' => $req->serial_code ?? '',
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/serial-code-ebook'),
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = RedeemCode::find($id);
        return view('backend.serial_code_ebook.edit', $data);
    }

    public function update(Request $req)
    {
        try {
            $data = RedeemCode::find($req->id);
            $data->update([
                'book_title' => $req->book_title ?? $data->book_title,
                'serial_code' => $req->serial_code ?? $data->serial_code,
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/serial-code-ebook'),
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function delete(Request $req)
    {
        try {
            $data = RedeemCode::find($req->id);
            $data->delete();
            return [
                'code' => 200,
                'success' => true,
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function importView()
    {
        return view('backend.serial_code_ebook.import');
    }

    public function import(Request $req)
    {
        try {
            if (!$req->hasFile('file')) {
                return ['code' => 422, 'success' => false, 'message' => 'File tidak ditemukan.'];
            }

            $file      = $req->file('file');
            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($extension, ['csv', 'xlsx'])) {
                return ['code' => 422, 'success' => false, 'message' => 'Format file harus CSV atau XLSX.'];
            }

            $path = $file->getRealPath();
            $rows = $extension === 'xlsx'
                ? $this->parseXlsx($path)
                : $this->parseCsv($path);

            $imported = 0;
            $skipped  = 0;
            $now      = now();

            foreach ($rows as $index => $row) {
                if ($index === 0) {
                    continue;
                }

                if (count($row) < 2) {
                    $skipped++;
                    continue;
                }

                $bookTitle  = trim($row[0] ?? '');
                $serialCode = trim($row[1] ?? '');
                $createdRaw = trim($row[2] ?? '');

                if (empty($serialCode)) {
                    $skipped++;
                    continue;
                }

                $createdAt = $createdRaw !== '' ? Carbon::parse($createdRaw) : $now;

                DB::table('redeem_codes')->insert([
                    'book_title'  => $bookTitle,
                    'serial_code' => $serialCode,
                    'created_at'  => $createdAt,
                    'updated_at'  => $now,
                ]);
                $imported++;
            }

            $message = "Berhasil mengimport {$imported} data.";
            if ($skipped > 0) {
                $message .= " {$skipped} baris dilewati karena tidak valid.";
            }

            return [
                'code'    => 200,
                'success' => true,
                'message' => $message,
                'url'     => url('backend/serial-code-ebook'),
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    private function parseCsv(string $path): array
    {
        $rows   = [];
        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new \Exception('Gagal membaca file CSV.');
        }
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $rows[] = $row;
        }
        fclose($handle);
        return $rows;
    }

    private function parseXlsx(string $path): array
    {
        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            throw new \Exception('Gagal membuka file XLSX.');
        }

        // --- Shared strings ---
        $sharedStrings = [];
        $ssContent     = $zip->getFromName('xl/sharedStrings.xml');
        if ($ssContent) {
            $ssXml = simplexml_load_string($ssContent);
            foreach ($ssXml->si as $si) {
                if (isset($si->t)) {
                    $sharedStrings[] = (string) $si->t;
                } else {
                    $text = '';
                    foreach ($si->r as $r) {
                        $text .= (string) $r->t;
                    }
                    $sharedStrings[] = $text;
                }
            }
        }

        // --- Date style detection ---
        $dateStyleIndexes   = [];
        $builtinDateFormats = [14, 15, 16, 17, 18, 19, 20, 21, 22, 45, 46, 47];
        $stylesContent      = $zip->getFromName('xl/styles.xml');
        if ($stylesContent) {
            $stylesXml     = simplexml_load_string($stylesContent);
            $customDateFmt = [];
            if (isset($stylesXml->numFmts)) {
                foreach ($stylesXml->numFmts->numFmt as $fmt) {
                    $id   = (int) $fmt['numFmtId'];
                    $code = strtolower((string) $fmt['formatCode']);
                    if (preg_match('/[ymd]/', $code) && strpos($code, '"') === false) {
                        $customDateFmt[] = $id;
                    }
                }
            }
            if (isset($stylesXml->cellXfs)) {
                foreach ($stylesXml->cellXfs->xf as $idx => $xf) {
                    $numFmtId = (int) $xf['numFmtId'];
                    if (in_array($numFmtId, $builtinDateFormats) || in_array($numFmtId, $customDateFmt)) {
                        $dateStyleIndexes[] = (int) $idx;
                    }
                }
            }
        }

        // --- Sheet data ---
        $sheetContent = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if (!$sheetContent) {
            throw new \Exception('Tidak dapat membaca data sheet XLSX.');
        }

        $sheetXml = simplexml_load_string($sheetContent);
        $rows     = [];

        foreach ($sheetXml->sheetData->row as $row) {
            $rowData = [];
            foreach ($row->c as $cell) {
                $type       = (string) $cell['t'];
                $styleIndex = isset($cell['s']) ? (int) $cell['s'] : null;
                $ref        = (string) $cell['r'];
                preg_match('/^([A-Z]+)/', $ref, $m);
                $colIndex = $this->colLetterToIndex($m[1]);

                while (count($rowData) <= $colIndex) {
                    $rowData[] = '';
                }

                $value = isset($cell->v) ? (string) $cell->v : '';

                if ($type === 's') {
                    $value = $sharedStrings[(int) $value] ?? '';
                } elseif ($type === 'inlineStr') {
                    $value = isset($cell->is->t) ? (string) $cell->is->t : '';
                } elseif (
                    $type === '' &&
                    $styleIndex !== null &&
                    in_array($styleIndex, $dateStyleIndexes) &&
                    $value !== ''
                ) {
                    $serial    = (float) $value;
                    $days      = (int) $serial;
                    $fraction  = $serial - $days;
                    $timestamp = ($days - 25569) * 86400 + (int) round($fraction * 86400);
                    $value     = date('Y-m-d H:i:s', $timestamp);
                }

                $rowData[$colIndex] = $value;
            }
            $rows[] = $rowData;
        }

        return $rows;
    }

    private function colLetterToIndex(string $letters): int
    {
        $letters = strtoupper($letters);
        $index   = 0;
        for ($i = 0; $i < strlen($letters); $i++) {
            $index = $index * 26 + (ord($letters[$i]) - ord('A') + 1);
        }
        return $index - 1;
    }

    public function template()
    {
        $filename = 'template_serial_code.csv';
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Book Title', 'Serial Number', 'Created At']);
            fputcsv($handle, ['Informatika 3 SMA Kelas XII', 'FJ4U-QFPH-8SYX-WVIZ', '2026-05-07 13:48:34']);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
