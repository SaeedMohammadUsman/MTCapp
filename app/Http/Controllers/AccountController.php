<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of accounts with filters.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type'); // 'sarrafi' or 'cash'
        $status = $request->input('status'); // 'active' or 'inactive'

        $accounts = Account::query()
            ->when($search, fn($query) => $query->where('name', 'like', "%$search%"))
            ->when($type, fn($query) => $query->where('type', $type))
            ->when($status, function ($query) use ($status) {
                return $status === 'inactive' ? $query->onlyTrashed() : $query->whereNull('deleted_at');
            })
            ->paginate(10);

            return view('accounts.index', compact('accounts'));
    }

    /**
     * Store a newly created account via AJAX request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:accounts,name',
            'type' => 'required|in:sarrafi,cash',
            'balance' => 'nullable|numeric|min:0',
        ]);

        $account = Account::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully!',
            'account' => $account
        ]);
    }

    /**
     * Show account details for editing.
     */
    public function edit(Account $account)
    {
        return response()->json($account);
    }

    /**
     * Update the specified account.
     */
    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:accounts,name,' . $account->id,
            'type' => 'required|in:sarrafi,cash',
            'balance' => 'nullable|numeric|min:0',
        ]);

        $account->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Account updated successfully!',
            'account' => $account
        ]);
    }

    /**
     * Soft delete the specified account.
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully!'
        ]);
    }
    
    /**
     * Restore the specified account.
     */
    public function restore(string $id)
    {
        $account = Account::withTrashed()->where('id', $id)->firstOrFail();
        $account->restore();
        return response()->json(['success' => 'Account restored successfully!']);
    }
    
}
