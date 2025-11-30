<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HydrantType\StoreHydrantTypeRequest;
use App\Http\Requests\HydrantType\UpdateHydrantTypeRequest;
use App\Models\HydrantType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class HydrantTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $hydrantTypes = HydrantType::select(['id', 'name', 'slug', 'description'])->latest();

            return DataTables::of($hydrantTypes)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $editUrl = route('hydrant-types.edit', $row->id);
                    $deleteUrl = route('hydrant-types.destroy', $row->id);

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

        return view('admin.hydrant-type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.hydrant-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHydrantTypeRequest $request)
    {
        try {
            $validated = $request->validated();

            HydrantType::create($validated);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant-types.index');
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
    public function show(HydrantType $hydrantType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HydrantType $hydrantType)
    {
        return view('admin.hydrant-type.edit', compact('hydrantType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHydrantTypeRequest $request, HydrantType $hydrantType)
    {
        try {
            $validated = $request->validated();

            $hydrantType->update($validated);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant-types.index');
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
    public function destroy(HydrantType $hydrantType)
    {
        try {
            $hydrantType->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant-types.index');
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
