@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col mb-4">
            <h1 class="text-center">Welcome in Pizzeria, <strong>{{ Auth::user()->first_name }}</strong></h1>
            @if (session('message'))
                <div class="alert alert-success" role="alert">
                    {{ session('message') }}
                </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        </div>
    </div>

    <div class="row">
        <div class="col-4 position-sticky" style="top: 80px; height: 400px;">

            {{-- Cart --}}
            <div class="card bg-info mb-3 cart">
                <div class="card-header text-white">
                    <strong>Cart</strong>
                    <span>(max 10 per row)</span>
                </div>
                <div class="card-body p-0">
                    @if(!empty(session('cart')))
                        <ul class="list-group">
                            @foreach(session('cart.items') as $key => $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-primary d-inline-flex ">
                                        <form class="form-inline" action="{{ route('customer.cart.increment') }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <input type="hidden" name="row_id" value="{{ $key }}">
                                            <button class="btn btn-link px-0 mr-2" role="submit">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <strong class="px-0 mr-2">{{ $item['quantity'] }}</strong>
                                        </form>
                                        <form class="form-inline" action="{{ route('customer.cart.decrement') }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <input type="hidden" name="row_id" value="{{ $key }}">
                                            <button class="btn btn-link px-0 mr-3" role="submit">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <strong style="max-width: 130px;">{{ $item['name'] }}</strong>
                                        </form>
                                    </span>
                                    <form class="form-inline" action="{{ route('customer.cart.emptyRow') }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <span class="badge badge-primary badge-pill">€ {{ number_format($item['subtotal'], 2) }}</span>
                                        <input type="hidden" name="row_id" value="{{ $key }}">
                                        <button class="btn btn-link" role="submit">
                                            <i class="fas fa-times text-danger"></i>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between align-items-center active mb-1">
                                <strong>Subtotal</strong>
                                <form class="form-inline" action="{{ route('customer.cart.empty') }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <span class="badge badge-primary badge-pill">€ {{ number_format(session('cart.subtotal'), 2) }}</span>
                                    <button class="btn btn-link" role="submit">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </button>
                                </form>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center active">
                                <form action="{{ route('customer.cart.empty') }}" method="post" class="form-inline">
                                    @csrf
                                    @method('POST')
                                    <a class="btn btn-warning" href="{{ route('customer.cart.pay') }}" role="button">Checkout</a>
                                    <input class="btn btn-danger ml-2" type="submit" name="submit" value="Empty Cart">
                                </form>
                            </li>
                        </ul>
                        
                    @else
                        <p class="text-white px-3 py-4">No items in the cart</p>
                    @endif
                </div>
            </div>

            {{-- Latest orders --}}
            <div class="card bg-light mb-3" >
                <div class="card-header">
                    <strong>{{ Auth::user()->orders->count() }} orders made</strong>
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
                                <th scope="col">Amount</th>
                                <th scope="col">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>€ {{ number_format($order->amount, 2) }}</td>
                                    <td>{{ $order->created_at->diffForHumans() }}</td>
                                </tr>      
                            @endforeach
                        </tbody>
                    @endif
                </table>    
            </div>
        </div>
        <div class="col-8">

            {{-- Pizze --}}
            <div class="row">
                <div class="col d-flex align-items-center">
                    <h2 class="mr-2"><strong>Pizzas</strong> / </h2>
                    <h5>Favourite italian food in the world</h5>
                </div>
            </div>
            <hr>
            <div class="row row-cols-1 row-cols-md-3">
                @foreach($products['pizzas'] as $product)
                    <div class="col mb-4 px-1">
                        <div class="card card-pizza">
                            <img 
                                src="{{ strpos($product->image, '://') ? $product->image : asset("/storage/" . $product->image) }}" 
                                class="card-img-top" alt="{{ $product->name }}"
                            >
                            <div class="card-body text-center">
                                <h5 class="card-title text-primary"><strong>{{ $product->name }}</strong></h5>
                                <p class="card-text">{{ $product->description }}</p>
                                <h4>€ {{ number_format($product->price, 2) }}</h4>
                                <hr>
                                <div class="form-group">
                                    <form class="d-flex justify-content-center align-items-end" action="{{ route('customer.cart.add') }}" method="post">
                                        @csrf
                                        @method('POST')
                                        <label for="quantity">Qty</label>
                                        <input class="ml-2" type="number" name="quantity" value="1" min="1" max="10" style="height:36px" required>
                                        <input type="hidden" name="product_id" value="{{ $product->id }}" min="1" max="10">
                                        <input class="btn btn-success ml-2" type="submit" name="submit" value="Add">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Appetizers --}}
            <div class="row mt-4">
                <div class="col d-flex align-items-center">
                    <h2 class="mr-2"><strong>Appetizers</strong> / </h2>
                    <h5>Taste our amazing antipasti</h5>
                </div>
            </div>
            <hr>
            <div class="row row-cols-1 row-cols-md-3">
                @foreach($products['appetizers'] as $product)
                    <div class="col mb-4 px-1">
                        <div class="card card-pizza">
                            <img 
                                src="{{ strpos($product->image, '://') ? $product->image : asset("/storage/" . $product->image) }}" 
                                class="card-img-top" 
                                alt="{{ $product->name }}"
                            >
                            <div class="card-body text-center">
                                <h5 class="card-title text-primary"><strong>{{ $product->name }}</strong></h5>
                                <p class="card-text">{{ $product->description }}</p>
                                <h4>€ {{ number_format($product->price, 2) }}</h4>
                                <hr>
                                <div class="form-group">
                                    <form class="d-flex justify-content-center align-items-end" action="{{ route('customer.cart.add') }}" method="post">
                                        @csrf
                                        @method('POST')
                                        <label for="quantity">Qty</label>
                                        <input class="ml-2" type="number" name="quantity" value="1" min="1" max="10" style="height: 36px;" required>
                                        <input type="hidden" name="product_id" value="{{ $product->id }}" min="1" max="10">
                                        <input class="btn btn-success ml-2" type="submit" name="submit" value="Add">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Desserts --}}
            <div class="row mt-4">
                <div class="col d-flex align-items-center">
                    <h2 class="mr-2"><strong>Dessert</strong> / </h2>
                    <h5>From the best italian tradition</h5>
                </div>
            </div>
            <hr>
            <div class="row row-cols-1 row-cols-md-3">
                @foreach($products['desserts'] as $product)
                    <div class="col mb-4 px-1">
                        <div class="card card-pizza">
                            <img 
                                src="{{ strpos($product->image, '://') ? $product->image : asset("/storage/" . $product->image) }}" 
                                class="card-img-top" 
                                alt="{{ $product->name }}"
                            >
                            <div class="card-body text-center">
                                <h5 class="card-title text-primary"><strong>{{ $product->name }}</strong></h5>
                                <p class="card-text">{{ $product->description }}</p>
                                <h4>€ {{ number_format($product->price, 2) }}</h4>
                                <hr>
                                <div class="form-group">
                                    <form class="d-flex justify-content-center align-items-end" action="{{ route('customer.cart.add') }}" method="post">
                                        @csrf
                                        @method('POST')
                                        <label for="quantity">Qty</label>
                                        <input class="ml-2" type="number" name="quantity" value="1" min="1" max="10" style="height: 36px;" required>
                                        <input type="hidden" name="product_id" value="{{ $product->id }}" min="1" max="10">
                                        <input class="btn btn-success ml-2" type="submit" name="submit" value="Add">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <hr>  

</div>
@endsection