<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promotions = Promotion::all();
        return response()->json([
            'success' => true,
            'data' => $promotions,
            'message' => 'Lấy danh sách khuyến mãi thành công'
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $promotion = Promotion::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $promotion,
            'message' => 'Tạo khuyến mãi thành công'
        ]);
    }

    public function show($id)
    {
        $promotion = Promotion::find($id);

        if (!$promotion) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy khuyến mãi'], 404);
        }

        return response()->json(['success' => true, 'data' => $promotion]);
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::find($id);

        if (!$promotion) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy khuyến mãi'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'discount_percent' => 'sometimes|required|numeric|min:0|max:100',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $promotion->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $promotion,
            'message' => 'Cập nhật khuyến mãi thành công'
        ]);
    }

    public function destroy($id)
    {
        $promotion = Promotion::find($id);

        if (!$promotion) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy khuyến mãi'], 404);
        }

        $promotion->delete();

        return response()->json(['success' => true, 'message' => 'Xóa khuyến mãi thành công']);
    }
}
