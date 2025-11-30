<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\HydrantNozzle;
use SweetAlert2\Laravel\Swal;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\HydrantNozzle\StoreHydrantNozzleRequest;
use App\Http\Requests\HydrantNozzle\UpdateHydrantNozzleRequest;

class HydrantNozzleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $hydrantNozzles = HydrantNozzle::select(['id', 'name', 'slug', 'description',])->latest();

            return DataTables::of($hydrantNozzles)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $editUrl = route('hydrant-nozzles.edit', $row->id);
                    $deleteUrl = route('hydrant-nozzles.destroy', $row->id);
                    
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
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.hydrant-nozzle.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.hydrant-nozzle.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHydrantNozzleRequest $request)
    {
        try {
            $validated = $request->validated();

            HydrantNozzle::create($validated);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant-nozzles.index');
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
    public function show(HydrantNozzle $hydrantNozzle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HydrantNozzle $hydrantNozzle)
    {
        return view('admin.hydrant-nozzle.edit', compact('hydrantNozzle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHydrantNozzleRequest $request, HydrantNozzle $hydrantNozzle)
    {
        try {
            $validated = $request->validated();

            $hydrantNozzle->update($validated);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant-nozzles.index');
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
    public function destroy(HydrantNozzle $hydrantNozzle)
    {
        try {
            $hydrantNozzle->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant-nozzles.index');
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
