<?php

namespace App\Http\Requests\Challenge;

use App\Enums\ChallengeFrequency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateChallengeRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'frequency' => [
                'sometimes',
                'string',
                new Enum(ChallengeFrequency::class),
            ],
            'is_public' => 'sometimes|boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'checkin_deadline' => 'required|date_format:H:i',
            'price_per_miss' => 'required|integer|min:0',
            'price_early_leave' => 'required|integer|min:0',
            'coins_per_checkin' => 'required|integer|min:0',
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
