@extends('layouts.app')

@section('content')

<div class="container">

    @if(session('message'))
        <div class="alert alert-primary">
            {{ session('message') }}
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8 mb-4">
            <h1 class="mb-4"><strong>{{ $product->name }}</strong></h1>
            <h4 class="mb-4">Category: {{ $product->category->name }}</h4>
            <h4 class="mb-4">Description: {{ $product->description }}</h4>
            <h4 class="mb-4">
                <span>Price</span>
                <strong>€ {{ number_format($product->price, 2) }}</strong>
            </h4>
            <h4 class="mb-4">
                <span>Items purchased:</span>
                <strong>{{ $itemsPurchased }}</strong>
            </h4>
            <h4>
                <span>Estimated earnings (<i>current price</i>):</span>
                <strong>€ {{ number_format(($itemsPurchased * $product->price), 2) }}</strong>
            </h4>
        </div>
        <div class="col-md-4 mb-4">
            <img 
                src="{{ strpos($product->image, '://') ? $product->image : asset("/storage/" . $product->image) }}" 
                class="card-img-top" 
                alt="{{ $product->name }}"
            />
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col mb-4">
            <div class="card bg-light mb-3" >
                <div class="card-header">
                    <strong>{{ count($product->orders) }} orders with {{ $product->name }}</strong>
                </div>
                <table class="table table-hover">
                    @if(count($product->orders) == 0)
                        <tbody>
                            <tr>
                                <td>Here you will see your orders history</td>
                            </tr>
                        </tbody> 
                    @else
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                                    <td>€ {{ number_format($order->amount, 2) }}</td>
                                    <td>{{ $order->created_at->diffForHumans() }}</td>
                                </tr>      
                            @endforeach
                        </tbody>
                    @endif
                </table>    
            </div>
        </div>
    </div>



    <div class="row justify-content-center">
        <div class="col">
            <div class="form-group form-inline">
                <a class="btn btn-primary" href="{{ route('merchant.index') }}" role="button">Back to Dashboard</a>
                <a class="btn btn-secondary ml-2" href="{{ route('merchant.products.edit', $product->id) }}" role="button">Edit product</a>
                <form action="{{ route('merchant.products.destroy', $product) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input class="btn btn-danger ml-2" type="submit" value="Delete product">
                </form>
            </div>
        </div>
    </div>  

</div>

@endsection