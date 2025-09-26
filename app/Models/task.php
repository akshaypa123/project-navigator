<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Task extends Model {
use HasFactory;


protected $fillable = ['title','description','assignee_id','due_date','priority','created_by'];


protected $casts = ['due_date' => 'date'];


public function subtasks() {
     return $this->hasMany(Subtask::class);
     }
public function checklists() { 
    return $this->hasMany(Checklist::class);
 }
public function timelogs() { 
    return $this->hasMany(Timelog::class); 
}
public function logs() { 
    return $this->hasMany(TaskLog::class);
 }
public function assignee() {
     return $this->belongsTo(User::class, 'assignee_id');
     }
public function creator() { 
    return $this->belongsTo(User::class, 'created_by');
 }
}