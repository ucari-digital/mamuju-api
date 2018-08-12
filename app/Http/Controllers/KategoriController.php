<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\Kategori;
use App\Helper\Response;
class KategoriController extends Controller
{
    public function kategori()
    {
    	try {
    		$data = Kategori::where('is_deleted', 'N')->get();
    		return Response::json($data, 'success fetch query', 'success', 200);
    	} catch (\Exception $e) {
    		return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
    	}
    }

    public function kategori_finder(Request $request)
    {
    	try {
    		$data = Kategori::where('is_deleted', 'N')
    		->where('id', $request->kategori)
    		->orWhere('nama_kategori', $request->kategori)
    		->first();
    		return Response::json($data, 'success fetch query', 'success', 200);
    	} catch (\Exception $e) {
    		return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
    	}
    }
}
