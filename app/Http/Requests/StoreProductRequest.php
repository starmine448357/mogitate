<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0|max:10000',
            'image' => 'required|image|mimes:jpeg,png|max:10240',
            'description' => 'required|string|max:120',
            'seasons' => 'required|array',
            'seasons.*' => 'exists:seasons,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '値段を入力してください',
            'price.integer' => '値段は数値で入力してください',
            'price.max' => '値段は10000円以内で入力してください',
            'image.required' => '商品画像を登録してください',
            'image.mimes' => '".png"または".jpeg"形式でアップロードしてください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は120文字以内で入力してください',
            'seasons.required' => '季節を選択してください',
            'image.max' => '画像サイズは10MB以内でアップロードしてください。',

        ];
    }
}
