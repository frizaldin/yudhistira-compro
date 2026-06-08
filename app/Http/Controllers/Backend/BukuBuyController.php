<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProductDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class BukuBuyController extends Controller
{
    private function apiUrl(): string
    {
        return rtrim(env('API_URL'), '/') . '/products';
    }

    private function apiHeaders(): array
    {
        return ['key' => env('API_KEY'), 'Origin' => 'buy.bukuyudhistira.id'];
    }

    /**
     * GET /backend/buku-buy
     */
    public function index(Request $request)
    {
        $query    = $request->only(['search', 'page']);
        $products = [];
        $meta     = null;
        $error    = null;

        try {
            $response = Http::withHeaders($this->apiHeaders())
                ->withoutVerifying()
                ->timeout(20)
                ->get($this->apiUrl(), $query);

            if ($response->successful()) {
                $body     = $response->json();
                $wrapper  = $body['data']    ?? [];
                $products = $wrapper['data'] ?? [];
                $meta     = $wrapper;
            } else {
                $error = 'API merespons dengan status HTTP ' . $response->status() . '.';
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $error = 'Koneksi ke server BuyBuku gagal: ' . $e->getMessage();
        } catch (\Throwable $e) {
            $error = 'Terjadi kesalahan: ' . $e->getMessage();
        }

        return view('backend.buku_buy.index', compact('products', 'meta', 'error'));
    }

    /**
     * GET /backend/buku-buy/documents/{productId}
     * Ambil daftar dokumen untuk satu produk (JSON, dipanggil via AJAX).
     */
    public function documents(int $productId)
    {
        $docs = ProductDocument::where('product_id', $productId)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($doc) {
                return [
                    'id'         => $doc->id,
                    'type'       => $doc->type,
                    'attachment' => $doc->type === 'file'
                        ? asset('storage/' . $doc->attachment)
                        : $doc->attachment,
                    'filename'   => $doc->type === 'file'
                        ? basename($doc->attachment)
                        : null,
                    'created_at' => $doc->created_at->format('d/m/Y H:i'),
                ];
            });

        return response()->json(['data' => $docs]);
    }

    /**
     * POST /backend/buku-buy/documents
     * Simpan dokumen baru.
     */
    public function storeDocument(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|integer',
            'type'         => 'required|in:file,link',
            'link'         => 'required_if:type,link|nullable|url|max:2000',
            'file'         => 'required_if:type,file|nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip|max:20480',
            'json_product' => 'nullable|string',
        ]);

        $attachment = null;

        if ($request->type === 'file') {
            $path = $request->file('file')->store('product_documents', 'public');
            $attachment = $path;
        } else {
            $attachment = $request->link;
        }

        ProductDocument::create([
            'product_id'   => $request->product_id,
            'type'         => $request->type,
            'attachment'   => $attachment,
            'json_product' => $request->json_product,
        ]);

        return response()->json(['success' => true, 'message' => 'Dokumen berhasil disimpan.']);
    }

    /**
     * DELETE /backend/buku-buy/documents/{id}
     * Hapus dokumen.
     */
    public function destroyDocument(int $id)
    {
        $doc = ProductDocument::findOrFail($id);

        if ($doc->type === 'file' && Storage::disk('public')->exists($doc->attachment)) {
            Storage::disk('public')->delete($doc->attachment);
        }

        $doc->delete();

        return response()->json(['success' => true, 'message' => 'Dokumen berhasil dihapus.']);
    }
}
