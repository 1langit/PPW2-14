<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Favourite;
use App\Models\Gallery;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class BukuController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batas = 5;
        $jumlah_data = Buku::count('id');
        $data_buku = Buku::orderBy('id', 'desc')->paginate($batas);
        $no = $batas * ($data_buku->currentPage() - 1);
        $total_harga = Buku::sum('harga');
        if (!Auth::check()) {
            return view('index', compact('data_buku', 'no', 'jumlah_data', 'total_harga'));
        }
        return view('buku.listbuku', compact('data_buku', 'no', 'jumlah_data', 'total_harga'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('buku.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'judul'         => 'required|string',
            'penulis'       => 'required|string|max:30',
            'harga'         => 'required|numeric',
            'tgl_terbit'    => 'required|date',
            'thumbnail'     => 'image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($request->file('thumbnail')) {
            $filename = time().'_'.$request->thumbnail->getClientOriginalName();
            $filepath = $request->file('thumbnail')->storeAs('uploads', $filename, 'public');
            Image::make(storage_path().'/app/public/uploads/'.$filename)->fit(240, 320)->save();
        }
        
        $buku = Buku::create([
            'judul'         => $request->judul,
            'penulis'       => $request->penulis,
            'tgl_terbit'    => $request->tgl_terbit,
            'harga'         => $request->harga,
            'filename'      => $filename ?? null,
            'filepath'      => isset($filename) ? '/storage/'.$filepath : null
        ]);

        if ($request->file(('gallery'))) {
            foreach($request->file('gallery') as $key => $file) {
                $filename = time().'_'.$file->getClientOriginalName();
                $filepath = $file->storeAs('uploads', $filename, 'public');

                Gallery::create([
                    'nama_galeri'   => $filename,
                    'path'          => '/storage/'.$filepath,
                    'foto'          => $filename,
                    'buku_id'       => $buku->id
                ]);
            }
        }

        return redirect('buku')->with('pesan', 'Data buku berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $buku = Buku::find($id);
        return view('buku.edit', compact('buku'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);

        $this->validate($request, [
            'judul'         => 'required|string',
            'penulis'       => 'required|string|max:30',
            'harga'         => 'required|numeric',
            'tgl_terbit'    => 'required|date',
            'thumbnail'     => 'image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($request->file('thumbnail')) {
            $filename = time().'_'.$request->thumbnail->getClientOriginalName();
            $filepath = $request->file('thumbnail')->storeAs('uploads', $filename, 'public');
            Image::make(storage_path().'/app/public/uploads/'.$filename)->fit(240, 320)->save();
        }

        $data = [
            'judul'         => $request->judul,
            'penulis'       => $request->penulis,
            'tgl_terbit'    => $request->tgl_terbit,
            'harga'         => $request->harga,
        ];

        if ($request->file('thumbnail')) {
            $data['filename'] = $filename;
            $data['filepath'] = '/storage/'.$filepath;
        }

        $buku->update($data);

        if ($request->file(('gallery'))) {
            foreach($request->file('gallery') as $key => $file) {
                $filename = time().'_'.$file->getClientOriginalName();
                $filepath = $file->storeAs('uploads', $filename, 'public');

                Gallery::create([
                    'nama_galeri'   => $filename,
                    'path'          => '/storage/'.$filepath,
                    'foto'          => $filename,
                    'buku_id'       => $id
                ]);
            }
        }
        
        return redirect('buku')->with('pesan', 'Data buku berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buku = Buku::find($id);
        $buku->delete();
        return redirect('buku')->with('pesan', 'Data buku berhasil dihapus');
    }

    public function search(Request $request)
    {
        $batas = 5;
        $cari = $request->kata;
        $data_buku = Buku::where('judul', 'like', "%".$cari."%")->orwhere('penulis', 'like', "%".$cari."%")->paginate($batas);
        $jumlah_buku = $data_buku->count();
        $no = $batas * ($data_buku->currentPage() - 1);
        return view('buku.search', compact('jumlah_buku', 'data_buku', 'no', 'cari'));
    }

    public function deletegallery($id)
    {
        $gallery = Gallery::find($id);
        $gallery->delete();
        return redirect()->back();
    }

    public function galbuku($id)
    {
        $buku = Buku::find($id);
        $ratings = Rating::where('buku_id', $id)->get();
        $rating = $ratings->average('rating');
        if ($rating == 0) {
            $rating = "Rating is not available";
        }
        return view('buku.detail', compact('buku', 'rating'));
    }

    public function addrating(Request $request)
    {
        Rating::create([
            'user_id' => Auth::user()->id,
            'buku_id' => $request->buku_id,
            'rating' => $request->rating
        ]);
        return redirect()->back();
    }

    public function addfavourite(Request $request)
    {
        Favourite::create([
            'user_id' => Auth::user()->id,
            'buku_id' => $request->buku_id,
        ]);
        return redirect()->back();
    }

    public function showfavourite()
    {
        $favourites = Favourite::where('user_id', Auth::user()->id)->pluck('buku_id');
        $buku = Buku::whereIn('id', $favourites)->get();
        return view('buku.favorit', compact('buku'));
    }
}
