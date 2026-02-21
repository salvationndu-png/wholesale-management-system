<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function manageUsers()
    {
        return view('admin.manage-users');
    }

    public function getUsers(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('usertype', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'usertype' => $user->usertype,
                'status' => $user->status,
                'created_at' => $user->created_at->format('Y-m-d'),
            ];
        });

        return response()->json(['success' => true, 'users' => $users]);
    }

    public function getUserStats()
    {
        $totalUsers = User::count();
        $activeSales = User::salesUsers()->active()->count();
        $inactiveUsers = User::where('status', 0)->count();
        $thisMonth = User::whereMonth('created_at', now()->month)->count();

        return response()->json([
            'success' => true,
            'stats' => [
                'total' => $totalUsers,
                'activeSales' => $activeSales,
                'inactive' => $inactiveUsers,
                'thisMonth' => $thisMonth,
            ]
        ]);
    }

    public function createUser(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'usertype' => 'required|in:0,1',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'usertype' => $request->usertype,
                'status' => $request->status ?? 1,
                'created_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Cannot change your own status.']);
        }

        $user->status = !$user->status;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User status updated.',
            'status' => $user->status
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'usertype' => 'required|in:0,1',
                'status' => 'required|in:0,1',
            ];

            if ($request->filled('password')) {
                $rules['password'] = 'min:8';
            }

            $request->validate($rules);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->usertype = $request->usertype;
            $user->status = $request->status;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'usertype' => $user->usertype,
                    'status' => $user->status,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $newPassword = Str::random(10);
        $user->password = Hash::make($newPassword);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.',
            'password' => $newPassword
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete your own account.']);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }
}
