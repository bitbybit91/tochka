@extends('layouts.app')

@section('title', $item->name . ' - Tochka Market')

@section('content')
<div class="card">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div>
            <h1 style="margin-bottom: 1rem;">{{ $item->name }}</h1>
            
            <p style="color: #7f8c8d; margin-bottom: 1rem;">
                Vendor: <a href="{{ route('vendors.show', $item->user->username) }}" style="color: #3498db; text-decoration: none;">
                    {{ $item->user->username }}
                </a>
                @if($item->user->is_trusted_seller)
                    <span style="background: #27ae60; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; margin-left: 0.5rem;">
                        ⭐ Trusted
                    </span>
                @endif
            </p>
            
            <div style="margin: 1.5rem 0;">
                <h3>Description</h3>
                <p style="white-space: pre-wrap; line-height: 1.6;">{{ $item->description }}</p>
            </div>
            
            <div style="margin: 1.5rem 0;">
                <p style="color: #7f8c8d;">
                    Category: {{ $item->category->name ?? 'Uncategorized' }}
                </p>
                <p style="color: #7f8c8d;">
                    Views: {{ $item->number_of_views }}
                </p>
                <p style="color: #7f8c8d;">
                    Sales: {{ $item->number_of_sales }}
                </p>
            </div>
        </div>
        
        <div>
            <div class="card" style="background: #f9f9f9;">
                <h3 style="margin-bottom: 1rem;">Packages</h3>
                
                @forelse($item->packages as $package)
                    <div style="background: white; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                        <h4>{{ $package->name }}</h4>
                        <p style="color: #7f8c8d; font-size: 0.9rem;">{{ $package->description }}</p>
                        
                        @if($package->price)
                            <p class="price" style="margin-top: 0.5rem;">
                                €{{ number_format($package->price->price, 2) }} {{ $package->price->currency }}
                            </p>
                        @endif
                        
                        <p style="color: #7f8c8d; font-size: 0.9rem; margin-top: 0.5rem;">
                            Ships from: {{ $package->country_name_en_shipping_from ?? 'Worldwide' }}<br>
                            Ships to: {{ $package->country_name_en_shipping_to ?? 'Worldwide' }}
                        </p>
                        
                        <button class="btn" style="width: 100%; margin-top: 0.5rem;">Add to Cart</button>
                    </div>
                @empty
                    <p style="color: #7f8c8d;">No packages available.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@if($item->reviews->isNotEmpty())
<div class="card">
    <h2 style="margin-bottom: 1rem;">Reviews</h2>
    
    @foreach($item->reviews as $review)
        <div style="border-bottom: 1px solid #eee; padding: 1rem 0;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <strong>{{ $review->user->username }}</strong>
                <span style="color: #f39c12;">⭐ {{ $review->rating }}/5</span>
            </div>
            <p style="color: #7f8c8d;">{{ $review->comment }}</p>
            <p style="color: #bdc3c7; font-size: 0.85rem;">{{ $review->created_at->diffForHumans() }}</p>
        </div>
    @endforeach
</div>
@endif

@if($relatedItems->isNotEmpty())
<div class="card">
    <h2 style="margin-bottom: 1rem;">Related Products</h2>
    <div class="item-grid">
        @foreach($relatedItems as $related)
            <div class="item-card">
                <div class="content">
                    <h3>
                        <a href="{{ route('items.show', $related->uuid) }}" style="text-decoration: none; color: inherit;">
                            {{ Str::limit($related->name, 50) }}
                        </a>
                    </h3>
                    <p class="vendor">by {{ $related->user->username }}</p>
                    @if($related->packages->isNotEmpty() && $related->packages->first()->price)
                        <p class="price">€{{ number_format($related->packages->first()->price->price, 2) }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
@endsection
