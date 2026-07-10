<?php

namespace App\Http\Requests\ShowSeat;

use Illuminate\Foundation\Http\FormRequest;

class BookSeatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}