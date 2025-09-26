<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubtaskRequest;
use App\Models\Subtask;
use App\Models\TaskLog;


class SubtaskController extends Controller {
public function store(StoreSubtaskRequest $request) {
$subtask = Subtask::create($request->validated());
TaskLog::create(['task_id'=>$subtask->task_id,'user_id'=>auth()->id(),'action'=>'created_subtask','meta'=>json_encode($subtask->toArray())]);
return response()->json(['message'=>'Subtask created','subtask'=>$subtask], 201);
}


public function update(Request $request, Subtask $subtask) {
$subtask->update($request->only(['title','is_completed']));
TaskLog::create(['task_id'=>$subtask->task_id,'user_id'=>auth()->id(),'action'=>'updated_subtask','meta'=>json_encode($subtask->toArray())]);
return response()->json(['message'=>'Subtask updated']);
}


public function destroy(Subtask $subtask) {
$subtask->delete();
return response()->json(['message'=>'Subtask deleted']);
}
}