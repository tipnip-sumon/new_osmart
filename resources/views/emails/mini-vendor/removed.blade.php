<x-mail::message>
# Mini Vendor Assignment Removed

Hello **{{ $affiliate->name }}**,

This is to inform you that your **Mini Vendor assignment** with {{ $mainVendor->name }} has been removed.

## What This Means

Your account has been **reverted from Vendor back to Affiliate** status:

- 📊 **Dashboard Access:** You no longer have access to vendor features
- 💰 **Commission:** No new commission earnings will be generated
- 🔄 **Role Change:** Your role has been changed back to Affiliate
- 📈 **Account Status:** Your account remains active with affiliate privileges

## Assignment Summary

- **Was assigned by:** {{ $mainVendor->name }}
- **Total commission earned:** ৳{{ number_format($totalCommissionEarned, 2) }}
- **Assignment period:** Until today
- **Final status:** Removed

## Your Commission Earnings

@if($totalCommissionEarned > 0)
During your time as a mini vendor, you earned a total of **৳{{ number_format($totalCommissionEarned, 2)}}** in commissions. These earnings remain in your account.
@else
No commission earnings were generated during your mini vendor period.
@endif

## What You Can Still Do

As an **Affiliate**, you can:
- ✅ Continue using your account normally
- ✅ Access affiliate-level features
- ✅ Participate in affiliate programs
- ✅ Keep all your existing earnings

<x-mail::button :url="$loginUrl" color="primary">
Login to Your Account
</x-mail::button>

## Questions?

If you have any questions about this change or your account status, please contact support.

Thanks,<br>
{{ config('app.name') }} Team

---
*This is an automated notification. Please do not reply to this email.*
</x-mail::message>
