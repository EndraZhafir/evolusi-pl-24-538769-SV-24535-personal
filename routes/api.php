<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Saving;

Route::get('/tugas', function () {
    try {
        $savings = Saving::all();
    } catch (\Throwable $e) {
        $savings = collect();
    }

    if ($savings->isEmpty()) {
        $data = [
            ['id' => 1, 'judul' => 'Setup CI/CD Pipeline', 'status' => 'Selesai'],
            ['id' => 2, 'judul' => 'Integrasi Vue 3 ke Laravel', 'status' => 'Proses'],
            ['id' => 3, 'judul' => 'Menulis Unit Test Vitest', 'status' => 'Tertunda'],
        ];
    } else {
        $data = $savings->map(function ($item) {
            return [
                'id' => $item->id,
                'judul' => $item->name,
                'status' => $item->status_label ?? 'Aktif',
            ];
        });
    }

    return response()->json([
        'success' => true,
        'message' => 'Daftar tugas/tabungan berhasil diambil',
        'data' => $data
    ], 200);
});