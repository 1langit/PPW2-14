@extends('layout.master')

@section('title', 'Favorit')

@section('content')
<x-app-layout>
    <div class="container">
        <h1 class="h1 py-3">Buku Favoritku</h1>
        <table class="table table-hover text-center">
            <thead class="table-primary">
                <tr>
                    <td>Buku</td>
                    <td>Judul</td>
                    <td>Penulis</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($buku as $fav)
                    <tr>
                        <td class="d-flex justify-content-center">
                            @if ($fav->filepath)
                                <div class="h-50 w-50 content-center">
                                    <img class="object-center" src="{{ asset($fav->filepath) }}" alt="thumbnail">
                                </div>
                            @endif</td>
                        <td>{{ $fav->judul }}</td>
                        <td>{{ $fav->penulis }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>