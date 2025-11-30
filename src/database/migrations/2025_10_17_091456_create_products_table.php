<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('condition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->string('image');
            $table->string('name');
            $table->integer('price');
            $table->string('brand')->nullable();
            $table->text('description');
            $table->text('shipping_address')->nullable();
            $table->enum('status', ['available', 'sold'])->default('available');
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
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['condition_id']);

            if (Schema::hasColumn('products', 'payment_id')) {
                $table->dropForeign(['payment_id']);
            }

            if (Schema::hasColumn('products', 'seller_id')) {
                $table->dropForeign(['seller_id']);
            }

            if (Schema::hasColumn('products', 'buyer_id')) {
                $table->dropForeign(['buyer_id']);
            }
        });
        Schema::dropIfExists('products');
    }
}
