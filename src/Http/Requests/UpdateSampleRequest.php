<?php

declare(strict_types=1);

namespace Varsite\Audio\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Varsite\Audio\Enums\AudioSampleStatus;
use Varsite\Platform\Contracts\MediaLibrary;

final class UpdateSampleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:audio_categories,id'],
            'description' => ['nullable', 'string'],
            'media_id' => ['sometimes', 'integer', function (string $a, mixed $v, \Closure $fail): void {
                if (app(MediaLibrary::class)->find((int) $v) === null) {
                    $fail('Wskazany plik media nie istnieje.');
                }
            }],
            'status' => ['sometimes', Rule::enum(AudioSampleStatus::class)],
        ];
    }
}
