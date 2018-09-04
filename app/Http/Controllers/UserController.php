<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Helper\Response;
use App\Model\Users;
use App\Model\Berita;
class UserController extends Controller
{
    public function user(Request $request)
    {
    	try {
    		$users = Users::where('nickname', $request->nickname)->first();
    		if (!$users) {
    			return Response::json('', 'User tidak ditemukan', 'success', 200);
    		}
    		$berita = self::berita_by_user($users->id);
    		$arr = [
    			'users' => $users,
    			'berita' => $berita
    		];
    		return Response::json($arr, 'success fetch query', 'success', 200);
    	} catch (\Exception $e) {
    		return Response::json($e->getMessage(), 'Terjadi Kesahalan', 'failed', 500);
    	}
    }

    public static function berita_by_user($id)
    {
    	$berita = DB::table('berita')
        ->join('kategori', 'berita.kode_kategori', '=', 'kategori.id')
        ->select('berita.*', 'kategori.nama_kategori as kategori', 'kategori.label_color as kategori_color')
        ->where('berita.user_id', $id)
    	->where('berita.status', 'publish')
    	->orderBy('berita.created_at', 'DESC')
    	->get();

    	return $berita;
    }
}
