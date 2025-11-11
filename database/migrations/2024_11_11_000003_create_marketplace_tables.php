<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Item Categories
        Schema::create('item_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('item_categories')->onDelete('cascade');
        });

        // Items
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('name');
            $table->text('description');
            $table->unsignedBigInteger('item_category_id');
            $table->uuid('user_uuid');
            $table->boolean('is_promoted')->default(false);
            $table->integer('number_of_sales')->default(0);
            $table->integer('number_of_views')->default(0);
            $table->uuid('reviewed_by_user_uuid')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('item_category_id')->references('id')->on('item_categories')->onDelete('cascade');
            $table->foreign('user_uuid')->references('uuid')->on('users')->onDelete('cascade');
            $table->index('user_uuid');
            $table->index('item_category_id');
        });

        // Packages
        Schema::create('packages', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // 'mail' or 'drop'
            $table->uuid('item_uuid');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('country_name_en_shipping_from')->nullable();
            $table->string('country_name_en_shipping_to')->nullable();
            $table->integer('drop_city_id')->nullable();
            $table->uuid('city_metro_station_uuid')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('item_uuid')->references('uuid')->on('items')->onDelete('cascade');
            $table->index('item_uuid');
        });

        // Package Prices
        Schema::create('package_prices', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('currency', 10);
            $table->decimal('price', 15, 2);
            
            $table->foreign('uuid')->references('uuid')->on('packages')->onDelete('cascade');
        });

        // Rating Reviews
        Schema::create('rating_reviews', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('item_uuid');
            $table->uuid('user_uuid');
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();
            
            $table->foreign('item_uuid')->references('uuid')->on('items')->onDelete('cascade');
            $table->foreign('user_uuid')->references('uuid')->on('users')->onDelete('cascade');
            $table->index('item_uuid');
            $table->index('user_uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_reviews');
        Schema::dropIfExists('package_prices');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('items');
        Schema::dropIfExists('item_categories');
    }
};
