<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("price");
            $table->string("discount_price")->nullable();
            $table->string("tag")->nullable();
            $table->text("description")->nullable();
            $table->integer("category")->nullable();
            $table->integer("subcategory")->nullable();
            $table->string("image")->nullable()->default('https://www.bastiaanmulder.nl/wp-content/uploads/2013/11/dummy-image-square.jpg');
            $table->boolean("status")->default(0);
            $table->timestamps();
        });

        // Insert Blog Details
        DB::table('products')->insert([
            'name' => 'dummy product',
            'price' => '0',
            'discount_price' => '0',
            'image' => 'https://www.bastiaanmulder.nl/wp-content/uploads/2013/11/dummy-image-square.jpg',
            'description' => 'Dummy content',
            'category' => 0,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
