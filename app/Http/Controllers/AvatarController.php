<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AvatarController extends Controller
{
    /**
     * Streams a user's avatar directly from storage — avoids needing
     * `php artisan storage:link`, which commonly fails on Windows
     * without Administrator privileges or Developer Mode enabled.
     */
    public function show(User $user): StreamedResponse
    {
        abort_unless(
            $user->avatar_path && Storage::disk('public')->exists($user->avatar_path),
            404
        );

        return Storage::disk('public')->response($user->avatar_path);
    }
}
