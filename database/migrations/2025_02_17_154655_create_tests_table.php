<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->bigIncrements('addTestId');

            $table->string('testName');

            $table->unsignedBigInteger('testCatId')->nullable();

            $table->decimal('testCost', 8, 2)->default(0);

            $table->text('howSample')->nullable();

            $table->string('typeSample')->nullable();


            $table->foreign('testCatId')
                  ->references('testCatId')->on('test_categories')
                  ->onDelete('set null');
                              $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('tests');
    }
};
