<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Table;
use App\Models\TimePricing;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     private function calculateAmount($start, $end) {
        $timePricings = TimePricing::all();
        $total = 0;

        $startTime = Carbon::parse($start);
        $endTime = Carbon::parse($end);

        while ($startTime < $endTime) {
            $hour = $startTime->hour;
            $pricing = $timePricings->first(fn($p) => $hour >= $p->start_hour && $hour < $p->end_hour);
            $nextHour = (clone $startTime)->addHour();
            $intervalEnd = $nextHour > $endTime ? $endTime : $nextHour;
            $minutes = $startTime->diffInMinutes($intervalEnd);
            $total += ($pricing->price_per_hour / 60) * $minutes;

            $startTime = $intervalEnd;
        }

        return round($total, 2);
    }


    public function toggle($id)
    {
        $table = Table::findOrFail($id);

        if ($table->is_active) {
            // Nếu đang hoạt động → tắt và kết thúc session
            $session = Session::where('table_id', $table->id)
                ->whereNull('end_time')
                ->latest()
                ->first();

            if ($session) {
                $session->end_time = now();

                $start = Carbon::parse($session->start_time);
                $end = Carbon::parse($session->end_time);
                $durationInMinutes = $end->diffInMinutes($start);

                $pricePerHour = 50000; // Hoặc lấy từ bảng time_pricings
                $session->total_amount = ($durationInMinutes / 60) * $pricePerHour;

                $session->save();
            }

            $table->is_active = false;
            $table->current_session_id = null;
        } else {
            // Nếu đang trống → bật bàn và tạo session mới
            $session = new Session();
            $session->table_id = $table->id;
            $session->start_time = now();
            $session->save();

            $table->is_active = true;
            $table->current_session_id = $session->id;
        }

        $table->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái bàn thành công']);
    }


    public function index()
    {
        // Lấy danh sách bàn
        $tables = Table::with('currentSession')->get();

        // Trả về danh sách bàn
        return response()->json(
            [
                'data' => $tables,
                'success' => true,
                'message' => 'Lấy danh sách bàn thành công',
            ]
        );


        // Trả về danh sách bàn
        return response()->json(
            [
                'data' => $tables,
                'success' => true,
                'message' => 'Lấy danh sách bàn thành công',
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $table = Table::create([
            'name' => $request->name,
            'is_active' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tạo bàn mới thành công',
            'data' => $table
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $table = Table::findOrFail($id);

        if ($table->is_active) {
            $session = $table->currentSession;
            $session->total_amount = $this->calculateAmount($session->start_time, now());
            $session->save();
        }

        // Trả về thông tin bàn
        return response()->json(
            [
                'data' => $table,
                'success' => true,
                'message' => 'Lấy thông tin bàn thành công',
            ]
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $table = Table::find($id);
        if (!$table) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy bàn'], 404);
        }

        $table->name = $request->name;
        $table->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật bàn thành công',
            'data' => $table
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $table = Table::find($id);
        if (!$table) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy bàn'], 404);
        }

        $table->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa bàn thành công'
        ]);
    }
}
