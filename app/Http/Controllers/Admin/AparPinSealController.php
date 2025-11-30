<?php

namespace App\Http\Controllers\Admin;

use App\Models\AparPinSeal;
use Illuminate\Http\Request;
use SweetAlert2\Laravel\Swal;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\AparPinSeal\StoreAparPinSealRequest;
use App\Http\Requests\AparPinSeal\UpdateAparPinSealRequest;

class AparPinSealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $aparPinSeals = AparPinSeal::select(['id', 'name', 'slug', 'description',])->latest();

            return DataTables::of($aparPinSeals)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $editUrl = route('apar-pin-seals.edit', $row->id);
                    $deleteUrl = route('apar-pin-seals.destroy', $row->id);
                    
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

        return view('admin.apar-pin-seal.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.apar-pin-seal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAparPinSealRequest $request)
    {
        try {
            $validated = $request->validated();

            AparPinSeal::create($validated);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar-pin-seals.index');
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
    public function show(AparPinSeal $aparPinSeal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AparPinSeal $aparPinSeal)
    {
        return view('admin.apar-pin-seal.edit', compact('aparPinSeal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAparPinSealRequest $request, AparPinSeal $aparPinSeal)
    {
        try {
            $validated = $request->validated();

            $aparPinSeal->update($validated);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar-pin-seals.index');
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
    public function destroy(AparPinSeal $aparPinSeal)
    {
        try {
            $aparPinSeal->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar-pin-seals.index');
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
