<?php

namespace App\Http\Requests\Challenge;

use App\Enums\ChallengeFrequency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateChallengeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'frequency' => [
                'sometimes',
                'string',
                new Enum(ChallengeFrequency::class),
            ],
            'is_public' => 'sometimes|boolean',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'checkin_deadline' => 'sometimes|date_format:H:i',
            'price_per_miss' => 'sometimes|integer|min:0',
            'price_early_leave' => 'sometimes|integer|min:0',
            'coins_per_checkin' => 'sometimes|integer|min:0',
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
        ];
    }
}
