<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Position\StorePositionRequest;
use App\Http\Requests\Position\UpdatePositionRequest;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $position = Position::select(['id', 'name', 'slug', 'description'])->latest();

            return DataTables::of($position)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $editUrl = route('positions.edit', $row->id);
                    $deleteUrl = route('positions.destroy', $row->id);

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

        return view('admin.position.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.position.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePositionRequest $request)
    {
        try {
            $validated = $request->validated();

            Position::create($validated);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('positions.index');
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
    public function show(Position $position)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $position)
    {
        return view('admin.position.edit', compact('position'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePositionRequest $request, Position $position)
    {
        try {
            $validated = $request->validated();

            $position->update($validated);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('positions.index');
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
    public function destroy(Position $position)
    {
        try {
            $position->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('positions.index');
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

