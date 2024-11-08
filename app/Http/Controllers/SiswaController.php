<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class SiswaController extends Controller
{
    public function index()
    {
        try {
            return Siswa::all();
        } catch (\Exception $e) {
            Log::error('error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data siswa.'], 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:10',
            'umur' => 'required|integer',
        ]);

        try {
            $siswa = Siswa::create($validatedData);

            return response()->json($siswa, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data siswa.'], 500);
        }
    }

    public function show($id)
    {
        try {
            return Siswa::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Siswa tidak ditemukan.'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => ['sometimes', 'required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'kelas' => ['sometimes', 'required', 'string', 'max:10', 'regex:/^(X|XI|XII)\s(IPA|IPS)\s[1-9]$/'],
            'umur' => 'sometimes|required|integer|min:6|max:18'
        ], [
            'nama.regex' => 'Nama hanya boleh mengandung huruf dan spasi',
            'kelas.regex' => 'Format kelas harus seperti "XII IPA 1"',
            'umur.min' => 'Umur minimal adalah 6 tahun',
            'umur.max' => 'Umur maksimal adalah 18 tahun'
        ]);
        try {
            $siswa = Siswa::findOrFail($id);

            $validatedData = $request->validate([
                'nama' => 'sometimes|required|string|max:255',
                'kelas' => 'sometimes|required|string|max:10',
                'umur' => 'sometimes|required|integer',
            ]);

            $siswa->update($validatedData);

            return response()->json($siswa);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memperbarui data siswa.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data siswa.'], 500);
        }
    }
}
