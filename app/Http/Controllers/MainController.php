<?php

namespace App\Http\Controllers;

use App\Model\Analytics;
use App\Model\Komentar;
use App\User;
use Illuminate\Http\Request;
use App\Helper\Response;
use App\Model\Berita;
use App\Model\Kategori;
class MainController extends Controller
{

    public function news(Request $request)
    {
    	try {
    		$data = Berita::where('status', 'publish')
    		->take($request->take)
    		->skip($request->skip)
    		->orderBy('created_at', 'DESC');
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
                    $data_arr->put('kategori_color', '');
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

    public function news_detail($id, $seo)
    {

        try {
            $data = Berita::where('id', $id)->where('seo', $seo)->first();
            if(empty($data))
            {
                return Response::json("not found", 'ID Berita Tidak Tersedia', 'failed', 404);
            }
            return Response::json($data, 'success fetch query', 'success', 200);
        } catch (\Exception $e) {
            return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
        }
    }

    public function news_search(Request $request)
    {
        try {
            $data = Berita::where('status', 'publish')->where('judul', 'like', '%'.$request->judul.'%')->orderBy('created_at', 'DESC')->get();

            $data_response = [];
            foreach ($data as $item) {
                $tags = Berita::where('tags', 'like', '%'.$item->tags.'%')->first();
                if (empty($tags)) {
                    $tags = '';
                }
                $data_arr = collect($item);
                $data_arr->put('kategori', $tags);
                $data_response[] = $data_arr;
            }
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

            $data = Komentar::where('berita_id', $id)->orderBy('created_at', 'DESC')->get();
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
            $simpan->url = $request->url;
            $simpan->ip = $request->ip;
            $simpan->unique = $request->unique;
            $simpan->save();

            return Response::json($simpan, 'success insert data', 'success', 200);
        } catch (\Exception $e) {
            return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
        }
    }

    public function analytics(Request $request, $id)
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

    public function profil(Request $request)
    {
        try {
            $data = User::where('role', $request->role)->where('status', $request->status)->orderBy('created_at', 'DESC')->get();
            return Response::json($data, 'success fetch query', 'success', 200);
        } catch (\Exception $e) {
            return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
        }
    }
}
