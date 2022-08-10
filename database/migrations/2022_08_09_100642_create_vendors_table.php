<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('clients')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users', 'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');
            $table->text('description');
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(false);
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
        Schema::dropIfExists('vendors');
    }
};
