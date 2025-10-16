<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
  
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

  
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

  
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Berhasil logout.');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if (!$user) {
                $user = User::create([
                    'name'       => $googleUser->getName(),
                    'email'      => $googleUser->getEmail(),
                    'google_id'  => $googleUser->getId(),
                    'password'   => bcrypt(Str::random(16)),
                    'role'       => 'owner',
                ]);

                $store = Store::create([
                    'user_id' => $user->id,
                    'name'    => 'Toko ' . $googleUser->getName(),
                ]);

                session(['store_id' => $store->id]);
            }

            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Berhasil login dengan akun Google!');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google.');
        }
    }
}
