<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TimePricing;
use Illuminate\Http\Request;

class TimePricingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timePricings = TimePricing::all();
        return response()->json(['data' => $timePricings]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'time_duration' => 'required|numeric|min:1'
        ]);

        $timePricing = TimePricing::create($request->only('amount', 'time_duration'));

        return response()->json(['data' => $timePricing], 201);
    }

    public function show($id)
    {
        $timePricing = TimePricing::findOrFail($id);
        return response()->json(['data' => $timePricing]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'time_duration' => 'required|numeric|min:1'
        ]);

        $timePricing = TimePricing::findOrFail($id);
        $timePricing->update($request->only('amount', 'time_duration'));

        return response()->json(['data' => $timePricing]);
    }

    public function destroy($id)
    {
        $timePricing = TimePricing::findOrFail($id);
        $timePricing->delete();

        return response()->json(['message' => 'Xóa giá giờ thành công']);
    }
}
