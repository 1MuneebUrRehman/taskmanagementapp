<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $base = auth()->user()->isSuperAdmin() ? Task::query() : auth()->user()->tasks();
        $query = $base
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('priority'), fn($q) => $q->where('priority', $request->string('priority')))
            ->when($request->filled('archived'), fn($q) => $q->where('archived', filter_var($request->input('archived'), FILTER_VALIDATE_BOOLEAN)))
            ->orderByDesc('created_at');

        $tasks = $query->paginate(10)->withQueryString();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'filters' => $request->only(['status','priority','archived'])
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'is_completed' => ['sometimes','boolean'],
            // Scheduling
            'start_date' => ['nullable','date'],
            'due_date' => ['nullable','date','after_or_equal:start_date'],
            'completed_at' => ['nullable','date'],
            'reminder_at' => ['nullable','date'],
            // Categorization
            'priority' => ['nullable','string', Rule::in(['low','normal','high','urgent'])],
            'status' => ['nullable','string', Rule::in(['pending','in_progress','completed','blocked'])],
            // Collaboration
            'assigned_to' => ['nullable','exists:users,id'],
            // System/meta
            'archived' => ['sometimes','boolean'],
        ]);

        // Defaults
        $data['priority'] = $data['priority'] ?? 'normal';
        $data['status'] = $data['status'] ?? 'pending';
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        // Coherence between status/is_completed/completed_at
        // If explicitly marked completed via checkbox
        if (($data['is_completed'] ?? false) && empty($data['completed_at'])) {
            $data['completed_at'] = now();
            $data['status'] = 'completed';
        }
        // If status provided as completed but is_completed not set
        if (($data['status'] ?? null) === 'completed') {
            $data['is_completed'] = true;
            $data['completed_at'] = $data['completed_at'] ?? now();
        }

        auth()->user()->tasks()->create($data);

        return redirect()->route('tasks.index')->with('success', 'Task created');
    }

    public function create()
    {
        $users = User::select('id','name')->orderBy('name')->get();
        return Inertia::render('Tasks/Create', [
            'users' => $users,
        ]);
    }

    public function show(Task $task)
    {
        return Inertia::render('Tasks/Show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $users = User::select('id','name')->orderBy('name')->get();
        return Inertia::render('Tasks/Edit', [
            'task' => $task,
            'users' => $users,
        ]);
    }


    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $data = $request->validate([
            'title' => ['sometimes','required','string','max:255'],
            'description' => ['nullable','string'],
            'is_completed' => ['sometimes','boolean'],
            // Scheduling
            'start_date' => ['nullable','date'],
            'due_date' => ['nullable','date','after_or_equal:start_date'],
            'completed_at' => ['nullable','date'],
            'reminder_at' => ['nullable','date'],
            // Categorization
            'priority' => ['nullable','string', Rule::in(['low','normal','high','urgent'])],
            'status' => ['nullable','string', Rule::in(['pending','in_progress','completed','blocked'])],
            // Collaboration
            'assigned_to' => ['nullable','exists:users,id'],
            // System/meta
            'archived' => ['sometimes','boolean'],
        ]);

        // Handle status/completion coherence
        if (($data['status'] ?? null) === 'completed' && empty($data['completed_at'])) {
            $data['completed_at'] = now();
            $data['is_completed'] = true;
        }
        if (($data['is_completed'] ?? null) === false) {
            $data['completed_at'] = null;
        }

        $data['updated_by'] = auth()->id();

        $task->update($data);

        return redirect()->back()->with('success', 'Task updated');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete(); // Soft delete
        return redirect()->route('tasks.index')->with('success', 'Task deleted');
    }
}
