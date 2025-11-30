<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Zone\StoreZoneRequest;
use App\Http\Requests\Zone\UpdateZoneRequest;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $zones = Zone::select(['id', 'name', 'slug', 'description', 'created_at'])->latest();

            return DataTables::of($zones)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $editUrl = route('zones.edit', $row->id);
                    $deleteUrl = route('zones.destroy', $row->id);
                    
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

        return view('admin.zone.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.zone.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreZoneRequest $request)
    {
        try {
            $validated = $request->validated();

            Zone::create($validated);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('zones.index');
        } catch (\Exception $e) {
            Log::error('Data gagal disimpan :' . $e->getMessage());

            Swal::error([
                'title' => 'Data gagal disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Zone $zone)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Zone $zone)
    {
        return view('admin.zone.edit', compact('zone'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateZoneRequest $request, Zone $zone)
    {
        try {
            $validated = $request->validated();

            $zone->update($validated);

            Swal::success([
                'title' => 'Zona berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('zones.index');
        } catch (\Exception $e) {
            Log::error('Zona gagal disimpan : ' . $e->getMessage());

            Swal::error([
                'title' => 'Zona gagal disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Zone $zone)
    {
        try {
            $zone->delete();

            Swal::success([
                'title' => 'Zona berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('zones.index');
        } catch (\Exception $e) {
            Log::error('Zona gagal disimpan : ' . $e->getMessage());

            Swal::error([
                'title' => 'Data gagal dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()->withInput();
        }
    }
}
