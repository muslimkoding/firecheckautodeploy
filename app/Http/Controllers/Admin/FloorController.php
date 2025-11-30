<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Floor\StoreFloorRequest;
use App\Http\Requests\Floor\UpdateFloorRequest;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class FloorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $floors = Floor::select(['id', 'name', 'slug', 'description', 'created_at'])->latest();

            return DataTables::of($floors)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $editUrl = route('floors.edit', $row->id);
                    $deleteUrl = route('floors.destroy', $row->id);

                    $actionBtn = '
                        <a href="'.$editUrl.'" class="btn btn-sm btn-light border"><i class="fas fa-pen-to-square"></i></a>

                        <form action="'.$deleteUrl.'" id="delete-form-'.$row->id.'" method="post" style="display: inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                            <button type="button" class="btn btn-sm btn-light border" onclick="confirmDelete('.$row->id.')">
                                <i class="fas fa-trash-can text-danger"></i>
                            </button>
                        </form>
                    ';

                    return $actionBtn;
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d/m/Y H:i');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.floor.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.floor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFloorRequest $request)
    {
        try {
            $validated = $request->validated();

            Floor::create($validated);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('floors.index');
        } catch (\Exception $e) {
            Log::error('Data lantai gagal disimpan :' . $e->getMessage());

            Swal::error([
                'title' => 'Lantai gagal disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Floor $floor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Floor $floor)
    {
        return view('admin.floor.edit', compact('floor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFloorRequest $request, Floor $floor)
    {
        try {
            $validated = $request->validated();

            $floor->update($validated);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('floors.index');
        } catch (\Exception $e) {
            Log::error('Data gagal disimpan : ' . $e->getMessage());

            Swal::error([
                'title' => 'Data gagal disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Floor $floor)
    {
        try {
            $floor->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('floors.index');
        } catch (\Exception $e) {
            Log::error('Data gagal disimpan : ' . $e->getMessage());

            Swal::error([
                'title' => 'Data gagal dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()->withInput();
        }
    }
}
