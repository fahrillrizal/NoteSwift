@extends ('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">{{__('Dashboard')}}</div>
                <div class="card-body">
                    @if (Session::has('alert-success'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('alert-success') }}
                    </div>
                    @endif
                    
                    <form action="{{ route('todo.search') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Search by title" autocomplete="off">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                    
                    @if (count($todo) > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Progress</th>
                                <th scope="col">Deadline</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($todo as $task)
                            <tr>
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->desc }}</td>
                                <td>
                                    @if ($task->is_completed == 1)
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-danger">On Progress</span>
                                    @endif
                                </td>
                                <td>{{ $task->dl }}</td>
                                <td>
                                <button type="button" class="btn btn-sm btn-info edit-task" data-toggle="modal" data-target="#editModal" data-id="{{ $task->id }}" data-title="{{ $task->title }}" data-desc="{{ $task->desc }}" data-dl="{{ $task->dl }}">Edit</button>

                                    <form action="{{ route('todo.destroy', $task->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>

                                    @if ($task->is_completed == 0)
                                        <form action="{{ route('todo.selesai', $task->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Selesai</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    @else
                    <h4>Tidak Ada Task</h4>
                    @endif
                    <button type="button" class="btn btn-primary add-task" data-toggle="modal" data-target="#popupForm" data-mode="add">Add Task</button>
                </div>
            </div>
        </div>
    </div>
</div>

 <!-- Modal create -->
 <div class="modal fade" id="popupForm" tabindex="-1" role="dialog" aria-labelledby="popupFormLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="popupFormLabel">Add Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('todo.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" autofocus required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="desc" class="form-control" cols="5" rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deadline</label>
                        <input type="datetime-local" name="dl" class="form-control"></input>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" onclick="submitForm()">Add Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="editForm" method="post" action="{{ route('todo.update', ['id' => $todo->id]) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input id="editTitle" type="text" name="title" class="form-control" value="{{ $todo->title }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea id="editDesc" name="desc" class="form-control" cols="5" rows="5">{{ $todo->desc }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deadline</label>
                    <input id="editDl" type="datetime-local" name="dl" class="form-control" value="{{ $todo ? \Carbon\Carbon::parse($todo->dl)->format('Y-m-d\TH:i') : '' }}">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Task</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>

            </div>
        </div>
    </div>
</div>

@endsection