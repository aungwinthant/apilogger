<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateApiLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_logs', function(Blueprint $table) {
            $table->renameColumn('response', 'status_code');
        });
        Schema::table('api_logs', function(Blueprint $table) {
            $table->integer('status_code')->change();
            $table->longText('payload_raw')->nullable()->after('payload');
            $table->longText('response')->after('payload_raw');
            $table->longText('response_headers')->after('response');
            $table->text('headers')->nullable()->after('response');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_logs', function(Blueprint $table) {
            $table->dropColumn('response');
            $table->dropColumn('response_headers');
            $table->dropColumn('payload_raw');
            $table->dropColumn('headers');
        });

        Schema::table('api_logs', function(Blueprint $table) {
            $table->renameColumn('status_code', 'response');
        });

        Schema::table('api_logs', function(Blueprint $table) {
            $table->longText('response')->change();
        });
    }
}
