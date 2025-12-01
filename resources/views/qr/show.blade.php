@extends('layout')

@section('content')
<h3>Detail QR</h3>

<p><b>Kode QR:</b> {{ $qr->code }}</p>
<p><b>URL Saat Ini:</b> {{ $qr->target_url }}</p>

<h4>QR Code:</h4>
<div>
    {!! QrCode::size(250)->generate(url('/r/'.$qr->code)) !!}
</div>

<a href="{{ route('qr.edit', $qr->id) }}" class="btn btn-warning mt-3">Edit URL Tujuan</a>
@endsection
