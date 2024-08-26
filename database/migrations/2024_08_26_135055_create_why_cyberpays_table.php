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
        Schema::create('why_cyberpays', function (Blueprint $table) {
            $table->id();
            $table->longText('title');
            $table->string('image_url')->nullable();
            $table->string('caption')->nullable();
            $table->longText('description')->nullable();
            $table->string('benefitCaption')->nullable();
            $table->json('benefitList')->nullable();
            $table->longText('why_us')->nullable();
            $table->text('benefitMerchantCaption')->nullable();
            $table->json('benefitMerchantList')->nullable();
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
        Schema::dropIfExists('why_cyberpays');
    }
};
