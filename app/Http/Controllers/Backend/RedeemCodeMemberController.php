<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RedeemCode;
use App\Models\RedeemCodeMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class RedeemCodeMemberController extends Controller
{
    public function index()
    {
        $data['collection'] = RedeemCodeMember::when(request('search'), function ($query, $search) {
            return $query->where('code', 'like', '%' . $search . '%');
        })->orderBy('created_at', 'desc')->paginate(20);
        return view('backend.serial_code_member.index', $data);
    }

    private function getBooks(): array
    {
        try {
            $url      = rtrim(env('API_URL'), '/') . '/simple_products';
            $headers  = ['key' => env('API_KEY'), 'Origin' => 'buy.bukuyudhistira.id'];

            $response = Http::withHeaders($headers)
                ->withoutVerifying()
                ->timeout(20)
                ->get($url);

            if (!$response->successful()) {
                return [];
            }

            $body    = $response->json();
            $wrapper = $body['data'] ?? [];
            
            // The API response returns a list of products directly inside 'data'.
            // Handle both flat and nested pagination formats just in case.
            $raw     = isset($wrapper['data']) && is_array($wrapper['data']) ? $wrapper['data'] : $wrapper;
        } catch (\Throwable $e) {
            return [];
        }

        return collect($raw)->map(function ($book, $index) {
            return [
                'id'       => $book['id'] ?? $book['book_id'] ?? $book['product_id'] ?? ($index + 1),
                'title'    => $book['title'] ?? $book['nama'] ?? 'Buku #' . ($index + 1),
                'file_url' => $book['file_url'] ?? $book['file'] ?? '',
                'url'      => $book['url_goto_website'] ?? '',
                '_raw'     => $book,
            ];
        })->values()->toArray();
    }

    public function add()
    {
        $usedIds = RedeemCodeMember::pluck('serial_code_ebook')
            ->filter()
            ->flatten()
            ->unique()
            ->toArray();

        $data['redeemCodes'] = RedeemCode::whereNotIn('id', $usedIds)
            ->orderBy('book_title')
            ->get();
        $data['books']       = $this->getBooks();
        return view('backend.serial_code_member.add', $data);
    }

    public function store(Request $req)
    {
        // return $req;
        try {
            do {
                $code = strtoupper(Str::random(4)) . '-'
                    . strtoupper(Str::random(4)) . '-'
                    . strtoupper(Str::random(4)) . '-'
                    . strtoupper(Str::random(4));
            } while (RedeemCodeMember::where('code', $code)->exists());

            $bookId = (int) $req->book_id;

            if (!$bookId) {
                return ['code' => 422, 'success' => false, 'message' => 'Pilih buku terlebih dahulu.'];
            }

            $bookData = collect($this->getBooks())->first(fn($b) => (int)$b['id'] === $bookId);

            $selectedIds = $req->serial_code_ebook ? array_map('intval', (array) $req->serial_code_ebook) : [];

            RedeemCodeMember::create([
                'code'               => $code,
                'serial_code_ebook'  => $selectedIds ?: null,
                'book_id'            => $bookId,
                'book_json'          => $bookData ?: null,
                'used'               => 'no',
            ]);

            return [
                'code'    => 200,
                'success' => true,
                'url'     => url('backend/serial-code-member'),
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $item = RedeemCodeMember::find($id);

        $usedIds = RedeemCodeMember::where('id', '!=', $id)
            ->pluck('serial_code_ebook')
            ->filter()
            ->flatten()
            ->unique()
            ->toArray();

        $data['item']        = $item;
        $data['redeemCodes'] = RedeemCode::whereNotIn('id', $usedIds)
            ->orderBy('book_title')
            ->get();
        $data['books']       = $this->getBooks();
        return view('backend.serial_code_member.edit', $data);
    }

    public function update(Request $req)
    {
        try {
            $item = RedeemCodeMember::find($req->id);

            $selectedIds = $req->serial_code_ebook
                ? array_map('intval', (array) $req->serial_code_ebook)
                : [];

            $bookId = (int) $req->book_id;

            if (!$bookId) {
                return ['code' => 422, 'success' => false, 'message' => 'Pilih buku terlebih dahulu.'];
            }

            $bookData = collect($this->getBooks())->first(fn($b) => (int)$b['id'] === $bookId);

            $item->update([
                'serial_code_ebook' => $selectedIds ?: null,
                'book_id'           => $bookId,
                'book_json'         => $bookData ?: null,
                'used'              => $req->used ?? $item->used,
            ]);

            return [
                'code'    => 200,
                'success' => true,
                'url'     => url('backend/serial-code-member'),
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function delete(Request $req)
    {
        try {
            RedeemCodeMember::find($req->id)->delete();
            return ['code' => 200, 'success' => true];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}
