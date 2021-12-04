<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->string("logo")->nullable();
            $table->string("store_name");
            $table->string("store_tagline");
            $table->string("contact");
            $table->string("email");
            $table->string("currency");
            $table->timestamps();
        });

        // Insert Admin Details
        DB::table('websites')->insert([
            'store_name' => 'The Metal Edge',
            'store_tagline' => 'The Metal Edge',
            'contact' => '9636200102',
            'email' => 'sumersingh1997.ssh@gmail.com',
            'currency' => 'USD'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('websites');
    }
}
