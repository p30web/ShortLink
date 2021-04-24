<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $local_conf = config('shorturl.drivers.local');
        Schema::create($local_conf['table_name'], function (Blueprint $table) use ($local_conf) {
            $table->charset = $local_conf['charset'] ?? "utf8";
            $table->collation = $local_conf['collation'] ?? "utf8_bin";
            $table->increments('id');
            $table->string('long_path', $local_conf['index_key_prefix_size'])->unique();
            $table->string('short_path', 10)->unique();
            $table->string('base_url')->nullable();
            $table->bigInteger('clicks')->nullable()->default(0);
            $table->text('properties')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists(config('shorturl.drivers.local.table_name'));
    }
}

