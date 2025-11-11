<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $items = Item::with(['user', 'packages.price', 'category'])
            ->whereHas('user', function($query) {
                $query->where('is_seller', true)
                      ->where('banned', false);
            })
            ->latest()
            ->limit(20)
            ->get();

        $categories = ItemCategory::whereNull('parent_id')->get();
        $vendors = User::where('is_seller', true)
                       ->where('banned', false)
                       ->withCount('items')
                       ->orderBy('items_count', 'desc')
                       ->limit(10)
                       ->get();

        return view('home', compact('items', 'categories', 'vendors'));
    }

    public function about()
    {
        return view('about');
    }
}
