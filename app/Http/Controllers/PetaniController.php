<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetaniController extends Controller
{
    public function store(Request $request){

        DB::table('petani')->insert([
                'nama_petani'=>$request->nama,
                'no_telepon'=>$request->telepon
        ]);
        return redirect('/dashboard');
    }

    public function edit($id){
        $petani = DB::table('petani')
                            ->where('id_petani', $id)
                            ->get();
        return view('Edit.editpetani',['petani'=>$petani]);
    }

    public function update(Request $request){
        DB::table('petani')
                    ->where('id_petani', $request->id)
                    ->update(['nama_petani'=>$request->nama, 'no_telepon'=>$request->no]);
                    //return dd($_POST['id']);
       return redirect('/dashboard');
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            // 1. Hapus riwayat pembelian pupuk yang mengacu langsung ke id_petani
            DB::table('riwayat_pembelian_pupuk')->where('id_petani', $id)->delete();

            // 2. Ambil semua id_lahan milik petani ini
            $lahanIds = DB::table('lahan')->where('id_petani', $id)->pluck('id_lahan');

            if ($lahanIds->isNotEmpty()) {
                // 3. Hapus riwayat tanam yang mengacu ke id_lahan
                DB::table('riwayat_tanam')->whereIn('id_lahan', $lahanIds)->delete();
            }

            // 4. Hapus data anak di tabel lahan
            DB::table('lahan')->where('id_petani', $id)->delete();

            // 5. Terakhir hapus data induk di tabel petani
            DB::table('petani')->where('id_petani', $id)->delete();
        });

        return redirect('/dashboard');
    }

}
