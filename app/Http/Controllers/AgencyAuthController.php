<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AgencyAuthController extends Controller
{
    public function showRegister()
    {
        return view('agency.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:agencies,email'],
            'phone' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        // Store a logo upload when provided.
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('agencies', 'public');
        }

        Agency::create([
            'name' => $data['name'],
            'contact_person' => $data['contact_person'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'logo_path' => $logoPath,
            'status' => 'pending',
            'registered_at' => now(),
        ]);

        return redirect()
            ->route('agency.register')
            ->with('agency_pending', 'Thanks for registering! Your agency is pending approval.');
    }

    public function showLogin()
    {
        return view('agency.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $agency = Agency::query()->where('email', $data['email'])->first();

        if ($agency && $agency->status !== 'approved') {
            // Surface a friendly status message when the agency is not approved.
            $message = match ($agency->status) {
                'pending' => 'Your agency is pending approval.',
                'rejected' => 'Your agency registration was rejected.',
                'suspended' => 'Your agency account is suspended.',
                default => 'Your agency account is not active.',
            };

            return back()
                ->withErrors(['login' => $message], 'agency_login')
                ->withInput($request->only('email'));
        }

        if (Auth::guard('agency')->attempt($data, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route('agency.dashboard');
        }

        return back()
            ->withErrors(['login' => 'The provided credentials do not match our records.'], 'agency_login')
            ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::guard('agency')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('agency.login');
    }
}
