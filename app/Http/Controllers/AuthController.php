<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Tampilkan halaman registrasi.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi user baru.
     * Saat user mendaftar → otomatis jadi 'owner' dan dibuatkan 1 toko.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:6|confirmed',
            'store_name' => 'required|string|max:100',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'owner',
        ]);

        $store = Store::create([
            'user_id' => $user->id,
            'name'    => $request->store_name,
            'address' => $request->address ?? null,
            'phone'   => $request->phone ?? null,
        ]);

        Auth::login($user);
        session(['store_id' => $store->id]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Akun dan toko berhasil dibuat!');
    }

    /**
     * Proses login user.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            if ($user->role === 'owner' && $user->stores()->exists()) {
                $store = $user->stores()->first();
                session(['store_id' => $store->id]);
            }

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'owner', 'kasir' => redirect()->route('dashboard'),
                default => redirect()->route('dashboard'),
            };
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    /**
     * Logout user dari sistem.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Berhasil logout.');
    }
}
