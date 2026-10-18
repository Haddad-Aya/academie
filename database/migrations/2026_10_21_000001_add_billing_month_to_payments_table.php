<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->char('billing_month', 7)->nullable()->after('amount');
        });

        // Preserve existing payment history by assigning each legacy payment
        // to the month in which it was created. Leave any duplicate legacy rows
        // for the same registration/month unassigned so the unique index is safe.
        $seen = [];
        DB::table('payments')->select(['id', 'registration_id', 'created_at'])->orderBy('id')->chunkById(500, function ($payments) use (&$seen) {
            foreach ($payments as $payment) {
                $month = substr((string) $payment->created_at, 0, 7);
                $key = $payment->registration_id.':'.$month;

                if (! preg_match('/^\d{4}-\d{2}$/', $month) || isset($seen[$key])) {
                    continue;
                }

                DB::table('payments')->where('id', $payment->id)->update(['billing_month' => $month]);
                $seen[$key] = true;
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->unique(['registration_id', 'billing_month']);
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique(['registration_id', 'billing_month']);
            $table->dropColumn('billing_month');
        });
    }
};
