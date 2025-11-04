<?php

namespace App\Http\Controllers;

use App\Models\FacebookAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacebookAccountController extends Controller
{
    public function index()
    {
        $accountsQuery = Auth::user()
            ->facebookAccounts()
            ->withCount(['facebookPages', 'ads'])
            ->latest();

        $facebookAccounts = $accountsQuery->paginate(10);

        return view('facebook-accounts.index', compact('facebookAccounts'));
    }

    public function create()
    {
        return view('facebook-accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'access_token' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
        ]);

        $validated['user_id'] = Auth::id();

        FacebookAccount::create($validated);

        return redirect()->route('facebook-accounts.index')
            ->with('success', 'Tài khoản Facebook đã được thêm.');
    }

    public function edit(FacebookAccount $facebookAccount)
    {
        if ($facebookAccount->user_id !== Auth::id()) {
            abort(403);
        }

        return view('facebook-accounts.edit', compact('facebookAccount'));
    }

    public function update(Request $request, FacebookAccount $facebookAccount)
    {
        if ($facebookAccount->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'account_id' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'access_token' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
        ]);

        $facebookAccount->update($validated);

        return redirect()->route('facebook-accounts.index')
            ->with('success', 'Tài khoản Facebook đã được cập nhật.');
    }

    public function destroy(FacebookAccount $facebookAccount)
    {
        if ($facebookAccount->user_id !== Auth::id()) {
            abort(403);
        }

        $facebookAccount->delete();

        return redirect()->route('facebook-accounts.index')
            ->with('success', 'Tài khoản Facebook đã được xóa.');
    }
}