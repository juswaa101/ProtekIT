@component('mail::message')
# Password Reset Link

Hello, {{ $user->name }}

You are receiving this email because we received a password reset request for your account.

Click the button below to reset your password. Please note that this link will expire in 30 minutes.

@component('mail::button', ['url' => $url])
Reset Password
@endcomponent

If you did not request a password reset, no further action is required.

Thanks,
{{ config('app.name') }}
@endcomponent
