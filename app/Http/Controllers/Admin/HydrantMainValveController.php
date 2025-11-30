<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HydrantMainValve\StoreHydrantMainValveRequest;
use App\Http\Requests\HydrantMainValve\UpdateHydrantMainValveRequest;
use App\Models\HydrantMainValve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class HydrantMainValveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $hydrantMainValves = HydrantMainValve::select(['id', 'name', 'slug', 'description',])->latest();

            return DataTables::of($hydrantMainValves)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $editUrl = route('hydrant-main-valve.edit', $row->id);
                    $deleteUrl = route('hydrant-main-valve.destroy', $row->id);
                    
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

        return view('admin.hydrant-main-valve.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.hydrant-main-valve.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHydrantMainValveRequest $request)
    {
        try {
            $validated = $request->validated();

            HydrantMainValve::create($validated);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant-main-valve.index');
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
    public function show(HydrantMainValve $hydrantMainValve)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HydrantMainValve $hydrantMainValve)
    {
        // dd($hydrantMainValve);
        return view('admin.hydrant-main-valve.edit', compact('hydrantMainValve'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHydrantMainValveRequest $request, HydrantMainValve $hydrantMainValve)
    {
        try {
            $validated = $request->validated();

            $hydrantMainValve->update($validated);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant-main-valve.index');
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
    public function destroy(HydrantMainValve $hydrantMainValve)
    {
        try {
            $hydrantMainValve->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant-main-valve.index');
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
