<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Response;
use App\Model\Berita;
class MainController extends Controller
{
    public function headline(Request $request)
    {
    	try {
	    	$data = Berita::where('status', 'publish')->take(1)->orderBy('created_at', 'DESC')->first();
			return Response::json($data, 'success fetch query', 'success', 200);
    	} catch (\Exception $e) {
    		return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
    	}
    }

    public function news(Request $request)
    {
    	try {
    		$data = Berita::where('status', 'publish')
    		->take($request->take)
    		->skip($request->skip)
    		->orderBy('created_at', 'DESC')
    		->get();
    		return Response::json($data, 'success fetch query', 'success', 200);
    	} catch (\Exception $e) {
    		return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
    	}
    }
}
