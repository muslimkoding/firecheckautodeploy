<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Building\StoreBuildingRequest;
use App\Http\Requests\Building\UpdateBuildingRequest;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $buildings = Building::select(['id', 'name', 'slug', 'description', 'created_at'])->latest();

            return DataTables::of($buildings)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('buildings.edit', $row->id);
                    $deleteUrl = route('buildings.destroy', $row->id);

                    $actionBtn = '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-light border"><i class="fas fa-pen-to-square"></i></a>
                        
                        <form action="' . $deleteUrl . '" id="delete-form-' . $row->id . '" method="post" style="display: inline;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="button" class="btn btn-sm btn-light border" onclick="confirmDelete(' . $row->id . ')">
                                <i class="fas fa-trash-can text-danger"></i>
                            </button>
                        </form>
                ';

                    return $actionBtn;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d/m/y H:i');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.building.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.building.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBuildingRequest $request)
    {
        try {
            $validated = $request->validated();

            Building::create($validated);

            Swal::success([
                'title' => 'Data gedung berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('buildings.index');
        } catch (\Exception $e) {
            Log::error('Data gedung gagal disimpan : ' . $e->getMessage());

            Swal::error([
                'title' => 'Data gedung gagal disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Building $building)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Building $building)
    {
        return view('admin.building.edit', compact('building'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBuildingRequest $request, Building $building)
    {
        try {
            $validated = $request->validated();

            $building->update($validated);

            Swal::success([
                'title' => 'Dara Gedung berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('buildings.index');
        } catch (\Exception $e) {
            Log::error('Data gedung gagal perbarui : ' . $e->getMessage());

            Swal::error([
                'title' => 'Dara Gedung gagal diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Building $building)
    {
        try {
            $building->delete();

            Swal::success([
                'title' => 'Data Gedung berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('buildings.index');
        } catch (\Exception $e) {
            Log::error('Data gedung gagal dihapus : ' . $e->getMessage());

            Swal::error([
                'title' => 'Data gedung gagal dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back();
        }
    }
}
