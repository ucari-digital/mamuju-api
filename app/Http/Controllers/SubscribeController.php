<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helper\Response;

use App\Model\Subscribe;
class SubscribeController extends Controller
{
    public function subscribe(Request $request)
    {
    	try {
    		$data = new Subscribe;
    		$data->email = $request->email;
    		$data->save();
    		return Response::json($data, 'Berhasil subscribe', 'success', 200);
    	} catch (\Exception $e) {
    		return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
    	}
    }

    public function un_subscribe(Request $request)
    {
    	try {
    		$data = Subscribe::where('email', $request->email)->delete();

    		if ($data) {
    			$msg = 'berhasil unsubscribe';
    		} else {
    			$msg = 'Email tidak ditemukan';
    		}

    		return Response::json('', $msg, 'success', 200);
    	} catch (\Exception $e) {
    		return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
    	}
    }
}
