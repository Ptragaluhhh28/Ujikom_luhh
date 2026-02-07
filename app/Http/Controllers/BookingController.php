<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use App\Models\Transaksi;
use App\Models\Motor;
use App\Models\BagiHasil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'motor_id' => 'required|exists:motor,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesali' => 'required|date|after:tanggal_mulai',
            'tipe_durasi' => 'required|in:harian,mingguan,bulanan',
            'durasi_val' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $motor = Motor::find($request->motor_id);
        if ($motor->status != 'tersedia') {
            return response()->json(['error' => 'Motor tidak tersedia'], 400);
        }

        // Calculate price using MySQL function
        $price = DB::select("SELECT calculate_rental_price(?, ?, ?) as total_price", [
            $request->motor_id,
            $request->tipe_durasi,
            $request->durasi_val
        ])[0]->total_price;

        $penyewaan = Penyewaan::create([
            'penyewa_id' => auth()->id(),
            'motor_id' => $request->motor_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesali' => $request->tanggal_selesali,
            'tipe_durasi' => $request->tipe_durasi,
            'harga' => $price,
            'status' => 'pending',
        ]);

        return response()->json($penyewaan, 201);
    }

    public function pay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'penyewaan_id' => 'required|exists:penyewaan,id',
            'metode_pembayaran' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $penyewaan = Penyewaan::find($request->penyewaan_id);
        
        $transaksi = Transaksi::create([
            'penyewaan_id' => $penyewaan->id,
            'jumlah' => $penyewaan->harga,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status' => 'success', // Simulated success
            'tanggal' => now(),
        ]);

        // Trigger after_payment_success will handle BAGI_HASIL and status update
        return response()->json($transaksi);
    }

    public function history()
    {
        $history = Penyewaan::with('motor')->where('penyewa_id', auth()->id())->get();
        return response()->json($history);
    }

    public function ownerRevenue()
    {
        $revenue = BagiHasil::whereHas('penyewaan.motor', function($q) {
            $q->where('pemilik_id', auth()->id());
        })->with('penyewaan.motor')->get();

        return response()->json($revenue);
    }
}
