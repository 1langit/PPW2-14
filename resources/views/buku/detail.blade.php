@extends('layout.master')

@section('title', 'Detail')

@section('content')
    <div class="container mt-5 mb-3">
        <h4 class="text-center mb-4">Detail Buku</h4>
        <div class="row d-flex justify-content-center">
            <div class="col-auto">
                <img src="{{ $buku->filepath }}">
            </div>
            <div class="col-auto">
                <table class="mt-2">
                    <tr><td>Judul buku</td><td class="py-1 ps-4 pe-2">:</td><td>{{ $buku->judul }}</td></tr>
                    <tr><td>Penulis</td><td class="py-1 ps-4 pe-2">:</td><td>{{ $buku->penulis }}</td></tr>
                    <tr><td>Tanggal terbit</td><td class="py-1 ps-4 pe-2">:</td><td>{{ $buku->tgl_terbit }}</td></tr>
                    <tr><td>Harga</td><td class="py-1 ps-4 pe-2">:</td><td>{{ $buku->harga }}</td></tr>
                    <tr>
                        <td>Rating</td><td class="py-1 ps-4 pe-2">:</td>
                        <td>{{ $rating }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <h4 class="text-center mb-4 mt-5">Gallery</h4>
        <div class="row d-flex justify-content-center px-2 mt-1">
            @foreach ($buku->galleries()->get() as $gallery)
                <div class="col-4 d-flex-column mb-2 px-1">
                    <a href="{{ asset($gallery->path) }}" data-lightbox="image-1">
                        <img class="w-100" src="{{ asset($gallery->path) }}" alt="gallery">
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mt-5 mb-2">
            @if (Auth::check())
                <form method="POST" action="{{ route('buku.addrating') }}" class="d-flex justify-content-center">
                    @csrf
                    <input name="buku_id" type="text" hidden value="{{ $buku->id }}">
                    <div class="input-group w-25">
                        <span class="input-group-text text-bold rounded-0">Berikan rating</span>
                        <input name="rating" type="number" min="1" max="5" placeholder="1-5" class="form-control" required>
                        <button class="btn btn-primary rounded-0" type="submit">Submit</button>
                    </div>
                </form>
                <div class="d-flex">
                    <a href="/buku" class="btn btn-primary rounded-0 mt-4 me-2">Kembali</a>
                    <form method="POST" action="{{ route('buku.addfavourite') }}">
                        @csrf
                        <input name="buku_id" type="text" hidden value="{{ $buku->id }}">
                        <button class="btn btn-primary rounded-0 mt-4" type="submit">Simpan ke favorit</button>
                    </form>
                </div>
            @else
                <a href="/" class="btn btn-primary rounded-0">Kembali</a>
            @endif
        </div>
    </div>
@endsection