<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $username = $this->input('username');
        $password = $this->input('password');

        // デバッグログ
        \Log::info('Login attempt started', [
            'username' => $username,
            'password_length' => strlen($password),
            'ip' => $this->ip(),
            'user_agent' => $this->userAgent()
        ]);

        // ユーザーIDまたはメールアドレスでユーザーを検索
        \Log::info('Searching for user', ['username' => $username]);
        $user = \App\Models\User::findByUsernameOrEmail($username);
        \Log::info('User search result', ['found' => $user ? 'yes' : 'no']);

        // ユーザーが見つからない場合
        if (!$user) {
            \Log::warning('User not found', ['username' => $username]);
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'username' => 'ユーザーIDまたはパスワードが正しくありません。',
            ]);
        }

        // パスワードを検証
        if (!Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'username' => 'ユーザーIDまたはパスワードが正しくありません。',
            ]);
        }

        // ログイン
        Auth::login($user, $this->boolean('remember'));

        // ログイン成功時はRateLimiterをクリア
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('username')).'|'.$this->ip());
    }
}
