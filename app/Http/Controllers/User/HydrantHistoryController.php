<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Hydrant;
use App\Models\HydrantCheck;
use Illuminate\Http\Request;

class HydrantHistoryController extends Controller
{
    /**
     * Menampilkan form scan QR code
     */
    public function scanForm()
    {
        return view('user.hydrant.scan');
    }

    /**
     * Handle scan QR code dan tampilkan riwayat
     */
    public function showHistory(Request $request)
    {
        // Validasi input
        $request->validate([
            'qr_code' => 'required|string|max:255'
        ]);

        try {
            // Cari Hydrant berdasarkan QR code
            $barcode = $request->qr_code;

    $hydrant = Hydrant::with(['zone', 'building', 'user', 'floor', 'brand', 'hydrantType', 'extinguisherCondition'])
            ->where('is_active', true) // Perbaikan: gunakan true, bukan $true
            ->where(function($query) use ($barcode) {
                $query->where('qr_code', $barcode)
                      ->orWhere('number_hydrant', $barcode);
            })
            ->first();
            if (!$hydrant) {
                return redirect()->back()->with('error', 'Hydrant tidak ditemukan!');
            }

            // Ambil 10 riwayat pengecekan terbaru
            $inspections = HydrantCheck::where('hydrant_id', $hydrant->id)
                                        ->with(['user', 'hydrant', 'zone', 'building', 'group', 'hydrantDoor', 'hydrantCoupling', 'hydrantMainValve', 'hydrantHose', 'hydrantNozzle', 'hydrantSafetyMarking','condition', 'hydrantGuide', 'hydrantType'])
                                        ->orderBy('date_check', 'desc')
                                        ->limit(10)
                                        ->get();

            return view('user.hydrant.history', compact('hydrant', 'inspections'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
