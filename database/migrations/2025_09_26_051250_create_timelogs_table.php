<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('timelogs', function (Blueprint $table) {
$table->id();
$table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
$table->dateTime('log_date');
$table->decimal('hours_worked', 5, 2); // e.g. 1.50 hours
$table->text('description')->nullable();
$table->timestamps();
});
}


public function down(): void {
Schema::dropIfExists('timelogs');
}
};