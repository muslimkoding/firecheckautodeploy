<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller implements HasMiddleware
{
    /**
     * role & permission
     */
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('role.view'), only:['index']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('role.create'), only:['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('role.update'), only:['update', 'edit']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('role.destroy'), only:['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::query();

             // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $roles->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
        }

        $roles = $roles->latest()->get();

        return DataTables::of($roles)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $editUrl = route('role.edit', $row->id);
                $deleteUrl = route('role.destroy', $row->id);
                $permissionUrl = route('roles.give-permissions', $row->id);

                $actionBtn = '
                    <div class="d-flex justify-content-center gap-1">
                       
                        <a href="'.$editUrl.'" class="btn btn-sm btn-light border btn-action" title="Edit Role">
                            <i class="fas fa-pen-to-square text-primary"></i>
                        </a>
                        
                        <form action="'.$deleteUrl.'" id="delete-form-'.$row->id.'" method="post" style="display: inline;">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="button" class="btn btn-sm btn-light border btn-action" onclick="confirmDelete('.$row->id.')" title="Hapus Role">
                                <i class="fas fa-trash-can text-danger"></i>
                            </button>
                        </form>
                    </div>
                ';

                return $actionBtn;
            })
            ->addColumn('permission_count', function($row) {
                $count = $row->permissions_count ?? $row->permissions->count();
                return '
                    <span class="badge bg-info">' . $count . '</span>
                ';
            })
            ->rawColumns(['action', 'permission_count'])
            ->make(true);
        }

        return view('admin.role.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lastRole = Role::orderBy('created_at', 'desc')->first();
        return view('admin.role.create', compact('lastRole'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'sometimes|string|max:255'
        ]);

        try {
            Role::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name ?? 'web'
            ]);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('role.index');
        } catch (\Exception $e) {
            Log::error('Data gagal disimpan : ' . $e->getMessage());

            Swal::error([
                'title' => 'Data gagal disimpan' . $e->getMessage(),
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()
                ->withInput();
        }
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
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.role.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'guard_name' => 'sometimes|string|max:255',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        try {
            DB::beginTransaction();

            $role->update([
                'name' => $request->name,
                'guard_name' => $request->guard_name ?? 'web'
            ]);

            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }

            DB::commit();

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('role.index');
                
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Data gagal diperbarui : ' . $e->getMessage());

            Swal::error([
                'title' => 'Data gagal diperbarui' . $e->getMessage(),
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()
                // ->with('error', 'Error updating role: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            // Check if role is assigned to any users
            if ($role->users()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus role. Role ini sedang digunakan oleh ' . $role->users()->count() . ' user.');
            }

            // Check if role has any permissions assigned
            if ($role->permissions()->count() > 0) {

                Swal::error([
                    'title' => 'Tidak dapat menghapus role. Role ini memiliki ' . $role->permissions()->count() . ' permission yang terhubung.',
                    'showConfirmButton' => false,
                    'timer' => 2500
                ]);

                return redirect()->back();
                    // ->with('error', 'Tidak dapat menghapus role. Role ini memiliki ' . $role->permissions()->count() . ' permission yang terhubung.');
            }

            $role->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('role.index');
                
        } catch (\Exception $e) {
            Log::error('Data gagal dihapus : ' . $e->getMessage());

            Swal::error([
                'title' => 'Data gagal dihapus' . $e->getMessage(),
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()
                ->with('error', 'Error menghapus role: ' . $e->getMessage());
        }
    }

    // /**
    //  * Show form for giving permissions to role
    //  */
    // public function givePermissions(Role $role)
    // {
    //     $permissions = Permission::orderBy('name')->get();
    //     $rolePermissions = $role->permissions->pluck('id')->toArray();
        
    //     return view('admin.role.give-permission', compact('role', 'permissions', 'rolePermissions'));
    // }

    // /**
    //  * Update permissions for role
    //  */
    // public function updatePermissions(Request $request, Role $role)
    // {
    //     $request->validate([
    //         'permissions' => 'sometimes|array',
    //         'permissions.*' => 'exists:permissions,id'
    //     ]);

    //     try {
    //         DB::beginTransaction();

    //         if ($request->has('permissions')) {
    //             $permissions = Permission::whereIn('id', $request->permissions)->get();
    //             $role->syncPermissions($permissions);
    //         } else {
    //             $role->syncPermissions([]);
    //         }

    //         DB::commit();

    //         return redirect()->route('role.show', $role)
    //             ->with('success', 'Permissions updated successfully for role: ' . $role->name);
                
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()
    //             ->with('error', 'Error updating permissions: ' . $e->getMessage());
    //     }
    // }
}
