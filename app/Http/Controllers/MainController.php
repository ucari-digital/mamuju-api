<?php

namespace App\Http\Controllers;

use App\Model\Analytics;
use App\Model\Komentar;
use Illuminate\Http\Request;
use App\Helper\Response;
use App\Model\Berita;
class MainController extends Controller
{
    public function headline(Request $request)
    {
    	try {
	    	$data = Berita::where('status', 'publish')->take(1)->orderBy('created_at', 'DESC')->first();
	    	$collection = collect($data);
	    	$collection->put('url', env('PATH_STORAGE'));
			return Response::json($collection, 'success fetch query', 'success', 200);
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
}
