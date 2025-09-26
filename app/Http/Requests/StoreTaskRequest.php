<?php
namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class StoreTaskRequest extends FormRequest {
public function authorize() { return auth()->check(); }


public function rules() {
return [
'title' => 'required|string|max:255',
'description' => 'nullable|string',
'assignee_id' => 'nullable|exists:users,id',
'due_date' => 'nullable|date',
'priority' => 'nullable|in:low,medium,high'
];
}
}