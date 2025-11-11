@extends('layouts.app')

@section('title', $vendor->username . ' - Vendor - Tochka Market')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: start;">
        <div>
            <h1 style="margin-bottom: 0.5rem;">{{ $vendor->username }}</h1>
            @if($vendor->is_trusted_seller)
                <span style="background: #27ae60; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.9rem;">
                    ⭐ Trusted Seller
                </span>
            @endif
        </div>
        <div style="text-align: right; color: #7f8c8d;">
            <p>Member since {{ $vendor->registration_date->format('F Y') }}</p>
            <p>{{ $vendor->items()->count() }} products</p>
        </div>
    </div>
    
    @if($vendor->description)
        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #eee;">
            <h3 style="margin-bottom: 0.5rem;">About</h3>
            <p style="white-space: pre-wrap; line-height: 1.6;">{{ $vendor->description }}</p>
        </div>
    @endif
    
    @if($vendor->long_description)
        <div style="margin-top: 1rem;">
            <p style="white-space: pre-wrap; line-height: 1.6; color: #7f8c8d;">{{ $vendor->long_description }}</p>
        </div>
    @endif
    
    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #eee;">
        <h3 style="margin-bottom: 0.5rem;">Contact</h3>
        @if($vendor->pgp)
            <p style="color: #7f8c8d;">🔐 PGP: Available</p>
        @endif
        @if($vendor->bitmessage)
            <p style="color: #7f8c8d;">📧 Bitmessage: {{ $vendor->bitmessage }}</p>
        @endif
    </div>
</div>

<div class="card">
    <h2 style="margin-bottom: 1rem;">Products from {{ $vendor->username }}</h2>
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
            This vendor has no products listed yet.
        </p>
    @endforelse
</div>

<div style="margin-top: 2rem;">
    {{ $items->links() }}
</div>
@endsection
