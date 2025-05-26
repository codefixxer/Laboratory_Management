<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_categories', function (Blueprint $table) {
            $table->bigIncrements('testCatId');
            $table->unsignedBigInteger('adminId')->nullable();
            $table->string('testCat');
            $table->text('catDetail')->nullable();
                        $table->foreign('adminId')->references('id')->on('users')->onDelete('cascade');
                                    $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('test_categories');
    }
};
