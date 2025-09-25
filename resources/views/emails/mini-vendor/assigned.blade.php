<x-mail::message>
# 🎉 Congratulations! You're Now a Mini Vendor

Hello **{{ $affiliate->name }}**,

Great news! You have been assigned as a **Mini Vendor** by {{ $mainVendor->name }}.

## What This Means For You

Your account has been **upgraded from Affiliate to Vendor** status, which gives you access to:

- 📊 **Vendor Dashboard** - Manage your mini vendor operations
- 💰 **Commission Earnings** - Earn {{ $commissionRate }}% on qualifying transactions
- 🎯 **Enhanced Features** - Access to vendor-level functionality
- 📈 **Business Growth** - Opportunity to expand your operations

## Assignment Details

- **Assigned by:** {{ $mainVendor->name }}
- **Commission Rate:** {{ $commissionRate }}%
- **District:** {{ $miniVendor->district ?: 'Not specified' }}
- **Status:** Active
- **Assignment Date:** {{ $miniVendor->created_at->format('F j, Y') }}

## What Happens Next

✅ Your role has been automatically updated to **Vendor**  
✅ You now have access to the vendor dashboard  
✅ Commission tracking is active for transfers ≥ ৳100  
✅ You can start managing your mini vendor operations  

<x-mail::button :url="$dashboardUrl" color="success">
Access Your Vendor Dashboard
</x-mail::button>

## Commission System

When {{ $mainVendor->name }} transfers **৳100 or more** to you, a **{{ $commissionRate }}% commission** will be automatically added to the transfer amount.

## Need Help?

If you have any questions about your new mini vendor status or how to use the vendor features, please contact support.

Thanks,<br>
{{ config('app.name') }} Team

---
*This is an automated notification. Please do not reply to this email.*
</x-mail::message>
