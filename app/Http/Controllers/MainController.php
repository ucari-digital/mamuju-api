<?php

namespace App\Http\Controllers;

use App\Model\Analytics;
use App\Model\Iklan;
use App\Model\Komentar;
use App\User;

use Illuminate\Http\Request;
use DB;

use App\Helper\Response;
use App\Model\Berita;
use App\Model\Kategori;
class MainController extends Controller
{

    public function news(Request $request)
    {
    	try {
    		$data = DB::table('berita')
            ->join('kategori', 'berita.kode_kategori', '=', 'kategori.id')
            ->select('berita.*', 'kategori.nama_kategori as kategori', 'kategori.label_color as kategori_color')
            ->take($request->take)
            ->skip($request->skip)
            ->orderBy('created_at', 'DESC');

            if ($request->kategori) {
                $data = $data->where('kode_kategori', $request->kategori)
                ->orWhere('nama_kategori', $request->kategori)
                ->get();
            } else {
                $data = $data->get();
            }

    		return Response::json($data, 'success fetch query', 'success', 200);
    	} catch (\Exception $e) {
    		return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
    	}
    }

    public function news_detail(Request $request, $id, $seo)
    {
        try {
            $data = Berita::where('berita.id', $id)
                ->where('seo', $seo)
                ->first();

            if(empty($data))
            {
                return Response::json("not found", 'ID Berita Tidak Tersedia', 'failed', 404);
            } else {
                $analytics = Analytics::select('berita_id', 'ip')
                    ->where('berita_id', $id)
                    ->where('ip', $request->ip())
                    ->first();
                if(empty($analytics))
                {
                    $simpan = new Analytics;
                    $simpan->berita_id = $id;
                    $simpan->url = url()->full();
                    $simpan->ip = $request->ip();
                    $simpan->unique = null;
                    $simpan->save();

                    // Visitor Counter
                    Berita::where('id', $data->id)->update([
                        'visit' => $data->visit + 1
                    ]);
                }
            }

            $user_id = User::where('id', $data->user_id)->first();
            $approved_by = User::where('id', $data->approved_by)->first();
            $komentar = DB::table('komentar')
            ->join('users', 'komentar.user_id', '=', 'users.id')
            ->select('komentar.*', 'users.name', 'users.avatar')
            ->take(3)
            ->orderBy('created_at', 'DESC')
            ->get();
            $data = [
                'news_detail' => $data,
                'user_detail' => $user_id,
                'approved_detail' => $approved_by,
                'komentar' => $komentar
            ];
            return Response::json($data, 'success fetch query', 'success', 200);
        } catch (\Exception $e) {
            return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
        }
    }

    public function populer(Request $request)
    {
        try {
            $data = Berita::where('status', 'publish')
            ->orderBy('visit', 'DESC')
            ->take($request->take)
            ->skip($request->skip);
            
            if ($request->kategori) {
                $data = $data->where('kode_kategori', $request->kategori)->get();
            } else {
                $data = $data->get();
            }

            $data_response = [];
            foreach ($data as $item) {
                $kategori = Kategori::where('id', $item->kode_kategori)->first();
                $data_arr = collect($item);
                if (empty($kategori)) {
                    $data_arr->put('kategori', '');
                    $data_arr->put('kategori_color', ';');
                } else {
                    $data_arr->put('kategori', $kategori->nama_kategori);
                    $data_arr->put('kategori_color', $kategori->label_color);
                }
                $data_response[] = $data_arr;
            }
            return Response::json($data_response, 'success fetch query', 'success', 200);
        } catch (\Exception $e) {
            return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
        }
    }

    public function news_search(Request $request)
    {
        try {
            $data = Berita::where('status', 'publish')
                >where(function ($query) use ($request) {
                    $query->where('judul', 'like', '%'.$request->judul.'%')
                        ->where('tags', 'like', '%'.$request->tags.'%');
                })->orderBy('created_at', 'DESC')
                    ->get();
            return Response::json($data, 'success fetch query', 'success', 200);
        } catch (\Exception $e) {
            return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
        }
    }

    public function komentar(Request $request, $id)
    {
        try {
            $berita = Berita::select('id')->where('id', $id)->first();
            if(empty($berita))
            {
                return Response::json("not found", 'ID Berita Tidak Tersedia', 'failed', 404);
            }

            $data = Komentar::where('berita_id', $id)->orderBy('created_at', 'DESC')->paginate(10);
            return Response::json($data, 'success fetch query', 'success', 200);
        } catch (\Exception $e) {
            return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
        }
    }

    public function komentar_create(Request $request)
    {
        try {
            $simpan = new Komentar;
            $simpan->berita_id = $request->berita_id;
            $simpan->user_id = $request->user_id;
            $simpan->komentar = $request->komentar;
            $simpan->save();

            return Response::json($simpan, 'success insert data', 'success', 200);
        } catch (\Exception $e) {
            return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
        }
    }

    public function analytics($id)
    {
        try {
            $berita = Berita::select('id')->where('id', $id)->first();
            if(empty($berita))
            {
                return Response::json("not found", 'ID Berita Tidak Tersedia', 'failed', 404);
            }

            $data = Analytics::where('berita_id', $id)->count();
            return Response::json($data, 'success fetch query', 'success', 200);
        } catch (\Exception $e) {
            return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
        }
    }

    public function iklan($id)
    {
        try {
            $data = Iklan::select('iklan.*', 'users.name as users_name')->join('users', 'users.id', '=', 'iklan.user_id')->orderBy('iklan.created_at', 'DESC');
            if ($id = null) {
                $data = $data->get();
            } else {
                $data = $data->where('iklan.id', $id)->first();
            }
            return Response::json($data, 'success fetch query', 'success', 200);
        } catch (\Exception $e) {
            return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
        }
    }
}
