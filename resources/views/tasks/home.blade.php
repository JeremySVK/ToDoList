@extends('layouts.main')

@section('content')

    @if (session('status'))
        <div class="alert alert-success">
            {!! session('status') !!}
        </div>
    @endif

    {{ $errors->first() }}

    <div class="container">
        <div class="row pt-5 mb-2">
            <div class="col-12">
                <div class="d-grid gap-2 d-md-block">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">New Task</button>
                    <button type="button" class="btn btn-primary">New Tag</button>
                </div>
            </div>
        </div>

    <div class="row" style="border: 1px solid lightgrey; border-radius:10px; min-width:100%; align-content:center">

        @foreach($data as $status)
            
            <div class="col-4">
                <div class="col-12">
                    <h1> {{ $status->title }} </h1>

                    @foreach($status->tasks as $task)
                        @if($task->status_id == $status->id && $task->parent_task_id == NULL)

                            <div class="card mt-2">
                                <div class="m-1">
                                    <form action="{{ route('deleteTask', $task->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"> X </button>
                                    </form>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $task->title }}</h5> <strong>deadline:</strong> {{ $task->deadline }}
                                    <p class="card-text">{{ Str::substr($task->description, 0, 50) }}</p>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title"> Tags: </h6>
                                    {!! $task->tags->implode(function ($tag) {
                                        return '<span class="btn btn-success m-1">' . $tag['title'] . '</span>';
                                    }) !!}
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>Sub-tasks: </strong>
                                    </li>

                                    @foreach($task->subTasks as $subTask)
                                        <li class="list-group-item">
                                            <strong>{{ $subTask->title }}</strong>
                                            {{ Str::substr($subTask->description, 0, 20) }}
                                            <br>
                                            <span> status: {{ $subTask->status->title }} </span>
                                        </li>
                                    @endforeach

                                </ul>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('editTaskStatus', $task->id) }}" class="d-flex align-items-center">
                                        @csrf
                                        <div class="flex-grow-1">
                                            <select name="status_id" class="form-select" aria-label="Default select example">
                                                <option selected>Change Status</option>
                                                @foreach($statuses as $statusOption)
                                                    <option value="{{ $statusOption->id }}">{{ $statusOption->id . ' - ' . $statusOption->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" id="submit" class="btn btn-primary">Save</button>
                                    </form>
                                    <button type="button" class="btn btn-primary mt-2">
                                        <a style="color: white" href="{{ route('taskDetail', $task->id) }}"> Detail / Edit </a>
                                    </button>
                                    <button type="button"
                                            class="btn btn-primary mt-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="{{ '#addSubFor' . $task->id }}"
                                            {{ $status->id == 4 ? 'disabled' : '' }}>Add subtask</button>
                                </div>

                            </div>
                            <div class="modal fade" id="{{ 'addSubFor' . $task->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <form action="{{ route('storeTask') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="parent_task_id" value="{{ $task->id }}">
                                    @include('tasks.form')
                                </form>
                            </div>

                        @endif
                    @endforeach

                </div>
            </div>

        @endforeach

    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{ route('storeTask') }}" method="POST">
            @csrf
            @include('tasks.form')
        </form>
    </div>

@endsection

