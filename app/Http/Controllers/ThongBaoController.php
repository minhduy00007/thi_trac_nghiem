<?php

namespace App\Http\Controllers;

use App\Models\SinhVien;
use Illuminate\Http\Request;

class ThongBaoController extends Controller
{
    public function index($id){
        
        $sinhVien = SinhVien::find($id);
       
        $userEmail = $sinhVien->email;
        return view('thong-bao', [
            'userEmail' => $userEmail,
            'message' => 'Tài khoản ' . $userEmail . ' đã hết phiên đăng nhập'
        ]);
    }

    public function checkSession(Request $request){
        if ($request->state == false ) {
            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'redirect'   => route('thong-bao', [$request->id])
            ]);
        }
    }


}
