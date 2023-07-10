@extends('layouts.main')

@section('content')

    @if (session('status'))
        <div class="alert alert-success">
            {!! session('status') !!}
        </div>
    @endif

    {{ $errors->first() }}

    <div class="container">
        <div class="row pt-5">

            <form action="{{ route('deleteTask', $task->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> X </button>
            </form>

            <div class="col-12">
                <form method="POST" action="{{ route('editTask', $task->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Task title: </label>
                        <input type="text" value="{{ $task->title }}" name="title" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
                        <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"> {{ $task->description }} </textarea>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Deadline</label>
                        <input type="date" name="deadline" value="{{ $task->deadline }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"> Status: </label>
                        <select name="status_id" class="form-select" aria-label="Default select example">
                            <option selected value="{{ $task->status_id }}"> {{ $task->status->title }}</option>

                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}">{{ $status->title }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div>
                        <label class="form-label"> Tags: </label>
                        <br>

                        @foreach ($tags as $tag)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input"
                                        name="tags[]" type="checkbox"
                                        id="{{ $tag->id }}"
                                        value="{{ $tag->id }}"
                                        {{ $task->tags->contains('id', $tag->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ $tag->id }}">{{ $tag->title }}</label>
                            </div>
                        @endforeach

                    </div>
                    <button type="submit" id="submit" class="btn btn-primary mt-3">Save changes</button>
                </form>
            </div>

        </div>

        @if(!is_null($task->parent_task_id))

            <div class="pt-3">
                <h5> Sub task of: </h5>
                <a href="{{ route('taskDetail', $task->subTask->id) }}">{{ $task->subTask->title }}</a>
            </div>

        @else

            <div class="row">
                <div class="col-12 pt-3">
                    <h2>Sub-tasks</h2>

                    @foreach($task->subTasks as $subTask)

                        <div class="mb-3" style="border: 1px solid lightgray; border-radius: 10px">
                            <div class="row p-1">
                                <div class="col-5">
                                    <h5>{{ $subTask->title }}</h5>
                                    <p> Dedaline: {{ $subTask->deadline }}</p>
                                    <p> Status: {{ $subTask->status->title }}</p>
                                    <p>
                                        Tags:
                                        @foreach($subTask->tags as $tag)
                                        <span class="btn btn-success"> {{ $tag->title }} </span>
                                        @endforeach

                                    </p>
                                </div>
                                <div class="col-6">
                                    <strong> Description: </strong>
                                    <p>{{ $subTask->description }}</p>
                                </div>
                                <div class="col-1">
                                    <form action="{{ route('deleteTask', $subTask->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"> X </button>
                                    </form>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary mt-2">
                                <a style="color: white" href="{{ route('taskDetail', $subTask->id) }}"> Detail / Edit </a>
                            </button>
                        </div>

                    @endforeach

                    {{ $task->subTasks->links() }}

                </div>
                <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addSubtask">Add subtask</button>
            </div>

        @endif
    </div>

    <div class="modal fade" id="addSubtask" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{ route('storeTask') }}" method="POST">
            @csrf
            <input type="hidden" name="parent_task_id" value="{{ $task->id }}">
            @include('tasks.form')
        </form>
    </div>

@endsection
