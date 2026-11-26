<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PupukController extends Controller
{
    private function rules(): array
    {
        return [
            'nama'       => 'required|string|max:35',
            'jenis'      => 'required|in:Kimia,Organik',
            'kg_per_sak' => 'required|numeric|min:0.1|max:999.99',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        DB::table('pupuk')->insert([
            'nama_pupuk'  => $data['nama'],
            'jenis_pupuk' => $data['jenis'],
            'kg_per_sak'  => $data['kg_per_sak'],
        ]);

        return redirect('/dashboard')->with('success', 'Pupuk ditambahkan.');
    }

    public function edit($id)
    {
        $pupuk = DB::table('pupuk')->where('id_pupuk', $id)->first();
        abort_if(!$pupuk, 404);

        return view('Edit.editpupuk', ['pupuk' => $pupuk]);
    }

    public function update(Request $request)
    {
        $request->validate(['id' => 'required|exists:pupuk,id_pupuk']);
        $data = $request->validate($this->rules());

        DB::table('pupuk')
            ->where('id_pupuk', $request->id)
            ->update([
                'nama_pupuk'  => $data['nama'],
                'jenis_pupuk' => $data['jenis'],
                'kg_per_sak'  => $data['kg_per_sak'],
            ]);

        return redirect('/dashboard')->with('success', 'Pupuk diperbarui.');
    }

    public function delete($id)
    {
        try {
            DB::table('pupuk')->where('id_pupuk', $id)->delete();
        } catch (QueryException $e) {
            // Gagal karena pupuk ini sudah dipakai di riwayat tanam atau pembelian
            return redirect('/dashboard')->with('error', 'Pupuk tidak bisa dihapus karena masih dipakai di data lain.');
        }

        return redirect('/dashboard')->with('success', 'Pupuk dihapus.');
    }
}