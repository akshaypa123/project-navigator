<?php
namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class StoreTimelogRequest extends FormRequest {
public function authorize() { return auth()->check(); }
public function rules() { return [
'task_id' => 'required|exists:tasks,id',
'log_date' => 'required|date',
'hours_worked' => 'required|numeric|min:0.01',
'description' => 'nullable|string',
]; }
}