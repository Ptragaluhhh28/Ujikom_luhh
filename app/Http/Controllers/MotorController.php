<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\TarifRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MotorController extends Controller
{
    public function index(Request $request)
    {
        $query = Motor::with('tarif')->where('status', 'tersedia');

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        if ($request->filled('merk')) {
            $query->where('merk', 'like', '%' . $request->merk . '%');
        }

        if ($request->filled('tipe_cc')) {
            $query->where('tipe_cc', $request->tipe_cc);
        }

        return response()->json($query->get());
    }

    public function ownerMotors()
    {
        $motors = Motor::with('tarif')->where('pemilik_id', auth()->id())->get();
        return response()->json($motors);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merk' => 'required|string',
            'tipe_cc' => 'required|integer|in:100,125,150',
            'no_plat' => 'required|string|unique:motor',
            'photo' => 'nullable|image|max:2048',
            'dokumen_kepemilikan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'surat_lainnya' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'tarif_harian' => 'required|numeric',
            'tarif_mingguan' => 'required|numeric',
            'tarif_bulanan' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $photo_path = $request->file('photo') ? $request->file('photo')->store('motors', 'public') : null;
        $dokumen_path = $request->file('dokumen_kepemilikan') ? $request->file('dokumen_kepemilikan')->store('documents', 'public') : null;
        $surat_lainnya_path = $request->file('surat_lainnya') ? $request->file('surat_lainnya')->store('documents', 'public') : null;

        $motor = Motor::create([
            'pemilik_id' => auth()->id(),
            'merk' => $request->merk,
            'tipe_cc' => $request->tipe_cc,
            'no_plat' => $request->no_plat,
            'status' => 'pending', // Wait for admin verification
            'photo' => $photo_path,
            'dokumen_kepemilikan' => $dokumen_path,
            'surat_lainnya' => $surat_lainnya_path,
        ]);

        TarifRental::create([
            'motor_id' => $motor->id,
            'tarif_harian' => $request->tarif_harian,
            'tarif_mingguan' => $request->tarif_mingguan,
            'tarif_bulanan' => $request->tarif_bulanan,
        ]);

        return response()->json($motor->load('tarif'), 201);
    }
    public function show($id)
    {
        $motor = Motor::with('tarif')->where('pemilik_id', auth()->id())->findOrFail($id);
        return response()->json($motor);
    }

    public function update(Request $request, $id)
    {
        $motor = Motor::where('pemilik_id', auth()->id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'merk' => 'required|string',
            'tipe_cc' => 'required|integer|in:100,125,150',
            'photo' => 'nullable|image|max:2048',
            'dokumen_kepemilikan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'surat_lainnya' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'tarif_harian' => 'required|numeric',
            'tarif_mingguan' => 'required|numeric',
            'tarif_bulanan' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $updateData = [
            'merk' => $request->merk,
            'tipe_cc' => $request->tipe_cc,
        ];

        if ($request->hasFile('photo')) {
            if ($motor->photo) Storage::disk('public')->delete($motor->photo);
            $updateData['photo'] = $request->file('photo')->store('motors', 'public');
        }

        if ($request->hasFile('dokumen_kepemilikan')) {
            if ($motor->dokumen_kepemilikan) Storage::disk('public')->delete($motor->dokumen_kepemilikan);
            $updateData['dokumen_kepemilikan'] = $request->file('dokumen_kepemilikan')->store('documents', 'public');
        }

        if ($request->hasFile('surat_lainnya')) {
            if ($motor->surat_lainnya) Storage::disk('public')->delete($motor->surat_lainnya);
            $updateData['surat_lainnya'] = $request->file('surat_lainnya')->store('documents', 'public');
        }

        $motor->update($updateData);
        $motor->status = 'pending'; // Re-verify if updated? Or keep status? Let's reset to pending for safety.
        $motor->save();

        TarifRental::updateOrCreate(
            ['motor_id' => $motor->id],
            [
                'tarif_harian' => $request->tarif_harian,
                'tarif_mingguan' => $request->tarif_mingguan,
                'tarif_bulanan' => $request->tarif_bulanan,
            ]
        );

        return response()->json($motor->load('tarif'));
    }

    public function destroy($id)
    {
        $motor = Motor::where('pemilik_id', auth()->id())->findOrFail($id);
        TarifRental::where('motor_id', $motor->id)->delete();
        $motor->delete();
        
        return response()->json(['message' => 'Motor deleted successfully']);
    }
}
