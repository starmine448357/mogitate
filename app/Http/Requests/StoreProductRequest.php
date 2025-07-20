<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Storage;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0|max:10000',
            'seasons' => 'required|array|min:1',
            'description' => 'required|string|max:120',
        ];

    // 画像がセッションにないときだけ image を required にする
    if (!session()->has('image_temp_path')) {
        $rules['image'] = 'required|file|mimes:png,jpeg';
    } else {
        $rules['image'] = 'nullable|file|mimes:png,jpeg';
    }

    return $rules;
}

    public function messages(): array
    {
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '値段を入力してください',
            'price.integer' => '数値で入力してください',
            'price.min' => '0~10000円以内で入力してください',
            'price.max' => '0~10000円以内で入力してください',
            'seasons.required' => '季節を選択してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '120文字以内で入力してください',
            'image.required' => '商品画像を登録してください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('image') && $this->file('image')->isValid()) {
                $tempPath = $this->file('image')->store('temp', 'public');
                session([
                    'image_temp_url' => Storage::url($tempPath),
                    'image_temp_name' => $this->file('image')->getClientOriginalName(),
                ]);
            }
        });
    }
}
