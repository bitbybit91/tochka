@extends('layouts.app')

@section('title', 'Browse Products - Tochka Market')

@section('content')
<div class="card">
    <h1 style="margin-bottom: 1rem;">Browse Products</h1>
    
    <form method="GET" style="display: flex; gap: 1rem; margin-bottom: 1rem;">
        <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}" 
               style="flex: 1; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
        
        <select name="category" style="padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        
        <button type="submit" class="btn">Search</button>
    </form>
</div>

<div class="item-grid">
    @forelse($items as $item)
        <div class="item-card">
            <div class="content">
                <h3>
                    <a href="{{ route('items.show', $item->uuid) }}" style="text-decoration: none; color: inherit;">
                        {{ Str::limit($item->name, 50) }}
                    </a>
                </h3>
                <p class="vendor">by {{ $item->user->username }}</p>
                <p style="color: #7f8c8d; margin: 0.5rem 0;">
                    {{ Str::limit($item->description, 100) }}
                </p>
                @if($item->packages->isNotEmpty() && $item->packages->first()->price)
                    <p class="price">€{{ number_format($item->packages->first()->price->price, 2) }}</p>
                @endif
                <a href="{{ route('items.show', $item->uuid) }}" class="btn" style="margin-top: 1rem; display: block; text-align: center;">
                    View Details
                </a>
            </div>
        </div>
    @empty
        <p style="grid-column: 1/-1; text-align: center; color: #7f8c8d; padding: 2rem;">
            No items found. Try adjusting your search.
        </p>
    @endforelse
</div>

<div style="margin-top: 2rem;">
    {{ $items->links() }}
</div>
@endsection
