<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExtinguisherCondition\StoreExtinguisherConditionRequest;
use App\Http\Requests\ExtinguisherCondition\UpdateExtinguisherConditionRequest;
use App\Models\ExtinguisherCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class ExtinguisherConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $extinguisherConditions = ExtinguisherCondition::select(['id', 'name', 'slug', 'description',])->latest();

            return DataTables::of($extinguisherConditions)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $editUrl = route('extinguisher-conditions.edit', $row->id);
                    $deleteUrl = route('extinguisher-conditions.destroy', $row->id);
                    
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

        return view('admin.extinguisher-condition.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.extinguisher-condition.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExtinguisherConditionRequest $request)
    {
        try {
            $validated = $request->validated();

            ExtinguisherCondition::create($validated);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('extinguisher-conditions.index');
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
    public function show(ExtinguisherCondition $extinguisherCondition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExtinguisherCondition $extinguisherCondition)
    {
        return view('admin.extinguisher-condition.edit', compact('extinguisherCondition'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExtinguisherConditionRequest $request, ExtinguisherCondition $extinguisherCondition)
    {
        try {
            $validated = $request->validated();

            $extinguisherCondition->update($validated);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('extinguisher-conditions.index');
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
    public function destroy(ExtinguisherCondition $extinguisherCondition)
    {
        try {
            $extinguisherCondition->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('extinguisher-conditions.index');
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
