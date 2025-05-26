<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('debit', function (Blueprint $table) {
            $table->id('debitAi'); // Primary Key
            $table->unsignedBigInteger('userId'); // Foreign Key for users
            $table->decimal('debitAmount', 10, 2);
            $table->text('debitDetail')->nullable();
            $table->date('createdDate');
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
                        $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('debit');
    }
};
