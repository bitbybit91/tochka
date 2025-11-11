<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['user', 'packages.price', 'category'])
            ->whereHas('user', function($q) {
                $q->where('is_seller', true)->where('banned', false);
            });

        if ($request->has('category')) {
            $query->where('item_category_id', $request->category);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(20);
        $categories = ItemCategory::all();

        return view('items.index', compact('items', 'categories'));
    }

    public function show($uuid)
    {
        $item = Item::with(['user', 'packages.price', 'category', 'reviews.user'])
            ->findOrFail($uuid);

        $item->incrementViews();

        $relatedItems = Item::where('item_category_id', $item->item_category_id)
            ->where('uuid', '!=', $uuid)
            ->limit(6)
            ->get();

        return view('items.show', compact('item', 'relatedItems'));
    }

    public function create()
    {
        $this->authorize('create', Item::class);
        $categories = ItemCategory::all();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Item::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'item_category_id' => 'required|exists:item_categories,id',
        ]);

        $item = Item::create([
            ...$validated,
            'user_uuid' => auth()->user()->uuid,
        ]);

        return redirect()->route('items.show', $item->uuid)
            ->with('success', 'Item created successfully');
    }
}
