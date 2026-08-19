<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UserManagementController extends Controller
{
    public function updateStatus(Request $request, User $user)
    {
        $this->authorizeAdmin($request);

        if ($request->user()->is($user)) {
            return Redirect::back()->withErrors(['user' => 'You cannot disable your own account.']);
        }

        if (! in_array($user->role, ['staff'], true)) {
            abort(403);
        }

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $user->update(['is_active' => $validated['is_active']]);

        return Redirect::back()->with('success', 'User account status has been updated.');
    }

    public function destroy(Request $request, User $user)
    {
        $this->authorizeAdmin($request);

        if ($request->user()->is($user)) {
            return Redirect::back()->withErrors(['user' => 'You cannot remove your own account.']);
        }

        if (! in_array($user->role, ['staff'], true)) {
            abort(403);
        }

        $user->delete();

        return Redirect::back()->with('success', 'User account has been removed.');
    }

    private function authorizeAdmin(Request $request): void
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }
    }
}
