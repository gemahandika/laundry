<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Menampilkan seluruh user terdaftar
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,kasir',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User berhasil didaftarkan ke sistem.');
    }

    public function destroy(Request $request, User $user)
    {
        // Mengambil ID user yang sedang login langsung dari request sistem
        if ($request->user()->id == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri yang sedang aktif.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }

    public function resetPassword($id)
    {
        $user = \App\Models\User::findOrFail($id);

        // Set password default ke '12345678'
        $user->password = \Illuminate\Support\Facades\Hash::make('12345678');
        $user->save();

        return back()->with('success', 'Password berhasil direset ke 12345678');
    }
}
