<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Position;
use Illuminate\Http\Request;
use SweetAlert2\Laravel\Swal;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PositionSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $positions = Position::all(); // Tambahkan ini untuk data filter

        if ($request->ajax()) {
            try {
                // $query = User::with('employeeType', 'position')->select('users.*');
                $query = User::with('employeeType', 'position')->orderBy('position_id', 'asc')->select('users.*');

                // Filter by position
                if ($request->has('position_filter') && $request->position_filter != '') {
                    if ($request->position_filter === 'without_position') {
                        $query->whereNull('position_id');
                    } else {
                        $query->where('position_id', $request->position_filter);
                    }
                }

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('position', function ($row) {
                        return $row->position->name ?? '<span class="text-danger">- No Position -</span>';
                    })
                    ->addColumn('action', function ($row) {
                        $editUrl = route('user.position.edit', $row->id);

                        $actionBtn = '
                            <a href="' . $editUrl . '" class="btn btn-sm btn-light border"><i class="fas fa-pen-to-square"></i></a>
                        ';

                        return $actionBtn;
                    })
                    ->filterColumn('position', function ($query, $keyword) {
                        $query->whereHas('position', function ($q) use ($keyword) {
                            $q->where('name', 'like', '%' . $keyword . '%');
                        })->orWhereNull('position_id');
                    })
                    ->rawColumns(['position', 'action'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error('DataTables Error: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return view('admin.position-setting.index', compact('positions')); // Kirim positions ke view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $positions = Position::all();

        return view('admin.position-setting.edit', compact('user', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'position_id' => 'nullable',
        ]);

        try {
            User::find($id)->update($validatedData);

            Swal::success([
                'title' => 'Position berhasil diperbaharui!',
                'showConfirmButton' => false,
                'timer' => 2500,
            ]);

            return redirect()->route('user.position');
        } catch (\Exception $e) {
            Log::error('Position gagal diperbaharui : ' . $e->getMessage());

            Swal::error([
                'title' => 'Position gagal diperbaharui!',
                'showConfirmButton' => false,
                'timer' => 2500,
            ]);

            return redirect()->back()->withInput()->withErrors([
                'error' => 'Gagal menyimpan data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
