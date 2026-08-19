<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaUploadController extends Controller
{
    // Endpoint utilisé par le plugin SimpleUploadAdapter de CKEditor.
    public function upload(Request $request)
    {
        $request->validate([
            'upload' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp,mp4,webm', 'max:20480'],
        ]);

        $chemin = $request->file('upload')->store('chapitres-media', 'public');

        return response()->json([
            'url' => Storage::url($chemin),
        ]);
    }
}
