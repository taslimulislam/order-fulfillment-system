@extends('layout')
@section('content')
<div class="container mt-5">
    <h3>Login</h3>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="text" name="email" class="form-control mb-2" placeholder="Email" required>
        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
        <button class="btn btn-primary">Login</button>
    </form>
</div>
@endsection