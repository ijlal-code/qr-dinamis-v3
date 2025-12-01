@extends('layout')

@section('content')
<h3>Edit Link Tujuan</h3>

<form action="{{ route('qr.update', $qr->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>URL Baru:</label>
    <input type="text" name="target_url" class="form-control"
        value="{{ $qr->target_url }}" required>

    <button class="btn btn-success mt-3">Update</button>
</form>
@endsection
