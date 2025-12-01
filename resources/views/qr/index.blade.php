@extends('layout')

@section('content')
<h3>Daftar QR Dinamis</h3>
<a href="{{ route('qr.create') }}" class="btn btn-primary mb-3">Buat QR Baru</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Kode</th>
            <th>URL Tujuan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($qrs as $qr)
        <tr>
            <td>{{ $qr->code }}</td>
            <td>{{ $qr->target_url }}</td>
            <td>
                <a href="{{ route('qr.show', $qr->id) }}" class="btn btn-info btn-sm">Detail</a>
                <a href="{{ route('qr.edit', $qr->id) }}" class="btn btn-warning btn-sm">Edit</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
