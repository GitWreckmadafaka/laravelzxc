<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));

    }


    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    public function toggleStatus($id)
{

    $user = User::findOrFail($id);


    $user->is_active = !$user->is_active;
    $user->last_login = now();
    $user->save();

    return redirect()->route('admin.users.index')->with('message', 'User status updated successfully!');
}

public function toggleAdmin($userId)
{
    $user = User::findOrFail($userId);


    if ($user->id == auth()->id() && $user->is_admin) {
        return redirect()->route('admin.users.show', $userId)
            ->with('error', 'You cannot remove yourself as an admin.');
    }


    $user->is_admin = !$user->is_admin;
    $user->save();

    return redirect()->route('admin.users.show', $userId)
        ->with('success', 'User role updated successfully.');
}

public function destroy($id)
{
    $user = User::find($id);

    if (!$user) {
        return redirect()->route('admin.users.index')->with('error', 'User not found.');
    }

    $userName = $user->name;
    $user->delete();

    // Log the action directly to the database
    DB::table('logs')->insert([
        'admin_id' => Auth::id(),
        'action' => 'Delete User',
        'details' => "Deleted user: $userName (ID: $id)",
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
}



}
