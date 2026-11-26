<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    private function rules(): array
    {
        return [
            'id_petani'    => 'required|exists:petani,id_petani',
            'id_pupuk'     => 'required|exists:pupuk,id_pupuk',
            'tanggal_beli' => 'required|date',
            'jumlah'       => 'required|integer|min:1',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        DB::table('riwayat_pembelian_pupuk')->insert([
            'id_petani'     => $data['id_petani'],
            'id_pupuk'      => $data['id_pupuk'],
            'tanggal_beli'  => $data['tanggal_beli'],
            'jumlah_karung' => $data['jumlah'],
        ]);

        return redirect('/dashboard')->with('success', 'Pembelian pupuk ditambahkan.');
    }

    public function edit($id)
    {
        $transaksi = DB::table('riwayat_pembelian_pupuk')
            ->where('id_transaksi', $id)
            ->first();
        abort_if(!$transaksi, 404);

        $petani = DB::table('petani')->orderBy('nama_petani')->get();
        $pupuk  = DB::table('pupuk')->get();

        return view('Edit.edittransaksi', [
            'transaksi' => $transaksi,
            'petani'    => $petani,
            'pupuk'     => $pupuk,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate(['id' => 'required|exists:riwayat_pembelian_pupuk,id_transaksi']);
        $data = $request->validate($this->rules());

        DB::table('riwayat_pembelian_pupuk')
            ->where('id_transaksi', $request->id)
            ->update([
                'id_petani'     => $data['id_petani'],
                'id_pupuk'      => $data['id_pupuk'],
                'tanggal_beli'  => $data['tanggal_beli'],
                'jumlah_karung' => $data['jumlah'],
            ]);

        return redirect('/dashboard')->with('success', 'Pembelian pupuk diperbarui.');
    }

    public function delete($id)
    {
        DB::table('riwayat_pembelian_pupuk')->where('id_transaksi', $id)->delete();

        return redirect('/dashboard')->with('success', 'Pembelian pupuk dihapus.');
    }
}