<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FileLinkController extends Controller
{
    public function store(Request $request, File $file)
    {
        //auth

        $link = $file->links()->firstOrcreate([], [
            'token' => hash_hmac('sha256', Str::random(40), $file->uuid)
        ]);

        return [
            'data' => [
                'url' => config('app.client_url') . '/download/' . $file->uuid . '?token=' . $link->token
            ]
        ];
    }
}
