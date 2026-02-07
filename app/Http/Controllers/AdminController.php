<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Penyewaan;
use App\Models\BagiHasil;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function verifyMotor(Request $request, $id)
    {
        $motor = Motor::findOrFail($id);
        $motor->update(['status' => 'tersedia']);
        return response()->json(['message' => 'Motor verified successfully']);
    }

    public function pendingMotors()
    {
        $motors = Motor::with('pemilik')->where('status', 'pending')->get();
        return response()->json($motors);
    }

    public function confirmBooking(Request $request, $id)
    {
        $penyewaan = Penyewaan::findOrFail($id);
        $penyewaan->update(['status' => 'aktif']);
        // trigger after_booking_update handles motor status
        return response()->json(['message' => 'Booking confirmed successfully']);
    }

    public function revenueReport()
    {
        $total_revenue = BagiHasil::sum('bagi_hasil_admin');
        $reports = BagiHasil::with('penyewaan.penyewa', 'penyewaan.motor.pemilik')->get();
        
        return response()->json([
            'total_admin_revenue' => $total_revenue,
            'reports' => $reports
        ]);
    }
}
