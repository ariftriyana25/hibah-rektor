<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate(['video' => 'required|file|mimes:mp4|max:51200']); // Max 50MB

        $path = $request->file('video')->store('maestro_videos', 'public');

        // Di sini bisa tambah logika process video dengan MediaPipe untuk ekstrak keypoints (gunakan code_execution nanti)
        // Misal: simpan path ke DB atau process langsung

        return redirect()->back()->with('success', 'Video maestro berhasil diupload! Dataset diperbarui.');
    }
}