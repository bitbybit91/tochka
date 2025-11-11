<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = User::where('is_seller', true)
            ->where('banned', false)
            ->withCount('items')
            ->paginate(20);

        return view('vendors.index', compact('vendors'));
    }

    public function show($username)
    {
        $vendor = User::where('username', $username)
            ->where('is_seller', true)
            ->firstOrFail();

        $items = $vendor->items()
            ->with(['packages.price', 'category'])
            ->paginate(20);

        return view('vendors.show', compact('vendor', 'items'));
    }
}
