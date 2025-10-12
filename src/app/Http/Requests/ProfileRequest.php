<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'image' => ['nullable', 'image', 'mimes:jpeg,png'],
            'username' => ['required', 'max:20'],
            'postal_code' => ['required', 'string', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required', 'string', ],
            'building' => ['nullable', 'string', ],
        ];
    }

    public function messages()
    {
        return [
            'image.mimes:png,jpeg' => '「.png」または「.jpeg」形式でアップロードしてください',
            'username.required' => 'ユーザー名を入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => 'ハイフンありの8文字で入力してください',
            'address.required' => '住所を入力してください',
        ];
    }
}
