<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Models\Position;
use App\Models\Competency;
use App\Models\EmployeeType;
use Illuminate\Http\Request;
use SweetAlert2\Laravel\Swal;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = Auth::user();
        return view('admin.profile.show', compact('user'));
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $user = Auth::user();
    
        // Load relational data jika diperlukan
        $employeTypes = EmployeeType::orderBy('name')->get();
        $groups = Group::orderBy('name')->get();
        $positions = Position::orderBy('name')->get();
        $competencies = Competency::orderBy('name')->get();
        
        return view('admin.profile.edit', compact(
            'user', 
            'employeTypes', 
            'groups', 
            'positions', 
            'competencies'
        ));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'nip' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('users')->ignore($user->id),
            ],
            'date_birth' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'employe_type_id' => 'nullable|exists:employee_types,id',
            'group_id' => 'nullable|exists:groups,id',
            'position_id' => 'nullable|exists:positions,id',
            'competency_id' => 'nullable|exists:competencies,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $validator->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
                
                $imagePath = $request->file('image')->store('users', 'public');
                $data['image'] = $imagePath;
            }

            // Handle image removal
            if ($request->has('remove_image') && $user->image) {
                Storage::disk('public')->delete($user->image);
                $data['image'] = null;
            }

            // Remove remove_image from data as it's not a database field
            unset($data['remove_image']);

            $user->update($data);

            Swal::success([
                'title' => 'Profile berhasil diperbaharui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('profile.show');

        } catch (\Exception $e) {
            Log::error('Profile gagal diperbaharui :' . $e->getMessage());

            Swal::error([
                'title' => 'Profile gagal diperbaharui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()
                ->withInput();
        }
    }

    /**
     * Show the form for changing password.
     */
    public function editPassword()
    {
        return view('admin.profile.edit-password');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validator->after(function ($validator) use ($user, $request) {
            if (!Hash::check($request->current_password, $user->password)) {
                $validator->errors()->add('current_password', 'The current password is incorrect.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            Swal::success([
                'title' => 'Password berhasil diperbaharui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('profile.show')
                ->with('success', 'Password updated successfully.');

        } catch (\Exception $e) {
            Log::error('Password gagal diperbaharui :' . $e->getMessage());

            Swal::error([
                'title' => 'Password gagal diperbaharui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()
                ->with('error', 'Error updating password: ' . $e->getMessage());
        }
    }
}
