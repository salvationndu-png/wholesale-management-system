<?php

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\LoginRequest;

class CustomLoginRequest extends LoginRequest
{
    public function rules(): array
    {
        return [
            'login'    => ['nullable', 'string'], // dummy username field
            'password' => ['required', 'string'],
        ];
    }

    public function credentials(): array
    {
        return [
            'login'    => $this->input('login', ''), // dummy
            'password' => $this->input('password'),
        ];
    }
}
