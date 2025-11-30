<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeType\StoreEmployeeTypeRequest;
use App\Http\Requests\EmployeeType\UpdateEmployeeTypeRequest;
use App\Models\EmployeeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class EmployeeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $employeeTypes = EmployeeType::select(['id', 'name', 'slug', 'description',])->latest();

            return DataTables::of($employeeTypes)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $editUrl = route('employee-types.edit', $row->id);
                    $deleteUrl = route('employee-types.destroy', $row->id);
                    
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

        return view('admin.employee-type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.employee-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeTypeRequest $request)
    {
        try {
            $validated = $request->validated();

            EmployeeType::create($validated);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('employee-types.index');
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
    public function show(EmployeeType $employeeType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeType $employeeType)
    {
        return view('admin.employee-type.edit', compact('employeeType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeTypeRequest $request, EmployeeType $employeeType)
    {
        try {
            $validated = $request->validated();

            $employeeType->update($validated);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('employee-types.index');
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
    public function destroy(EmployeeType $employeeType)
    {
        try {
            $employeeType->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('employee-types.index');
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
