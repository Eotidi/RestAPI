<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy tất cả các session với thông tin liên quan đến bàn và khuyến mãi
        $sessions = Session::with('table', 'promotion')->get();

        return response()->json($sessions);
    }
    
    public function show($id)
    {
        // Lấy thông tin chi tiết session
        $session = Session::with(['table', 'user'])->findOrFail($id);

        return response()->json(['data' => $session]);
    }
}
