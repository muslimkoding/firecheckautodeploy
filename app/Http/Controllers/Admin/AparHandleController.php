<?php

namespace App\Http\Controllers\Admin;

use App\Models\AparHandle;
use Illuminate\Http\Request;
use SweetAlert2\Laravel\Swal;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\AparHandle\StoreAparHandleRequest;
use App\Http\Requests\AparHandle\UpdateAparHandleRequest;

class AparHandleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $aparHandles = AparHandle::select(['id', 'name', 'slug', 'description',])->latest();

            return DataTables::of($aparHandles)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $editUrl = route('apar-handles.edit', $row->id);
                    $deleteUrl = route('apar-handles.destroy', $row->id);
                    
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

        return view('admin.apar-handle.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.apar-handle.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAparHandleRequest $request)
    {
        try {
            $validated = $request->validated();

            AparHandle::create($validated);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar-handles.index');
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
    public function show(AparHandle $aparHandle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AparHandle $aparHandle)
    {
        return view('admin.apar-handle.edit', compact('aparHandle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAparHandleRequest $request, AparHandle $aparHandle)
    {
        try {
            $validated = $request->validated();

            $aparHandle->update($validated);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar-handles.index');
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
    public function destroy(AparHandle $aparHandle)
    {
        try {
            $aparHandle->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar-handles.index');
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
