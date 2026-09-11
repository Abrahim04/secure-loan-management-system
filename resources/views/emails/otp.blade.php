<x-mail::message>
# Your Verification Code

Use the code below to complete your sign-in:

<x-mail::panel>
<div style="font-size: 28px; letter-spacing: 8px; text-align: center; font-weight: bold;">
{{ $otpCode }}
</div>
</x-mail::panel>

This code will expire in **{{ $expiryMinutes }} minute(s)** and can only be used once.

If you did not request this code, you can safely ignore this email — do not share this code with anyone.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
