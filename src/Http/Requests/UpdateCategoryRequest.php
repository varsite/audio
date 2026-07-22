<?php

declare(strict_types=1);

namespace Varsite\Audio\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
