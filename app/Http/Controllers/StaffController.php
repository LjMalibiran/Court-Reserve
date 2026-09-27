<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = User::whereIn('role', ['admin', 'cashier']);
        
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $staffs = $query->orderBy('created_at', 'desc')->get();
        
        return view('admin.manage-staff', compact('staffs', 'search'));
    }

    public function attendance($id)
    {
        $user = User::findOrFail($id);
        
        $attendances = \App\Models\Attendance::where('user_id', $user->id)
            ->orderBy('login_time', 'desc')
            ->get()
            ->map(function ($attendance) {
                $duration = '---';
                if ($attendance->logout_time) {
                    $diffInMinutes = $attendance->login_time->diffInMinutes($attendance->logout_time);
                    $hours = floor($diffInMinutes / 60);
                    $minutes = $diffInMinutes % 60;
                    $duration = $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
                }
                
                return [
                    'login_time' => $attendance->login_time->format('M d, Y h:i A'),
                    'logout_time' => $attendance->logout_time ? $attendance->logout_time->format('M d, Y h:i A') : 'Active Now',
                    'duration' => $duration
                ];
            });
            
        return response()->json($attendances);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['admin', 'cashier'])],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Staff member added successfully!');
    }

    public function update(Request $request, $id)
    {
        $staff = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($staff->id)],
            'role' => ['required', Rule::in(['admin', 'cashier'])],
        ]);

        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->role = $request->role;
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $staff->password = Hash::make($request->password);
        }
        
        $staff->save();

        return redirect()->back()->with('success', 'Staff member updated successfully!');
    }

    public function toggleStatus($id)
    {
        $staff = User::findOrFail($id);
        
        // Prevent admin from deactivating themselves
        if (auth()->id() == $staff->id) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }
        
        $staff->is_active = !$staff->is_active;
        $staff->save();
        
        $status = $staff->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Staff member {$status} successfully!");
    }

    public function destroy($id)
    {
        $staff = User::findOrFail($id);
        
        if (auth()->id() == $staff->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }
        
        $staff->delete();
        
        return redirect()->back()->with('success', 'Staff member deleted successfully!');
    }
}
