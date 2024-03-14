<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Requests\TodoRequest;
use App\Models\Todo;
use Carbon\Carbon;


class TodoController extends Controller
{
    public function index()
    {
        $todo = Todo::all();
        return view('todo.index',[
            'todo' => $todo
        ]);
    }

    public function store(TodoRequest $request)
    {
        Todo::create([
            'title' => $request->input('title'),
            'desc' => $request->input('desc'),
            'is_completed' => 0,
            'dl' => $request->input('dl')
        ]);

        $request->session()->flash('alert-success', 'Todo Sudah ditambahkan');

        return redirect()->route('todo.index');
    }

    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return redirect()->route('todo.index')->with('alert-success', 'Todo Sudah dihapus');
    }

    public function selesai($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->is_completed = 1;
        $todo->save();

        return redirect()->route('todo.index')->with('alert-success', 'Tugas Selesai Ditandai');
    }

    public function edit($id)
    {
        $todo = Todo::findOrFail($id);
        return view('edit', compact('todo'));
    }


    public function update(TodoRequest $request, $id)
    {
        $todo = Todo::findOrFail($id);
        
        $todo->title = $request->input('title');
        $todo->desc = $request->input('desc');
        $todo->dl = $request->input('dl');
        $todo->save();

        $request->session()->flash('alert-success', 'Task Sudah Diupdate');
        return redirect()->route('todo.update');
    }

    public function search(Request $request)
    {
        $query = Request::input('q');
        
        if (!$query) {
            $todo = Todo::all();
        } else {
            $todo = Todo::where('title', 'like', '%' . $query . '%')->get();
        }
    
        return view('todo.index', compact('todo', 'errorMessage'));
    }
}