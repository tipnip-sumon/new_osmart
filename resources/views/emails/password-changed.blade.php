<x-mail::message>
# Password Changed Successfully

Hello {{ $user->name ?? 'User' }},

This email confirms that your password has been successfully changed for your account at {{ config('app.name') }}.

@if($newPassword)
<x-mail::panel>
## 🔐 Your New Password

**New Password:** `{{ $newPassword }}`

⚠️ **IMPORTANT SECURITY NOTICE:**
- Please log in immediately and consider changing this password
- Delete this email after copying your password
- Never share this password with anyone
- This email contains sensitive information
</x-mail::panel>
@endif

## Details:
- **Date & Time:** {{ $changeTime->format('F j, Y \a\t g:i A') }}
- **IP Address:** {{ $ipAddress ?? 'Unknown' }}
- **Browser:** {{ $userAgent ? substr($userAgent, 0, 100) . (strlen($userAgent) > 100 ? '...' : '') : 'Unknown' }}

If you did not make this change, please contact our support team immediately or reset your password using the button below.

<x-mail::button :url="route('member.profile')" color="success">
View My Profile
</x-mail::button>

<x-mail::panel>
**Security Tip:** For your account security, we recommend:
- Using a strong, unique password
- Enabling two-factor authentication when available
- Never sharing your password with anyone
- Logging out from shared devices
- Delete this email after reading
</x-mail::panel>

If you have any questions or concerns about your account security, please don't hesitate to contact our support team.

Thanks,<br>
{{ config('app.name') }} Security Team
</x-mail::message>
