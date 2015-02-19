<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('logs', function($table) {
            $table->increments('_id');
            $table->string('HOST');
            $table->string('SRC-NUMBER-IN');
            $table->string('DST-NUMBER-IN');
            $table->string('SRC-NUMBER-OUT');
            $table->string('DST-NUMBER-OUT');
            $table->string('SRC-NUMBER-BILL');
            $table->string('DST-NUMBER-BILL');
            $table->string('SRC-IP');
            $table->string('DST-IP');
            $table->string('SRC-USER');
            $table->string('SRC-NAME');
            $table->string('DST-NAME');
            $table->string('DIALPEER-NAME');
            $table->string('INITIAL-INCOMING-LOCAL-ADDRESS');
            $table->string('SELECTED-INCOMING-LOCAL-ADDRESS');
            $table->string('RECORD-ID');
            $table->string('ELAPSED-TIME');
            $table->string('SETUP_TIME_TIME');
            $table->string('SETUP_TIME_DATE');
            $table->string('CONNECT_TIME_TIME');
            $table->string('CONNECT_TIME_DATE');
            $table->string('DISCONNECT_TIME_TIME');
            $table->string('DISCONNECT_TIME_DATE');
            $table->string('DISCONNECT-CODE-LOCAL');
            $table->string('DISCONNECT-CODE-Q931');
            $table->string('SRC-BYTES-IN');
            $table->string('DST-BYTES-IN');
            $table->string('SRC-BYTES-OUT');
            $table->string('DST-BYTES-OUT');
            $table->string('QOS');
            $table->string('CALLID');
            $table->string('CONFID');
            $table->string('PROXY-MODE');
            $table->string('ROUTE-RETRIES');
            $table->string('SCD-TIME');
            $table->string('SOURCE-TUNNELLING');
            $table->string('PDD-TIME');
            $table->string('PDD-REASON');
            $table->string('SRC-RTP-IP');
            $table->string('DST-RTP-IP');
            $table->string('CONVERTER-NAME');
            $table->string('CONVERTER-IP');
            $table->string('DST-USER');
            $table->string('OUTGOING-LOCAL-ADDRESS');
            $table->string('SRC-CODEC');
            $table->string('DST-CODEC');
            $table->string('SOURCE-FASTSTART');
            $table->string('DESTINATION-FASTSTART');
            $table->string('DESTINATION-TUNNELLING');
            $table->string('LAR-FAULT-REASON');
            $table->string('LAST-CHECKED-DIALPEER');
            $table->string('phoneCode');
            $table->string('Region');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('Logs');
    }

}
