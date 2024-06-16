<?php

namespace App\Http\Controllers;

// use Illuminate\Support\Facades\Request;
use App\Http\Requests\TodoRequest;
use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $this->updateTaskStatuses();

        $todo = Todo::where('user_id', Auth::id())->get();
        return view('todo.index', [
            'todo' => $todo
        ]);
    }

    private function updateTaskStatuses()
    {
        $todos = Todo::where('user_id', Auth::id())->get();

        foreach ($todos as $todo) {
            if ($todo->is_completed == 0 && Carbon::parse($todo->dl)->isPast()) {
                $todo->is_completed = -1;
                $todo->save();
            }
        }
    }

    public function store(TodoRequest $request)
    {
        Todo::create([
            'title' => $request->input('title'),
            'desc' => $request->input('desc'),
            'is_completed' => 0,
            'dl' => $request->input('dl'),
            'user_id' => Auth::id()
        ]);

        $request->session()->flash('alert-success', 'Todo Sudah ditambahkan');

        return redirect()->route('todo.index');
    }

    public function destroy($id)
    {
        $todo = Todo::where('user_id', Auth::id())->findOrFail($id);
        $todo->delete();

        return redirect()->route('todo.index')->with('alert-success', 'Todo Sudah dihapus');
    }

    public function selesai($id)
    {
        $todo = Todo::where('user_id', Auth::id())->findOrFail($id);
        $todo->is_completed = 1;
        $todo->save();

        return redirect()->route('todo.index')->with('alert-success', 'Tugas Selesai Ditandai');
    }

    public function edit($id)
    {
        $todo = Todo::where('user_id', Auth::id())->findOrFail($id);
        return view('edit', compact('todo'));
    }

    public function update(TodoRequest $request, $id)
    {
        $todo = Todo::where('user_id', Auth::id())->findOrFail($id);

        $todo->title = $request->input('title');
        $todo->desc = $request->input('desc');
        $todo->dl = $request->input('dl');
        $todo->save();

        $request->session()->flash('alert-success', 'Task Sudah Diupdate');
        return redirect()->route('todo.index');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            $todo = Todo::where('user_id', Auth::id())->get();
        } else {
            $todo = Todo::where('user_id', Auth::id())
                ->where('title', 'like', '%' . $query . '%')
                ->get();
        }

        return view('todo.index', ['todo' => $todo]);
    }
}