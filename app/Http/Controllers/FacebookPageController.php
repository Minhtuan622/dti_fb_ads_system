<?php

namespace App\Http\Controllers;

use App\Models\FacebookAccount;
use App\Models\FacebookPage;
use Illuminate\Http\Request;

class FacebookPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = FacebookPage::withCount('projects')->paginate();

        return view('facebook-pages.index', [
            'pages' => $pages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $facebookAccounts = FacebookAccount::all();

        return view('facebook-pages.create', [
            'facebookAccounts' => $facebookAccounts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'facebook_account_id' => 'required|exists:facebook_accounts,id',
            'page_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        FacebookPage::create($validated);

        return redirect()->route('facebook-pages.index')
            ->with('success', 'Trang Facebook đã được thêm thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FacebookPage $facebookPage)
    {
        $facebookAccounts = FacebookAccount::all();

        return view('facebook-pages.edit', [
            'facebookPage' => $facebookPage,
            'facebookAccounts' => $facebookAccounts,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FacebookPage $facebookPage)
    {
        $validated = $request->validate([
            'facebook_account_id' => 'required|exists:facebook_accounts,id',
            'page_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        $facebookPage->update($validated);

        return redirect()->route('facebook-pages.index')
            ->with('success', 'Trang Facebook đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FacebookPage $facebookPage)
    {
        $facebookPage->delete();

        return redirect()->route('facebook-pages.index')
            ->with('success', 'Trang Facebook đã được xóa thành công.');
    }
}
