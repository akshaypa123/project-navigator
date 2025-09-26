<?php
namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class StoreSubtaskRequest extends FormRequest {
public function authorize() { return auth()->check(); }
public function rules() { 
    return [ 'task_id' => 'required|exists:tasks,id', 
    'title' => 'required|string|max:255' ]; 
}
}