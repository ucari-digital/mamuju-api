<?php

namespace App\Http\Controllers;

use App\Model\Analytics;
use App\Model\Komentar;
use App\User;
use Illuminate\Http\Request;
use App\Helper\Response;
use App\Model\Berita;
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

    public function profil_get($role, $status)
    {
        try {
            $data = User::where('role', $role)->where('status', $status)->orderBy('created_at', 'DESC')->get();
            return Response::json($data, 'success fetch query', 'success', 200);
        } catch (\Exception $e) {
            return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
        }
    }
}
