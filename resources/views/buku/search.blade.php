@extends('layout.master')

@section('title', 'index')

@section('content')
    @if (!Auth::check())
        <div class="d-flex justify-content-between navbar navbar-expand-lg bg-white py-3 px-5 mb-5">
            <div class="col-auto ms-5">
                <p class="fs-3 m-0">Toko Buku</p>
            </div>
            <div class="col-auto me-5">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-light border">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-light border me-2">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-light border">Register</a>
                    @endif
                @endauth
            </div>
        </div>
    @endif
    <div class="container">
        <h1>List Buku</h1>
        <div class="d-flex align-items-center my-3">
            <div class="flex-fill me-2">
                @if (Session::has('pesan'))
                    <div class="alert alert-success py-2 px-3 m-0">{{ Session::get('pesan') }}</div>
                @endif
            </div>
            <form action="{{ route('buku.search') }}" method="get">
                @csrf
                <div class="input-group">
                    <input type="text" name="kata" class="form-control flex-fill rounded-0" placeholder="Cari...">
                    <button type="submit" class="btn btn-primary rounded-0"><i class="bi bi-search"></i></button>
                </div>
            </form>
            @if (Auth::check() && Auth::user()->level == 'admin')
                <a href="{{ route('buku.create') }}" class="btn btn-primary rounded-0 ms-2">Tambah Buku</a>
            @endif
        </div>
        <table class="table table-hover text-center">
            <thead class="table-primary">
                <tr>
                    <th>id</th>
                    <th>Buku</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Tgl. Terbit</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_buku as $buku)
                    <tr>
                        <td>{{ ++$no }}</td>
                        <td>
                            @if ($buku->filepath)
                                <div class="relative h-10 w-10">
                                    <img class="object-center" src="{{ asset($buku->filepath) }}" alt="thumbnail">
                                </div>
                            @endif
                        </td>
                        <td>{{ $buku->judul }}</td>
                        <td>{{ $buku->penulis }}</td>
                        <td>{{ date('d/m/y', strtotime($buku->tgl_terbit)) }}</td>
                        <td>{{ "Rp".number_format($buku->harga, 0, ',', '.') }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                @if (Auth::check() && Auth::user()->level == 'admin')
                                    <form method="POST" action="{{ route('buku.edit', $buku->id) }}">
                                        @csrf
                                        <button class="btn btn-warning rounded-0">Edit</button>
                                    </form>
                                    <form method="POST" action="{{ route('buku.destroy', $buku->id) }}" class="mx-1">
                                        @csrf
                                        <button onclick="return confirm('Hapus buku?')" class="btn btn-danger rounded-0">Hapus</button>
                                    </form>
                                @endif
                                <a href="{{ route('galeri.buku', $buku->id) }}" class="btn btn-primary rounded-0">Lihat</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div>{{ $data_buku->links() }}</div>
    </div>
@endsection