@extends('errors.layout')

@section('title', '419 Sesi Berakhir')
@section('code', '419')
@section('header', 'Sesi Telah Berakhir')

@section('icon')
<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endsection

@section('message')
Sesi login atau formulir keamanan (CSRF Token) Anda telah kadaluarsa demi alasan keamanan. Silakan muat ulang halaman atau login kembali.
@endsection
