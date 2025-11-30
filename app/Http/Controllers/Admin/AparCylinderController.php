<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AparCylinder\StoreAparCylinderRequest;
use App\Http\Requests\AparCylinder\UpdateAparCylinderRequest;
use App\Models\AparCylinder;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class AparCylinderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $aparCylinders = AparCylinder::select(['id', 'name', 'slug', 'description',])->latest();

            return DataTables::of($aparCylinders)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $editUrl = route('apar-cylinders.edit', $row->id);
                    $deleteUrl = route('apar-cylinders.destroy', $row->id);
                    
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

        return view('admin.apar-cylinder.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.apar-cylinder.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAparCylinderRequest $request)
    {
        try {
            $validated = $request->validated();

            AparCylinder::create($validated);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar-cylinders.index');
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
    public function show(AparCylinder $aparCylinder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AparCylinder $aparCylinder)
    {
        return view('admin.apar-cylinder.edit', compact('aparCylinder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAparCylinderRequest $request, AparCylinder $aparCylinder)
    {
        try {
            $validated = $request->validated();

            $aparCylinder->update($validated);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar-cylinders.index');
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
    public function destroy(AparCylinder $aparCylinder)
    {
        try {
            $aparCylinder->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar-cylinders.index');
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
