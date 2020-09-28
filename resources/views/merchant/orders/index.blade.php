@extends('layouts.app')

@section('content')
<div class="container">
    @if ($errors->all())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-9 mb-4">
            <div class="card bg-light mb-3" >
                <div class="card-header">
                    <strong>{{ count($orders) }} orders received</strong>
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
    
        <div class="actions col-3 mb-4 position-sticky">
            <div class="card text-white bg-info mb-3" style="height: 200px;">
                <div class="card-header"><strong>Actions</strong></div>
                <div class="card-body">
                    <a class="btn btn-warning btn-lg btn-block mb-2" href="{{ route('merchant.products.create') }}" role="button">Add new Product</a>
                    <a class="btn btn-light btn-lg btn-block mb-2" href="{{ route('merchant.index') }}" role="button">Dashboard</a>
                </div>
            </div>
        </div>
    </div>



@endsection