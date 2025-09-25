<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            // Company Basic Information
            $table->string('company_name', 255)->nullable()->after('site_name');
            $table->text('company_address')->nullable()->after('company_name');
            $table->string('company_phone', 20)->nullable()->after('company_address');
            $table->string('company_email', 100)->nullable()->after('company_phone');
            $table->string('company_website', 100)->nullable()->after('company_email');
            
            // Legal & Tax Information  
            $table->string('company_tin', 50)->nullable()->after('company_website');
            $table->string('company_trade_license', 50)->nullable()->after('company_tin');
            $table->string('company_vat_number', 50)->nullable()->after('company_trade_license');
            $table->string('contact_person', 100)->nullable()->after('company_vat_number');
            $table->string('contact_designation', 100)->nullable()->after('contact_person');
            
            // Bank Information
            $table->string('bank_name', 100)->nullable()->after('contact_designation');
            $table->string('bank_account_name', 100)->nullable()->after('bank_name');
            $table->string('bank_account_number', 50)->nullable()->after('bank_account_name');
            $table->string('bank_routing_number', 20)->nullable()->after('bank_account_number');
            $table->string('bank_swift_code', 20)->nullable()->after('bank_routing_number');
            
            // Company Logo
            $table->string('company_logo')->nullable()->after('bank_swift_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'company_address', 
                'company_phone',
                'company_email',
                'company_website',
                'company_tin',
                'company_trade_license',
                'company_vat_number',
                'contact_person',
                'contact_designation',
                'bank_name',
                'bank_account_name',
                'bank_account_number',
                'bank_routing_number',
                'bank_swift_code',
                'company_logo'
            ]);
        });
    }
};
