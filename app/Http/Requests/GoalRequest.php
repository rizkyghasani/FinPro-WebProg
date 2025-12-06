<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            // target_amount: Wajib, angka, minimal 10000
            'target_amount' => 'required|numeric|min:10000|decimal:0,2',
            // current_amount: Opsional, angka, harus kurang dari target
            'current_amount' => 'nullable|numeric|lte:target_amount',
            // due_date: Opsional, harus tanggal setelah hari ini
            'due_date' => 'nullable|date|after_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'target_amount.min' => __('Target jumlah minimal harus Rp 10.000.'),
            'current_amount.lte' => __('Jumlah saat ini tidak boleh melebihi jumlah target.'),
            'due_date.after_or_equal' => __('Tanggal target harus hari ini atau di masa depan.'),
        ];
    }
}