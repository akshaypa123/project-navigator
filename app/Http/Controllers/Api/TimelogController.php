<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTimelogRequest;
use App\Models\Timelog;
use App\Models\TaskLog;


class TimelogController extends Controller {
public function store(StoreTimelogRequest $request) {
$data = $request->validated();
$data['user_id'] = auth()->id();
$timelog = Timelog::create($data);
TaskLog::create(['task_id'=>$timelog->task_id,'user_id'=>auth()->id(),'action'=>'created_timelog','meta'=>json_encode($timelog->toArray())]);
return response()->json(['message'=>'Timelog added','timelog'=>$timelog], 201);
}


public function update(Request $request, Timelog $timelog) {
$timelog->update($request->only(['log_date','hours_worked','description']));
return response()->json(['message'=>'Timelog updated']);
}


public function destroy(Timelog $timelog) {
$timelog->delete();
return response()->json(['message'=>'Timelog deleted']);
}
}