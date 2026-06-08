<?php

namespace App\Http\Controllers\Api\Concerns;

trait FormatsPhotoUrl
{
    protected function formatPhoto(?string $path): ?string
    {
        return $path ? asset($path) : null;
    }
}
