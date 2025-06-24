<?php

namespace Botble\ACL\Http\Requests;

use Botble\Support\Http\Requests\Request;

class RegisterRequest extends Request
{
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:120',
            'last_name' => 'required|string|max:120',
            'username' => 'nullable|string|max:60|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => __('acl/auth.register.validation.first_name_required'),
            'last_name.required' => __('acl/auth.register.validation.last_name_required'),
            'email.required' => __('acl/auth.register.validation.email_required'),
            'email.unique' => __('acl/auth.register.email_exist'),
            'username.unique' => __('acl/auth.register.username_exist'),
            'password.required' => __('acl/auth.register.validation.password_required'),
            'password.min' => __('acl/auth.register.validation.password_min'),
            'password.confirmed' => __('acl/auth.register.validation.password_confirmed'),
        ];
    }
}
