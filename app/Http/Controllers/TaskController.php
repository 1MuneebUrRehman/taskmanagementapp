<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = auth()->user()->tasks()->paginate(10);
        return Inertia::render('Tasks/Index', ['tasks' => $tasks]);
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required']);
        auth()->user()->tasks()->create($request->only('title', 'description', 'is_completed'));
        return redirect()->back();
    }

    public function create()
    {
        return Inertia::render('Tasks/Create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return Inertia::render('Tasks/Edit', [
            'task' => $task
        ]);
    }


    public function update(Request $request, Task $task)
    {
        $task->update($request->only('title', 'description', 'is_completed'));
        return redirect()->back();
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back();
    }
}
