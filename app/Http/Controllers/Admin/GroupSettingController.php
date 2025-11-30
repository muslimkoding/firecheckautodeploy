<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competency;
use App\Models\EmployeeType;
use App\Models\Group;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class GroupSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    //     public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         // $query = User::with('employeeType', 'group')->select('name')->get();
    //         $query = User::with('employeeType', 'group')->orderBy('group_id', 'asc')->select('users.*');

    //         return DataTables::of($query)
    //             ->addIndexColumn()
    //             ->addColumn('group', function($row) {
    //                 return $row->group->name ?? '<span class="text-danger">- No Group -</span>';
    //             })
    //             ->addColumn('action', function($row) {
    //                 $editUrl = route('user.group.edit', $row->id);

    //                 $actionBtn = '
    //                     <a href="'.$editUrl.'" class="btn btn-sm btn-light border"><i class="fas fa-pen-to-square"></i></a>
    //                 ';

    //                 return $actionBtn;
    //             })
    //             ->filterColumn('group', function($query, $keyword) {
    //                 $query->whereHas('group', function($q) use ($keyword) {
    //                     $q->where('name', 'like', '%'.$keyword.'%');
    //                 })->orWhereNull('group_id');
    //             })
    //             ->rawColumns(['group', 'action'])
    //             ->make(true);
    //     }

    //     return view('admin.group-setting.index');
    // }

    public function index(Request $request)
    {
        $groups = Group::all(); // Tambahkan ini untuk data filter

        if ($request->ajax()) {
            try {
                // $query = User::with('employeeType', 'group')->select('users.*');
                $query = User::with('employeeType', 'group')->orderBy('group_id', 'asc')->select('users.*');

                // Filter by group
                if ($request->has('group_filter') && $request->group_filter != '') {
                    if ($request->group_filter === 'without_group') {
                        $query->whereNull('group_id');
                    } else {
                        $query->where('group_id', $request->group_filter);
                    }
                }

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('group', function ($row) {
                        return $row->group->name ?? '<span class="text-danger">- No Group -</span>';
                    })
                    ->addColumn('action', function ($row) {
                        $editUrl = route('user.group.edit', $row->id);

                        $actionBtn = '
                            <a href="' . $editUrl . '" class="btn btn-sm btn-light border"><i class="fas fa-pen-to-square"></i></a>
                        ';

                        return $actionBtn;
                    })
                    ->filterColumn('group', function ($query, $keyword) {
                        $query->whereHas('group', function ($q) use ($keyword) {
                            $q->where('name', 'like', '%' . $keyword . '%');
                        })->orWhereNull('group_id');
                    })
                    ->rawColumns(['group', 'action'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error('DataTables Error: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return view('admin.group-setting.index', compact('groups')); // Kirim groups ke view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $employee_types = EmployeeType::all();
        $groups = Group::all();
        $competencies = Competency::all();
        $positions = Position::all();
        return view('admin.group-setting.edit', compact('user', 'employee_types', 'groups', 'competencies', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'group_id' => 'nullable',
        ]);

        try {
            User::find($id)->update($validatedData);

            Swal::success([
                'title' => 'Group berhasil diperbaharui!',
                'showConfirmButton' => false,
                'timer' => 2500,
            ]);

            return redirect()->route('user.group');
        } catch (\Exception $e) {
            Log::error('Group gagal diperbaharui : ' . $e->getMessage());

            Swal::error([
                'title' => 'Group gagal diperbaharui!',
                'showConfirmButton' => false,
                'timer' => 2500,
            ]);

            return redirect()->back()->withInput()->withErrors([
                'error' => 'Gagal menyimpan data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
