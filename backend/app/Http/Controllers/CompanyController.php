<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Portofolio;
use App\Models\SearchHistory;
use App\Http\Controllers\Traits\ValidatesRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    use ValidatesRole;

    public function searchStudents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keyword' => 'nullable|string|max:255',
            'skill' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'fakultas' => 'nullable|string|max:255',
            'universitas' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($error = $this->validatePerusahaan($request)) {
            return $error;
        }

        $result = $this->getPerusahaanOrFail($request);
        if ($result['error']) {
            return $result['error'];
        }
        $perusahaan = $result['perusahaan'];

        $filters = $request->only(['keyword', 'skill', 'jurusan', 'fakultas', 'universitas']);
        $query = Mahasiswa::searchStudents($filters);
        $results = $query->paginate(12);

        // Save search history
        SearchHistory::create([
            'perusahaan_id' => $perusahaan->id,
            'keyword' => $request->keyword,
            'filters' => $request->only(['skill', 'jurusan', 'fakultas', 'universitas']),
            'results_count' => $results->total(),
        ]);

        return response()->json($results);
    }

    public function getStudentPortfolio($id, Request $request)
    {
        $mahasiswa = Mahasiswa::withPublicPortfolioData($id);

        if (!$mahasiswa) {
            return response()->json(['message' => 'Portofolio tidak ditemukan atau tidak publik'], 404);
        }

        return response()->json(['mahasiswa' => $mahasiswa]);
    }

    public function getSearchHistory(Request $request)
    {
        if ($error = $this->validatePerusahaan($request)) {
            return $error;
        }

        $result = $this->getPerusahaanOrFail($request);
        if ($result['error']) {
            return $result['error'];
        }
        $perusahaan = $result['perusahaan'];

        $histories = $perusahaan->searchHistories()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($histories);
    }
}

