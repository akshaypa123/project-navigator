<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Models\TaskLog;
use Illuminate\Http\Request;


class TaskController extends Controller {
public function index(Request $request)
{
    $mine = $request->query('mine', '1');
    $query = Task::with(['subtasks','checklists','timelogs','assignee','creator'])
                 ->orderBy('created_at','desc');
    if ($mine === '1') {
        $query->where('created_by', auth()->id());
    }
    $tasks = $query->get();
    
    return view('tasks.index', compact('tasks')); // send to Blade
}


public function store(StoreTaskRequest $request) {
$data = $request->validated();
$data['created_by'] = auth()->id();
$task = Task::create($data);
TaskLog::create(['task_id'=>$task->id,'user_id'=>auth()->id(),'action'=>'created_task','meta'=>json_encode($task->toArray())]);
return response()->json(['message'=>'Task added successfully','task'=>$task], 201);
}


public function show(Task $task) {
return response()->json($task->load(['subtasks','checklists','timelogs','logs','assignee','creator']));
}


public function update(StoreTaskRequest $request, Task $task) {
$task->update($request->validated());
TaskLog::create(['task_id'=>$task->id,'user_id'=>auth()->id(),'action'=>'updated_task','meta'=>json_encode($task->toArray())]);
return response()->json(['message'=>'Task updated successfully']);
}


public function destroy(Task $task) {
$task->delete();
return response()->json(['message'=>'Task deleted successfully']);
}
}