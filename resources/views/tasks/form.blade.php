<div class="modal-dialog">
    <div class="modal-content">

        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Task</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Task title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" id="exampleFormControlInput1" placeholder="title">
            </div>

            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Task description</label>
                <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <select name="priority" class="form-select" aria-label="Default select example">
                    <option value="" selected>Choose priority</option>
                    <option value="1">Low</option>
                    <option value="2">Medium</option>
                    <option value="3">High</option>
                </select>
            </div>
            
            <div>
                <label class="form-label"> Tags: </label>
                <br>

                @foreach ($tags as $tag)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                               name="tags[]"
                               type="checkbox"
                               id="{{ $tag->id }}"
                               value="{{ $tag->id }}">
                        <label class="form-check-label" for="{{ $tag->id }}">{{ $tag->title }}</label>
                    </div>
                @endforeach

            </div>

            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Deadline</label>
                <input type="date" name="deadline" class="form-control" value="{{ old('deadline') }}">
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" id="submit" class="btn btn-primary">Save changes</button>
        </div>

    </div>
</div>
