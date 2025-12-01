@extends('layout')

@section('content')
<h3>Buat QR Baru</h3>

<form action="{{ route('qr.store') }}" method="POST">
    @csrf
    <label>URL Awal:</label>
    <input type="text" name="target_url" class="form-control" required>

    <button class="btn btn-success mt-3">Generate QR</button>
</form>
@endsection
