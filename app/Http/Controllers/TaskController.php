<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TaskController extends Controller
{
    public function index()
    {
        return Task::take(50)->get()->keyBy('id');
    }

    public function store(Request $request)
    {
       return Task::create($request->only('name'))->fresh();
        
    }

    public function destroy($id)
    {
        return Task::destroy($id);
    }

    public function update($id, Request $request)
    { 
        $task = Task::find($id)->fill($request->all())->save();
        return $task->fresh();
    }
}
