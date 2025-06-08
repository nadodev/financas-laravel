<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('financial_goal_progress');
    }

    public function down()
    {
        Schema::create('financial_goal_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financial_goal_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
}; 