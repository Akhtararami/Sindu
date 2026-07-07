<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\GrowthRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChildController extends Controller
{
    /**
     * Display the landing/dashboard page.
     */
    public function index()
    {
        if (Auth::user()->isKader()) {
            return redirect('/admin');
        }
        return view('dashboard');
    }

    /**
     * Display the admin/kader dashboard page.
     */
    public function adminIndex()
    {
        if (!Auth::user()->isKader()) {
            return redirect('/')->withErrors(['unauthorized' => 'Hanya Kader Posyandu yang berwenang mengakses halaman Back-End Admin.']);
        }
        $parents = User::where('role', 'user')->orderBy('name', 'asc')->get();
        return view('admin.dashboard', compact('parents'));
    }

    /**
     * Get all children with their records.
     */
    public function getChildren()
    {
        $query = Child::with(['records' => function ($q) {
            $q->orderBy('umur_bulan', 'asc');
        }]);

        // If simple user, only show their own children
        if (Auth::user()->isUser()) {
            $query->where('user_id', Auth::id());
        }

        $children = $query->get();

        return response()->json($children);
    }

    /**
     * Create a new child.
     */
    public function storeChild(Request $request)
    {
        if (!Auth::user()->isKader()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya Kader Posyandu yang berwenang menambahkan data anak.'
            ], 403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'nama_ibu' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $child = Child::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data anak berhasil ditambahkan.',
            'child' => $child->load('records')
        ]);
    }

    /**
     * Create a new growth record.
     */
    public function storeRecord(Request $request)
    {
        // Only kader can input medical / checkup logs
        if (Auth::user()->isUser()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya Kader Posyandu yang berwenang menambahkan hasil pemeriksaan.'
            ], 403);
        }

        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'tanggal_periksa' => 'required|date',
            'umur_bulan' => 'required|integer|min:0|max:60',
            'berat_badan' => 'required|numeric|min:0.5|max:50',
            'tinggi_badan' => 'required|numeric|min:30|max:130',
            'keluhan' => 'nullable|string',
            'solusi' => 'nullable|string',
        ]);

        $child = Child::findOrFail($validated['child_id']);
        
        // Calculate status_gizi based on WHO Median standards (Gomez/Waterlow Percent of Median)
        $status_gizi = $this->calculateStatusGizi(
            $child->jenis_kelamin, 
            $validated['umur_bulan'], 
            $validated['berat_badan']
        );

        $record = GrowthRecord::create([
            'child_id' => $validated['child_id'],
            'tanggal_periksa' => $validated['tanggal_periksa'],
            'umur_bulan' => $validated['umur_bulan'],
            'berat_badan' => $validated['berat_badan'],
            'tinggi_badan' => $validated['tinggi_badan'],
            'status_gizi' => $status_gizi,
            'keluhan' => $validated['keluhan'] ?? null,
            'solusi' => $validated['solusi'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Catatan pertumbuhan berhasil disimpan.',
            'record' => $record
        ]);
    }

    /**
     * Calculate Nutritional Status (Gizi) based on Child Sex, Age, and Weight.
     * Uses the Gomez / WHO percent-of-median model.
     */
    private function calculateStatusGizi($sex, $ageInMonths, $weight)
    {
        // Median weights (kg) for Male (L) and Female (P) from 0 to 60 months
        // Derived from WHO Growth Standard Tables
        $medians = [
            'L' => [
                0 => 3.3, 1 => 4.5, 2 => 5.6, 3 => 6.4, 4 => 7.0, 5 => 7.5,
                6 => 7.9, 7 => 8.3, 8 => 8.6, 9 => 8.9, 10 => 9.2, 11 => 9.4,
                12 => 9.6, 13 => 9.9, 14 => 10.1, 15 => 10.3, 16 => 10.5, 17 => 10.7,
                18 => 10.9, 19 => 11.1, 20 => 11.3, 21 => 11.5, 22 => 11.8, 23 => 12.0,
                24 => 12.2, 30 => 13.3, 36 => 14.3, 42 => 15.3, 48 => 16.3, 54 => 17.3, 60 => 18.3
            ],
            'P' => [
                0 => 3.2, 1 => 4.2, 2 => 5.1, 3 => 5.8, 4 => 6.4, 5 => 6.9,
                6 => 7.3, 7 => 7.6, 8 => 7.9, 9 => 8.2, 10 => 8.5, 11 => 8.7,
                12 => 8.9, 13 => 9.2, 14 => 9.4, 15 => 9.6, 16 => 9.8, 17 => 10.0,
                18 => 10.2, 19 => 10.4, 20 => 10.6, 21 => 10.9, 22 => 11.1, 23 => 11.3,
                24 => 11.5, 30 => 12.6, 36 => 13.9, 42 => 15.0, 48 => 16.1, 54 => 17.2, 60 => 18.2
            ]
        ];

        $genderMedians = $medians[$sex] ?? $medians['L'];

        // Find nearest age bracket if exact month is not in table
        if (array_key_exists($ageInMonths, $genderMedians)) {
            $medianWeight = $genderMedians[$ageInMonths];
        } else {
            // Find bounding ages and interpolate
            $ages = array_keys($genderMedians);
            sort($ages);
            $lowerAge = 0;
            $upperAge = 60;
            foreach ($ages as $a) {
                if ($a <= $ageInMonths) $lowerAge = $a;
                if ($a >= $ageInMonths) {
                    $upperAge = $a;
                    break;
                }
            }
            if ($upperAge == $lowerAge) {
                $medianWeight = $genderMedians[$lowerAge];
            } else {
                $fraction = ($ageInMonths - $lowerAge) / ($upperAge - $lowerAge);
                $medianWeight = $genderMedians[$lowerAge] + $fraction * ($genderMedians[$upperAge] - $genderMedians[$lowerAge]);
            }
        }

        // Percentage of median weight
        $percentage = ($weight / $medianWeight) * 100;

        if ($percentage < 70) {
            return 'Gizi Buruk';
        } elseif ($percentage >= 70 && $percentage < 80) {
            return 'Gizi Kurang';
        } elseif ($percentage >= 80 && $percentage <= 120) {
            return 'Gizi Baik';
        } else {
            return 'Gizi Lebih';
        }
    }

    /**
     * Update an existing child.
     */
    public function updateChild(Request $request, Child $child)
    {
        if (!Auth::user()->isKader()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.'
            ], 403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'nama_ibu' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $child->update($validated);

        // Recalculate status for all growth records in case gender/DOB changed
        foreach ($child->records as $record) {
            $status_gizi = $this->calculateStatusGizi(
                $child->jenis_kelamin,
                $record->umur_bulan,
                $record->berat_badan
            );
            $record->update(['status_gizi' => $status_gizi]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data anak berhasil diperbarui.',
            'child' => $child->load('records')
        ]);
    }

    /**
     * Delete a child and all related growth records.
     */
    public function destroyChild(Child $child)
    {
        if (!Auth::user()->isKader()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.'
            ], 403);
        }

        $child->delete(); // growth records will automatically be handled if cascading, but let's be safe
        $child->records()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data anak dan riwayat pemeriksaannya berhasil dihapus.'
        ]);
    }

    /**
     * Delete a specific growth record.
     */
    public function destroyRecord(GrowthRecord $record)
    {
        if (!Auth::user()->isKader()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.'
            ], 403);
        }

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catatan pemeriksaan berhasil dihapus.'
        ]);
    }
}
