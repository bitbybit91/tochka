@extends('layouts.app')

@section('title', 'Vendors - Tochka Market')

@section('content')
<div class="card">
    <h1 style="margin-bottom: 1rem;">Vendors</h1>
    <p style="color: #7f8c8d;">Browse our trusted sellers</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
    @forelse($vendors as $vendor)
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <h3>
                    <a href="{{ route('vendors.show', $vendor->username) }}" style="text-decoration: none; color: inherit;">
                        {{ $vendor->username }}
                    </a>
                </h3>
                @if($vendor->is_trusted_seller)
                    <span style="background: #27ae60; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">
                        ⭐ Trusted
                    </span>
                @endif
            </div>
            
            @if($vendor->description)
                <p style="color: #7f8c8d; margin-bottom: 1rem;">
                    {{ Str::limit($vendor->description, 150) }}
                </p>
            @endif
            
            <div style="display: flex; justify-content: space-between; color: #7f8c8d; font-size: 0.9rem;">
                <span>{{ $vendor->items_count }} products</span>
                <span>Member since {{ $vendor->registration_date->format('M Y') }}</span>
            </div>
            
            <a href="{{ route('vendors.show', $vendor->username) }}" class="btn" style="width: 100%; margin-top: 1rem; text-align: center;">
                View Shop
            </a>
        </div>
    @empty
        <p style="grid-column: 1/-1; text-align: center; color: #7f8c8d; padding: 2rem;">
            No vendors found.
        </p>
    @endforelse
</div>

<div style="margin-top: 2rem;">
    {{ $vendors->links() }}
</div>
@endsection
