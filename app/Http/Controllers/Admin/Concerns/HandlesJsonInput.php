<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait HandlesJsonInput
{
    protected function decodeOptionalJson(Request $request, string $field): ?array
    {
        $value = $request->input($field);

        if ($value === null || $value === '') {
            return null;
        }

        $decoded = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            throw ValidationException::withMessages([
                $field => "The {$field} field must contain valid JSON object or array data.",
            ]);
        }

        return $decoded;
    }
}
