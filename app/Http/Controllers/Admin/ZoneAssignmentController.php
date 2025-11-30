<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Zone;
use App\Models\ZoneAssignment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Middleware\PermissionMiddleware;

class ZoneAssignmentController extends Controller implements HasMiddleware
{
    /**
     * role & permission
     */
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('zone.assignment.view'), only:['index']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('zone.assignment.create'), only:['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('zone.assignment.update'), only:['update', 'edit']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('zone.assignment.destroy'), only:['destroy']),
        ];
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zoneAssignments = ZoneAssignment::with(['zone', 'group'])
            ->orderBy('zone_id')
            ->get()
            ->groupBy('zone.name');

        $zones = Zone::orderBy('name')->get();
        $groups = Group::orderBy('name')->get();

        return view('admin.zone-assignments.index', compact('zoneAssignments', 'zones', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $zones = Zone::all();
        $groups = Group::orderBy('name')->get();

        return view('admin.zone-assignments.create', compact('zones', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'group_id' => 'required|exists:groups,id',
        ]);

        try {
            // Cek apakah assignment sudah ada
            $existingAssignment = ZoneAssignment::where('zone_id', $request->zone_id)
                ->where('group_id', $request->group_id)
                ->first();

            if ($existingAssignment) {
                return redirect()->back()
                    ->with('error', 'Assignment already exists for this zone and group.')
                    ->withInput();
            }

            ZoneAssignment::create($request->all());

            return redirect()->route('zone-assignments.index')
                ->with('success', 'Zone assignment created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating zone assignment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ZoneAssignment $zoneAssignment)
    {
        $zoneAssignment->load(['zone', 'group']);
        return view('admin.zone-assignments.show', compact('zoneAssignment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ZoneAssignment $zoneAssignment)
    {
        $zones = Zone::orderBy('name')->get();
        $groups = Group::orderBy('name')->get();

        return view('admin.zone-assignments.edit', compact('zoneAssignment', 'zones', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ZoneAssignment $zoneAssignment)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'group_id' => 'required|exists:groups,id',
        ]);

        try {
            // Cek duplikasi (kecuali record saat ini)
            $existingAssignment = ZoneAssignment::where('zone_id', $request->zone_id)
                ->where('group_id', $request->group_id)
                ->where('id', '!=', $zoneAssignment->id)
                ->first();

            if ($existingAssignment) {
                return redirect()->back()
                    ->with('error', 'Assignment already exists for this zone and group.')
                    ->withInput();
            }

            $zoneAssignment->update($request->all());

            return redirect()->route('zone-assignments.index')
                ->with('success', 'Zone assignment updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating zone assignment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ZoneAssignment $zoneAssignment)
    {
        try {
            $zoneAssignment->delete();

            return redirect()->route('zone-assignments.index')
                ->with('success', 'Zone assignment deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting zone assignment: ' . $e->getMessage());
        }
    }

    /**
     * Bulk assignment - Assign multiple zones to a group
     */
    public function bulkAssign(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'zone_ids' => 'required|array',
            'zone_ids.*' => 'exists:zones,id'
        ]);

        try {
            DB::beginTransaction();

            $group = Group::findOrFail($request->group_id);
            
            // Sync zones untuk group
            $group->zones()->sync($request->zone_ids);

            DB::commit();

            return redirect()->route('zone-assignments.index')
                ->with('success', 'Zones assigned to group successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error assigning zones: ' . $e->getMessage());
        }
    }

    /**
     * Show form for bulk assignment
     */
    public function showBulkAssign()
    {
        $groups = Group::with('zones')->orderBy('name')->get();
        $zones = Zone::orderBy('name')->get();

        return view('admin.zone-assignments.bulk-assign', compact('groups', 'zones'));
    }

    /**
     * Get assigned zones for a group (API)
     */
    public function getAssignedZones($groupId)
    {
        $group = Group::with('zones')->findOrFail($groupId);
        return response()->json($group->zones);
    }
}
