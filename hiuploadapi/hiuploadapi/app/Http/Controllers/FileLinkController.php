<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\FileResource;
use Illuminate\Support\Facades\Storage;

class FileLinkController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function show(Request $request, File $file)
    {
        $file = File::whereUuid($file->uuid)
            ->whereHas('links', function ($query) use ($request) {
                $query->where('token', $request->token);
            })
            ->firstorFail();

        return (new FileResource($file))
            ->additional([
                'meta' => [
                    'url' => Storage::disk('s3')->temporaryUrl(
                        $file->path,
                        now()->addHours(2),
                        [
                            'ResponseContentType' => 'application/octet-stream', //forces Html, txt, etc files to be downloaded
                            'ResponseContentDisposition' => 'attachment; filename=' . $file->name
                        ]
                    )
                ]
            ]);
    }

    public function store(Request $request, File $file)
    {
        $this->authorize('create-link', $file);

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
