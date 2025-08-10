@extends('layouts.dashboard')

@section('title')
Merch Store
@endsection

@section('content')
@include('inc.header', ['title' => 'ZTL Merch Store'])

<div class="container">
    @if(count($merch_items) > 0 && toggleEnabled('merch-store'))
        <p>Items below are offered at cost to ZTL members. ZTL ARTCC is sales tax exempt thanks to VATUSA's
            status as an IRS-recognized 501(c)(3) entity. Please email your order to 
            <a href="mailto:{{ $merch_email }}" alt="Store Email">{{ $merch_email }}</a> with the item name(s) and quantity. We will reply with a confirmation
            email, total cost (including shipping), and payment instructions. All items are shipped via USPS
            First Class Mail with tracking.</p>
        <table class="table table-outline">
            <thead>
                <tr>
                    <th scope="col" colspan="2">Item</th>
                    <th scope="col">Description</th>
                    <th scope="col">Price</th>
                </tr>
                @foreach($merch_items as $merch_item)
                    <tr>
                        <td>
                            @if($merch_item->image)
                                <img src="{{ url('/storage/store/'.$merch_item->image) }}" width="300px"></img>
                            @else
                                <img src="/photos/No_image_available.svg" width="100px"></img>
                            @endif
                        </td>
                        <td>
                            @if(!is_null($merch_item->flag))
                                <span class="badge bg-{{ $merch_item->displayFlag()->badge }}">{{ $merch_item->displayFlag()->text }}</span><br>
                            @endif
                            <b>{{ $merch_item->title }}</b>
                        </td>
                        <td>{{ $merch_item->description }}</td>
                        <td>${{ $merch_item->price }} USD</td>
                    </tr>
                 @endforeach
            </thead>
        </table>
    @else
        @include('inc.empty_state', ['header' => 'No Merch', 'body' => 'Sorry - no ZTL merch available today! Please check back soon.', 'icon' => 'fa-solid fa-shirt'])
    @endif
</div>
@endsection
