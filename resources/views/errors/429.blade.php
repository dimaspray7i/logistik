@extends('errors.layout')

@section('title', '429 Terlalu Banyak Permintaan')
@section('code', '429')
@section('header', 'Terlalu Banyak Permintaan')

@section('icon')
<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
</svg>
@endsection

@section('message')
{{ $exception->getMessage() ?: 'Terlalu banyak permintaan yang dikirim dalam waktu singkat (Rate Limit). Silakan tunggu beberapa saat sebelum mencoba kembali.' }}
@endsection
