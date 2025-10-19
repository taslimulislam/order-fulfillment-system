@extends('layout')
@section('content')
<div class="container mt-4">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h3 class="mb-0">Order List ({{ ucfirst(auth()->user()->name) }} - {{ ucfirst(auth()->user()->role) }})</h3>
        </div>
        <div class="col-auto">
            <a href="#" id="logoutBtn" class="btn btn-outline-danger">Logout</a>
        </div>
    </div>


    @if(auth()->user()->role === 'buyer')
        <a href="{{ route('orders.create') }}" class="btn btn-success mb-3">Create Order</a>
    @endif

    @if(auth()->user()->role === 'seller')
        <div class="mb-3">Balance: <strong>{{ $balance ?? 'N/A' }}</strong></div>
    @endif
    @foreach($report['orders'] ?? [] as $order)
        <div class="card mb-2">
            <div class="card-body">
                <h5>Order #{{ $order['order_number'] }}</h5>
                <p>Status: {{ $order['status'] }}</p>
                <ul>
                    @foreach($order['items'] as $item)
                        <li>{{ $item['product']['name'] }} - Qty: {{ $item['quantity'] }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach

    <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display: none;">
        @csrf
    </form>

    <script>
        $('#logoutBtn').on('click', function(e) {
            e.preventDefault();
            $('#logoutForm').submit();
        });
    </script>

</div>
@endsection