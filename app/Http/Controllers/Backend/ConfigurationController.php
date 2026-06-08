<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;


class ConfigurationController extends Controller
{
    public $user;
    public function __construct()
    {
        $this->user = Auth::user();
    }
    public function index()
    {
        $data['item'] = Configuration::first();
        return view('backend.configuration.index', $data);
    }


    public function profile()
    {
        $data['item'] = User::findOrFail($this->user->id);
        return view('backend.configuration.profile', $data);
    }

    public function profileMore()
    {
        if ($this->user->authorities_id == 2) {
            $data['item'] = User::findOrFail($this->user->id);
        } elseif ($this->user->authorities_id == 3) {
            $data['item'] = User::findOrFail($this->user->id);
        }
        $data['user'] = $this->user;
        return view('backend.configuration.profile-more', $data);
    }

    function profileUpdate(Request $req)
    {
        try {
            $find = User::find($req->id);
            $find->update([
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'User') : $find->photo,
                'name' => $req->nama,
                'email' => $req->email
            ]);
            if ($req->id == $this->user->id) {
                $ext = [
                    'message' => 'Profile berhasil diubah.'
                ];
            } else {
                $ext = [
                    'url' => url('backend/configuration/profile')
                ];
            }
            return array_merge([
                'code' => 201,
                'success' => true,
                'url' => url('backend/configuration/profile')
            ], $ext);
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function profileMoreUpdate(Request $req)
    {
        try {
            if ($this->user->authorities_id == 2) {
                $find = User::find($req->id);
            } elseif ($this->user->authorities_id == 3) {
                $find = User::find($req->id);
            }

            $find->update([
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'User') : $find->photo,
                'nama' => $req->nama,
                'email_marketing' => $req->email_marketing,
                'telp_marketing' => $req->telp_marketing,
                'website' => $req->website,
            ]);
            if ($req->id == $this->user->id) {
                $ext = [
                    'message' => 'Profile berhasil diubah.'
                ];
            } else {
                $ext = [
                    'url' => url('backend/configuration/profile-more')
                ];
            }
            return array_merge([
                'code' => 201,
                'success' => true,
                'url' => url('backend/configuration/profile-more')
            ], $ext);
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function changePasswordProfile(Request $req)
    {
        try {
            $data = User::find($req->id);
            if ($req->password != $req->password_confirmation) {
                throw new \Exception('Password tidak cocok.');
            }
            $data->update([
                'password' => Hash::make($req->password)
            ]);
            return [
                'code' => 200,
                'success' => true,
                'message' => 'Password berhasil diubah.',
                'url' => url('backend/configuration/profile')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
    function changePasswordProfileMore(Request $req)
    {
        try {
            if ($this->user->authorities_id == 2) {
                $data = User::find($req->id);
            } elseif ($this->user->authorities_id == 3) {
                $data = User::find($req->id);
            }
            if ($req->password != $req->password_confirmation) {
                throw new \Exception('Password tidak cocok.');
            }
            $data->update([
                'password' => Hash::make($req->password)
            ]);
            return [
                'code' => 200,
                'success' => true,
                'message' => 'Password berhasil diubah.',
                'url' => url('backend/configuration/profile')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function update(Request $request)
    {
        try {
            $data = Configuration::findOrFail($request->id);
            if(auth()->user()->authorities_id == 5) {
                $data->update([
                    'max_request' => $request->max_request, 
                    'start_request' => $request->start_month.'-'.$request->start_day, 
                    'end_request' => $request->end_month.'-'.$request->end_day, 
                ]);
            } else {
                if ($request->logo) {
                    $this->deleteFile($data->logo);
                }
                if ($request->favicon) {
                    $this->deleteFile($data->favicon);
                }
                if ($request->logo_footer) {
                    $this->deleteFile($data->logo_footer);
                }
                $data->update(array_merge($request->only('url'), [
                    'event_completion_base_url' => $request->event_completion_base_url,
                    'logo' => ($request->logo) ? $this->uploadNormal($request->logo, 'Configuration') : $data->logo,
                    'favicon' => ($request->favicon) ? $this->uploadNormal($request->favicon, 'Configuration') : $data->favicon,
                    'logo_footer' => ($request->logo_footer) ? $this->uploadNormal($request->logo_footer, 'Configuration') : $data->logo_footer,
                    'name' => $request->name,
                    'title' => $request->title,
                    'meta_keyword' => $request->meta_keyword,
                    'meta_description' => $request->meta_description,
                    'meta_abstract' => $request->meta_abstract,
                    'meta_seo' => $request->meta_seo,
                    'meta_author' => $request->meta_author,
                    'meta_title' => $request->meta_title,
                    'map' => $request->map,
                    'address' => $request->address,
                    'email' => $request->email,
                    'notelp' => $request->notelp,
                    'wa' => $request->wa,
                    'disclaimer' => $request->disclaimer,
                    'google_analytics' => $request->google_analytics,
                    'meta_pixel' => $request->meta_pixel,
                    'short_description' => $request->short_description,
                    'deskripsi_singkat' => $request->deskripsi_singkat,
                    'tawkto' => $request->tawkto,
                    'text_consult' => $request->text_consult,
                    'digital_product_title' => $request->digital_product_title,
                    'digital_product_judul' => $request->digital_product_judul,
                    'digital_product_description' => $request->digital_product_description,
                    'digital_product_deskripsi' => $request->digital_product_deskripsi,
                    'service_title' => $request->service_title,
                    'service_judul' => $request->service_judul,
                    'service_description' => $request->service_description,
                    'service_deskripsi' => $request->service_deskripsi,
                    'contact_title' => $request->contact_title,
                    'contact_judul' => $request->contact_judul,
                    'contact_description' => $request->contact_description,
                    'contact_deskripsi' => $request->contact_deskripsi,
                    'certificate_temp' => $request->certificate_temp,
                ]));
            }
            
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/configuration')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}
