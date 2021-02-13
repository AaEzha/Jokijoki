<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('twitter')->nullable();
            $table->double('fee', 10)->nullable();
            $table->string('job')->nullable();
            $table->text('note')->nullable();
            $table->date('deal_date')->nullable();
            $table->date('deadline_date')->nullable();
            $table->char('paid', 1)->default('0');
            $table->double('dp', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
