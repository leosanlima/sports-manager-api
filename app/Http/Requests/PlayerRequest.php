<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Permitir que qualquer usuário autenticado faça essa requisição
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'required|string|max:5', 
            'height' => 'nullable|string|max:10', 
            'weight' => 'nullable|integer',
            'jersey_number' => 'nullable|integer',
            'college' => 'nullable|string|max:255',
            'country' => 'required|string|max:100',
            'draft_year' => 'nullable|integer|min:1900|max:'.date('Y'),
            'draft_round' => 'nullable|integer',
            'draft_number' => 'nullable|integer',
            'team_id' => 'required|exists:teams,id', 
        ];
    }
}
