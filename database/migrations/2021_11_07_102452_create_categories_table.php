<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->integer("parent_id")->default(0);
            $table->string("name");
            $table->text("description")->nullable();
            $table->text("image")->nullable();
            $table->boolean("status")->default(1);
            $table->timestamps();
        });

        DB::table('categories')->insert([
            'name' => 'Dummy Category'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
