<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Không thể xóa tài khoản Quản trị viên!');
        }

        $user->delete();
        return back()->with('success', 'Đã xóa tài khoản người dùng thành công.');
    }

    public function toggleRole(User $user)
    {
        // Prevent admin from revoking their own admin rights
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Bạn không thể tự thay đổi quyền của chính mình!');
        }

        $newRole = $user->role === 'admin' ? 'customer' : 'admin';
        $user->update(['role' => $newRole]);

        $status = $newRole === 'admin' ? 'được cấp quyền Quản trị viên' : 'bị giáng xuống Khách hàng';
        return back()->with('success', "Tài khoản {$user->name} đã {$status}.");
    }
}
