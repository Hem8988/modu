@extends('layouts.admin')
@section('title', 'Admin Account Settings')
@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    {{-- Profile Hub Header --}}
    <div style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 style="font-size: 24px; font-weight: 800; margin: 0; color: var(--text);">Account Settings</h2>
            <p style="color: var(--muted); margin-top: 4px; font-size: 14px;">Manage your profile identity, email, and security protocols.</p>
        </div>
        <span class="badge" style="background: rgba(37, 99, 235, 0.1); color: var(--accent); border: 1px solid rgba(37, 99, 235, 0.2); height: 28px; padding: 0 12px;">👤 {{ strtoupper($user->role ?? 'admin') }}</span>
    </div>
    
    @if (session('status') === 'profile-updated')
        <div style="background: #ecfdf5; color: #065f46; padding: 16px; border-radius: 12px; border: 1px solid #10b981; margin-bottom: 24px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
            <span>✅</span> Identity updated successfully.
        </div>
    @endif
    @if (session('status') === 'password-updated')
        <div style="background: #ecfdf5; color: #065f46; padding: 16px; border-radius: 12px; border: 1px solid #10b981; margin-bottom: 24px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
            <span>🔒</span> Security protocol updated successfully.
        </div>
    @endif

    {{-- Identity Hub --}}
    <div class="card" style="padding: 32px;">
        <div style="display: flex; align-items: flex-start; gap: 24px; margin-bottom: 24px;">
            <div style="width: 64px; height: 64px; background: var(--surface2); border: 2px solid var(--border); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800; color: var(--accent);">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 4px;">{{ $user->name }}</h3>
                <p style="color: var(--muted); font-size: 13px;">Member since {{ $user->created_at?->format('M Y') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf @method('PATCH')
            <div style="display: grid; gap: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div><label class="form-label">Full Account Name</label><input class="form-control" name="name" value="{{ $user->name }}" required></div>
                    <div><label class="form-label">Admin Email Address</label><input class="form-control" type="email" name="email" value="{{ $user->email }}" required></div>
                </div>
                <div>
                    <label class="form-label">Phone Number</label>
                    <input class="form-control" name="phone" value="{{ $user->phone }}" placeholder="e.g. +1 234 567 890">
                </div>
                <div style="border-top: 1px solid var(--border); padding-top: 20px; text-align: right;">
                    <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">💾 Update Profile Identity</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Security Hub --}}
    <div class="card" style="padding: 32px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; color: var(--danger);">
            <span style="font-size: 20px;">🛡</span> Security Protocol Override
        </h3>
        <p style="color: var(--muted); font-size: 13px; margin-bottom: 24px;">Ensure your account is using a long, random password to stay secure.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf @method('PUT')
            <div style="display: grid; gap: 20px;">
                <div><label class="form-label">Current Authentication Password</label><input class="form-control" type="password" name="current_password" required></div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div><label class="form-label">New Secure Password</label><input class="form-control" type="password" name="password" required></div>
                    <div><label class="form-label">Confirm New Password</label><input class="form-control" type="password" name="password_confirmation" required></div>
                </div>
                <div style="border-top: 1px solid var(--border); padding-top: 20px; text-align: right;">
                    <button type="submit" class="btn" style="background: var(--text); color: #fff; padding: 12px 24px;">🔒 Execute Security Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
