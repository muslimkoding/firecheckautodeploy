<?php

namespace App\Http\Controllers\Admin;

use App\Models\HydrantHose;
use Illuminate\Http\Request;
use SweetAlert2\Laravel\Swal;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\HydrantHose\StoreHydrantHoseRequest;
use App\Http\Requests\HydrantHose\UpdateHydrantHoseRequest;

class HydrantHoseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $hydrantHoses = HydrantHose::select(['id', 'name', 'slug', 'description',])->latest();

            return DataTables::of($hydrantHoses)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $editUrl = route('hydrant-hoses.edit', $row->id);
                    $deleteUrl = route('hydrant-hoses.destroy', $row->id);
                    
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

        return view('admin.hydrant-hose.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.hydrant-hose.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHydrantHoseRequest $request)
    {
        try {
            $validated = $request->validated();

            HydrantHose::create($validated);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant-hoses.index');
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
    public function show(HydrantHose $hydrantHose)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HydrantHose $hydrantHose)
    {
        return view('admin.hydrant-hose.edit', compact('hydrantHose'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHydrantHoseRequest $request, HydrantHose $hydrantHose)
    {
        try {
            $validated = $request->validated();

            $hydrantHose->update($validated);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant-hoses.index');
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
    public function destroy(HydrantHose $hydrantHose)
    {
        try {
            $hydrantHose->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant-hoses.index');
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
