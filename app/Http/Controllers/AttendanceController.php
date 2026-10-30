<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function scanPage()
    {
        return view('scan.index');
    }

    public function process(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = User::where('qr_code', $request->code)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'QR code tidak dikenali.'
            ], 404);
        }

        $attendance = Attendance::firstOrNew([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
        ]);

        // Belum ada data hari ini = absen MASUK
        if (!$attendance->exists) {
            $attendance->time_in = now()->format('H:i:s');
            $attendance->status = 'hadir';
            $attendance->save();

            return response()->json([
                'success' => true,
                'message' => 'BERHASIL MASUK - ' . $user->name . ' pukul ' . now()->format('H:i:s'),
            ]);
        }


        // Sudah masuk tapi belum pulang = absen PULANG
        if ($attendance->time_in && !$attendance->time_out) {
            $attendance->time_out = now()->format('H:i:s');
            $attendance->save();

            return response()->json([
                'success' => true,
                'message' => 'BERHASIL PULANG - ' . $user->name . ' pukul ' . now()->format('H:i:s'),
            ]);
        }

        // Sudah masuk dan pulang hari ini
        return response()->json([
            'success' => false,
            'message' => $user->name . ' sudah absen lengkap hari ini.',
        ], 422);
    }
}
