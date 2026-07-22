<?php

declare(strict_types=1);

namespace Varsite\Audio\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Varsite\Audio\Enums\AudioSampleStatus;
use Varsite\Platform\Contracts\MediaLibrary;

final class StoreSampleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:audio_categories,id'],
            'description' => ['nullable', 'string'],
            'media_id' => ['required', 'integer', $this->mediaExists()],
            'status' => ['sometimes', Rule::enum(AudioSampleStatus::class)],
        ];
    }

    protected function mediaExists(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail): void {
            if (app(MediaLibrary::class)->find((int) $value) === null) {
                $fail('Wskazany plik media nie istnieje.');
            }
        };
    }
}
