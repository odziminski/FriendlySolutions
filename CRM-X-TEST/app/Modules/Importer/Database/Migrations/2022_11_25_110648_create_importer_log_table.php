<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('importer_log')) {
            Schema::create('importer_log', function (Blueprint $table) {
                $table->increments('id');
            });
        }

        $columns = Schema::getColumnListing('importer_log');

        Schema::table(
            'importer_log',
            function (Blueprint $table) use ($columns) {
                if (!in_array('id', $columns)) {
                    $table->increments('id');
                }
                if (!in_array('type', $columns)) {
                    $table->string('type');
                }
                if (!in_array('run_at', $columns)) {
                    $table->dateTime('run_at');
                }
                if (!in_array('entries_processed', $columns)) {
                    $table->string('entries_processed');
                }
                if (!in_array('entries_created', $columns)) {
                    $table->string('entries_created');
                }
            }
        );
    }
};
