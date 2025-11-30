<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Models\Permission;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * role & permission
     */
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('permission.view'), only:['index']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('permission.create'), only:['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('permission.update'), only:['update', 'edit']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('permission.destroy'), only:['destroy']),
        ];
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    if ($request->ajax()) {
        $permissions = Permission::query();

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $permissions->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
        }

        // Apply group filter
        if ($request->has('group_filter') && !empty($request->group_filter)) {
            $permissions->where('name', 'like', $request->group_filter . '.%');
        }

        $permissions = $permissions->latest()->get();

        return DataTables::of($permissions)
            ->addIndexColumn()
            ->addColumn('group', function($row) {
                // Extract group from permission name (e.g., "user.create" -> "user")
                $parts = explode('.', $row->name);
                return $parts[0] ?? 'other';
            })
            ->addColumn('action', function($row) {
                $editUrl = route('permission.edit', $row->id);
                $deleteUrl = route('permission.destroy', $row->id);

                $actionBtn = '
                    <div class="d-flex justify-content-center gap-1">
                        <a href="'.$editUrl.'" class="btn btn-sm btn-light border btn-action" title="Edit Permission">
                            <i class="fas fa-pen-to-square text-primary"></i>
                        </a>
                        
                        <form action="'.$deleteUrl.'" id="delete-form-'.$row->id.'" method="post" style="display: inline;">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="button" class="btn btn-sm btn-light border btn-action" onclick="confirmDelete('.$row->id.')" title="Hapus Permission">
                                <i class="fas fa-trash-can text-danger"></i>
                            </button>
                        </form>
                    </div>
                ';

                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('admin.permission.index');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lastPermission = Permission::orderBy('created_at', 'desc')->first();
        return view('admin.permission.create', compact('lastPermission'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'guard_name' => 'sometimes|string|max:255'
        ]);

        try {
            Permission::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name ?? 'web'
            ]);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('permission.index');
                // ->with('success', 'Permission created successfully.');
                
        } catch (\Exception $e) {
            Log::error('Data gagal disimpan : ' . $e->getMessage());

            Swal::error([
                'title' => 'Data gagal disimpan' . $e->getMessage(),
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()
                ->with('error', 'Error creating permission: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        $roles = $permission->roles;
        return view('admin.permission.show', compact('permission', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        $lastPermission = Permission::orderBy('name', 'desc')->first();
        return view('admin.permission.edit', compact('lastPermission', 'permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'guard_name' => 'sometimes|string|max:255'
        ]);

        try {
            $permission->update([
                'name' => $request->name,
                'guard_name' => $request->guard_name ?? 'web'
            ]);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('permission.index');
                // ->with('success', 'Permission updated successfully.');
                
        } catch (\Exception $e) {
            Log::error('Data gagal disimpan : ' . $e->getMessage());

            Swal::error([
                'title' => 'Data gagal disimpan' . $e->getMessage(),
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()
                ->with('error', 'Error updating permission: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        try {
            // Check if permission is assigned to any role
            if ($permission->roles()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete permission. It is assigned to one or more roles.');
            }

            $permission->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('permission.index');
                
        } catch (\Exception $e) {
            Log::error('Data gagal dihapus : ' . $e->getMessage());

            Swal::error([
                'title' => 'Data gagal dihapus' . $e->getMessage(),
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()
                ->with('error', 'Error deleting permission: ' . $e->getMessage());
        }
    }
}
