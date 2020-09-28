@extends('layouts.app')

@section('content')

<div>
    @if(session('error'))
        <div class="alert alert-danger text-center" role="alert">
            {{ session('error') }}
        </div>
    @endif
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col mb-4">
            <h1 class="text-center">Review and pay, <strong>{{ Auth::user()->first_name }}</strong></h1>
        </div>
    </div>

    <div class="row">
        <div class="col mb-4">
            <table class="table table-hover mb-4">
                <thead>
                    <tr>
                        <th scope="col">Products</th>
                        <th class="text-right" scope="col">Price</th>
                        <th class="text-right" scope="col">Subtotal</th>
                    </tr>
                </thead>
                @if(session('cart'))
                    <tbody>
                        @foreach(session('cart.items') as $item)
                            <tr>
                                <td>{{ $item['quantity'] }} x {{ $item['name'] }}</td>
                                <td class="text-right">€ {{ number_format($item['price'], 2) }}</td>
                                <td class="text-right">€ {{ number_format($item['subtotal'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfooter>
                        <tr class="table-primary">
                            <th colspan="2">
                                <span>TOTAL</span>
                            </th>
                            <th class="text-right" >
                                <span>€ {{ number_format(session('cart.subtotal'), 2) }}</span>
                            </th>
                        </tr>
                    </tfooter>
                @else
                    <tbody>
                        <tr>
                            <td colspan==3>There are no items in the cart</td>
                        </tr>
                    </tbody>
                @endif
            </table>    
            
            <div class="d-flex">
                @if(session('cart'))
                    <form action="{{ route('customer.cart.checkout') }}" method="post">
                        @csrf
                        @method('POST')
                        <input class="btn btn-success" type="submit" name="submit" value="Pay now">
                    </form>
                @endif
                
                @if(session('cart'))
                    <form action="{{ route('customer.cart.empty') }}" method="post" class="ml-2">
                        @csrf
                        @method('POST')
                        <a class="btn btn-primary" href="{{ route('customer.index') }}" role="button">Back to index</a>
                        <input class="btn btn-danger ml-1" type="submit" name="submit" value="Empty Cart">
                    </form>
                @else
                    <a class="btn btn-primary" href="{{ route('customer.index') }}" role="button">Back to index</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection