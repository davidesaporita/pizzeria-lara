@extends('layouts.app')

@section('content')

<div class="container">

    @if(session('message'))
        <div class="alert alert-primary">
            {{ session('message') }}
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col mb-4">
            <h1 class="text-center">Hello, <strong>{{ Auth::user()->first_name }}</strong></h1>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-9 mb-4">
            <div class="card bg-light mb-3" >
                <div class="card-header">
                    <strong>{{ $totalOrders }} orders received</strong>
                    <span>(latest {{ count($orders) }} rows shown)</span>
                </div>
                <table class="table table-hover">
                    @if(count($orders) == 0)
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
                            @foreach($orders as $order)
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

        <div class="col-3 mb-4">
            <div class="card text-white bg-info mb-3" style="height: 200px;">
                <div class="card-header"><strong>Actions</strong></div>
                <div class="card-body">
                    <a class="btn btn-warning btn-lg btn-block mb-2" href="{{ route('merchant.products.create') }}" role="button">Add new product</a>
                    <a class="btn btn-light btn-lg btn-block mb-2" href="{{ route('merchant.orders.index') }}" role="button">Order list</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col">
            <h1 class="text-center">Your products</h1>
        </div>
    </div>

    {{-- Pizze --}}
    <div class="row">
        <div class="col d-flex align-items-center">
            <h2 class="mr-2"><strong>Pizzas</strong></h2>
            <hr>
        </div>
    </div>
    <hr>
    <div class="row row-cols-1 row-cols-md-4">
        @foreach($products['pizzas'] as $product)
            <div class="col mb-4">
                <div class="card card-pizza">
                    <img 
                        src="{{ strpos($product->image, '://') ? $product->image : asset("/storage/" . $product->image) }}" 
                        class="card-img-top" 
                        alt="{{ $product->name }}"
                    >
                    <div class="card-body">
                        <h5 class="card-title"><strong>{{ $product->name }}</strong></h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <h4>€ {{ number_format($product->price, 2) }}</h4>
                        <div class="form-group form-inline">
                            <a class="btn btn-primary" href="{{ route('merchant.products.show', $product->id) }}" role="button">View</a>
                            <a class="btn btn-secondary ml-2" href="{{ route('merchant.products.edit', $product->id) }}" role="button">Edit</a>
                            <form action="{{ route('merchant.products.destroy', $product) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <input class="btn btn-danger ml-2" type="submit" value="Delete">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Antipasti --}}
    <div class="row mt-4">
        <div class="col d-flex align-items-center">
            <h2 class="mr-2"><strong>Appetizers</strong></h2>
            <hr>
        </div>
    </div>
    <hr>
    <div class="row row-cols-1 row-cols-md-4">
        @foreach($products['appetizers'] as $product)
            <div class="col mb-4">
                <div class="card card-pizza">
                    <img 
                        src="{{ strpos($product->image, '://') ? $product->image : asset("/storage/" . $product->image) }}" 
                        class="card-img-top" 
                        alt="{{ $product->name }}"
                    >
                    <div class="card-body">
                        <h5 class="card-title"><strong>{{ $product->name }}</strong></h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <h4>€ {{ number_format($product->price, 2) }}</h4>
                        <div class="form-group form-inline">
                            <a class="btn btn-primary" href="{{ route('merchant.products.show', $product->id) }}" role="button">View</a>
                            <a class="btn btn-secondary ml-2" href="{{ route('merchant.products.edit', $product->id) }}" role="button">Edit</a>
                            <form action="{{ route('merchant.products.destroy', $product) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <input class="btn btn-danger ml-2" type="submit" value="Delete">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Dessert --}}
    <div class="row mt-4">
        <div class="col d-flex align-items-center">
            <h2 class="mr-2"><strong>Desserts</strong></h2>
            <hr>
        </div>
    </div>
    <hr>
    <div class="row row-cols-1 row-cols-md-4">
        @foreach($products['desserts'] as $product)
            <div class="col mb-4">
                <div class="card card-pizza">
                    <img 
                        src="{{ strpos($product->image, '://') ? $product->image : asset("/storage/" . $product->image) }}" 
                        class="card-img-top" 
                        alt="{{ $product->name }}"
                    >
                    <div class="card-body">
                        <h5 class="card-title"><strong>{{ $product->name }}</strong></h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <h4>€ {{ number_format($product->price, 2) }}</h4>
                        <div class="form-group form-inline">
                            <a class="btn btn-primary" href="{{ route('merchant.products.show', $product->id) }}" role="button">View</a>
                            <a class="btn btn-secondary ml-2" href="{{ route('merchant.products.edit', $product->id) }}" role="button">Edit</a>
                            <form action="{{ route('merchant.products.destroy', $product) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <input class="btn btn-danger ml-2" type="submit" value="Delete">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection