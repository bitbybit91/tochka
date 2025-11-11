@extends('layouts.app')

@section('title', 'Tochka Free Market - Secure Decentralized Marketplace')

@section('content')
<div class="card" style="text-align: center; padding: 3rem;">
    <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">Welcome to Tochka Free Market</h1>
    <p style="font-size: 1.2rem; color: #7f8c8d; margin-bottom: 2rem;">
        Secure, decentralized marketplace supporting Bitcoin and Monero
    </p>
    <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="{{ route('items.index') }}" class="btn">Browse Products</a>
        <a href="{{ route('vendors.index') }}" class="btn" style="background-color: #27ae60;">View Vendors</a>
    </div>
</div>

<div class="card">
    <h2 style="margin-bottom: 1rem;">✨ Features</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem;">
        <div>
            <h3>🔐 Secure</h3>
            <p style="color: #7f8c8d;">End-to-end encryption and secure payments</p>
        </div>
        <div>
            <h3>💰 Cryptocurrency</h3>
            <p style="color: #7f8c8d;">Bitcoin and Monero accepted</p>
        </div>
        <div>
            <h3>🌍 Global</h3>
            <p style="color: #7f8c8d;">Worldwide shipping available</p>
        </div>
        <div>
            <h3>⭐ Reviews</h3>
            <p style="color: #7f8c8d;">Vendor rating system</p>
        </div>
    </div>
</div>

<h2 style="margin: 2rem 0 1rem 0;">Latest Products</h2>
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
        <p style="grid-column: 1/-1; text-align: center; color: #7f8c8d;">No items available yet.</p>
    @endforelse
</div>

@if($vendors->isNotEmpty())
<h2 style="margin: 3rem 0 1rem 0;">Top Vendors</h2>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
    @foreach($vendors as $vendor)
        <div class="card" style="text-align: center;">
            <h3>
                <a href="{{ route('vendors.show', $vendor->username) }}" style="text-decoration: none; color: inherit;">
                    {{ $vendor->username }}
                </a>
            </h3>
            <p style="color: #7f8c8d;">{{ $vendor->items_count }} items</p>
            @if($vendor->is_trusted_seller)
                <span style="background: #27ae60; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">
                    ⭐ Trusted
                </span>
            @endif
        </div>
    @endforeach
</div>
@endif
@endsection
