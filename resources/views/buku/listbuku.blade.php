@extends('layout.master')

@section('title', 'Buku')

@section('content')
<x-app-layout>
    <div class="container py-4 xl:px-32">
        <h1 class="h1 pt-3">List Buku</h1>
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
                        <td class="d-flex justify-content-center">
                            @if ($buku->filepath)
                                <div class="h-50 w-50 content-center">
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
        <div class="row d-flex justify-content-between pb-5">
            <div class="col-auto">
                <div>{{ $data_buku->links() }}</div>
            </div>
            <div class="col-auto">
                <strong>{{ "Jumlah data: ".$jumlah_data }} buku</strong>
                <p>{{ "Total harga: Rp".number_format($total_harga, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
@endsection