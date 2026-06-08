<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DigitalLearningSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DigitalLearningSupportController extends Controller
{
    public function index()
    {
        $data['collection'] = DigitalLearningSupport::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('judul', 'like', '%' . $search . '%');
        })->when(Auth::user()->authorities_id != 1, function ($query) {
            return $query->where('created_by', Auth::user()->id);
        })->orderBy('id', 'desc')->paginate(10);
        return view('backend.digital_learning_support.index', $data);
    }

    public function add()
    {
        return view('backend.digital_learning_support.add');
    }

    public function store(Request $req)
    {
        try {
            /**
             * VALIDATED
             */
            $req->validate([
                'title'      => 'required|string|max:150',
                'judul'      => 'nullable|string|max:150',
                'video_tipe' => ['required', Rule::in(['internal', 'external'])],
                'file'       => [
                    Rule::requiredIf($req->video_tipe === 'internal'),
                    'nullable',
                    'file',
                    'mimes:mp4,mkv,avi,mov,webm',
                    'max:512000', // 500MB
                ],
                'embed'      => [
                    Rule::requiredIf($req->video_tipe === 'external'),
                    'nullable',
                    'string',
                ],
            ]);
    
            /**
             * UPLOAD FILE
             */
            $filePath = null;
            if ($req->video_tipe === 'internal' && $req->hasFile('file')) {
                $filePath = $this->uploadNormal($req->file('file'), 'digital-learning-support');
            }
    
            /**
             * STORE DATA
             */
            DigitalLearningSupport::create([
                'title'      => $req->title,
                'judul'      => $req->judul ?? '',
                'video_tipe' => $req->video_tipe,
                'file'       => $filePath,
                'embed'      => $req->video_tipe === 'external' ? ($req->embed ?? '') : null,
                'created_by' => Auth::user()->id,
            ]);
    
            return response()->json([
                'code'    => 200,
                'success' => true,
                'url'     => url('backend/digital-learning-supports'),
            ]);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            return response()->json([
                'code'    => 422,
                'success' => false,
                'errors'  => $errors,
                'message' => collect($errors)->flatten()->first(),
            ], 422);
    
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = DigitalLearningSupport::find($id);
        return view('backend.digital_learning_support.edit', $data);
    }

   public function update(Request $req)
    {
        try {
            /**
             * VALIDATED
             */
            $req->validate([
                'id'         => 'required|exists:digital_learning_supports,id',
                'title'      => 'required|string|max:150',
                'judul'      => 'nullable|string|max:150',
                'video_tipe' => ['required', Rule::in(['internal', 'external'])],
                'file'       => [
                    'nullable',
                    'file',
                    'mimes:mp4,mkv,avi,mov,webm',
                    'max:512000', // 500MB
                ],
                'embed'      => [
                    Rule::requiredIf($req->video_tipe === 'external'),
                    'nullable',
                    'string',
                ],
            ]);
    
            /**
             * CHECK DATA
             */
            $data = DigitalLearningSupport::find($req->id);
    
            /**
             * UPLOAD FILE
             */
            $filePath = $data->file;
            if ($req->video_tipe === 'internal' && $req->hasFile('file')) {
                $filePath = $this->uploadNormal($req->file('file'), 'digital-learning-support');
            } elseif ($req->video_tipe === 'external') {
                $filePath = null;
            }
    
            /**
             * UPDATE DATA
             */
            $data->update([
                'title'      => $req->title,
                'judul'      => $req->judul ?? $data->judul,
                'video_tipe' => $req->video_tipe,
                'file'       => $filePath,
                'embed'      => $req->video_tipe === 'external' ? ($req->embed ?? '') : null,
            ]);
    
            return response()->json([
                'code'    => 200,
                'success' => true,
                'url'     => url('backend/digital-learning-supports'),
            ]);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            return response()->json([
                'code'    => 422,
                'success' => false,
                'errors'  => $errors,
                'message' => collect($errors)->flatten()->first(),
            ], 422);
    
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function delete(Request $req)
    {
        try {
            $data = DigitalLearningSupport::find($req->id);
            $data->delete();
            return [
                'code' => 200,
                'success' => true,
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}
