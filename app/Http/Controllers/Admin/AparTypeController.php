<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AparType\StoreAparTypeRequest;
use App\Http\Requests\AparType\UpdateAparTypeRequest;
use App\Models\AparType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class AparTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $aparTypes = AparType::select(['id', 'name', 'slug', 'description', 'created_at'])->latest();

            return DataTables::of($aparTypes)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $editUrl = route('apar-types.edit', $row->id);
                    $deleteUrl = route('apar-types.destroy', $row->id);

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
                ->addColumn('created_at', function($row) {
                    return $row->created_at->format('d/m/Y H:i');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.apar-type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.apar-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAparTypeRequest $request)
    {
        try {
            $validated = $request->validated();

            AparType::create($validated);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar-types.index');
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
    public function show(AparType $aparType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AparType $aparType)
    {
        return view('admin.apar-type.edit', compact('aparType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAparTypeRequest $request, AparType $aparType)
    {
        try {
            $validated = $request->validated();

            $aparType->update($validated);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar-types.index');
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
    public function destroy(AparType $aparType)
    {
        try {
            $aparType->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar-types.index');
        } catch (\Exception $e) {
            Log::error('Data gagal dihapus : ' . $e->getMessage());

            Swal::error([
                'title' => 'Data gagal dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()->withInput();
        }
    }
}
