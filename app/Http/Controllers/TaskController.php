<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Tag;
use App\Models\Status;
use App\Http\Requests\StoreTaskRequest;

class TaskController extends Controller
{
    /**
     * Display list of resources
     *
     * @return View
     */
    public function index()
    {
        $tags = Tag::query()->get();

        $statuses = Status::query()->get();

        $data = Status::query()
            ->with(['tasks' => function($q) {
                $q->with('tags');
                $q->with('subTasks');
            }])
            ->get();

        return view('tasks.home', [
            'tags' => $tags,
            'data' => $data,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Display a specific resource
     *
     * @param  int $id
     * @return View
     */
    public function show($id)
    {
        $task = Task::query()->with(['tags', 'subTasks.tags'])
            ->findOrFail($id);

        $subTasks = $task->subTasks()->paginate(5);

        $task->setRelation('subTasks', $subTasks);

        $tags = Tag::query()->get();

        $statuses = Status::query()->get();

        return view('tasks.detail', [
            'task' => $task,
            'tags' => $tags,
            'statuses' => $statuses
        ]);
    }

    /**
     * Store a newly created resource
     *
     * @param  StoreRequest $request
     * @return void
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::query()->create($request->except('tags'));

        $task->tags()->attach($request['tags']);

        return back()->with('status', 'Task ' . $task->title . 'has been modified');
    }

    /**
     * update the specified resource
     *
     * @param  Request $request
     * @param  int $id
     * @return  View with status
     */
    public function update(Request $request, $id)
    {
        // check if only status is being changed
        if($request->routeIs('editTaskStatus'))
        {
            // check if all sub tasks are also finished before marking parent task as done
            if($request['status_id'] == 4)
            {
                $activeSubTasks = Task::query()
                    ->where([
                        ['parent_task_id','=', $id],
                        ['status_id', '<>', 4]
                    ])->count();

                // throw an error
                if($activeSubTasks > 1) {
                    return response()->with('status', 'Some associated tasks are not marked as done.');
                }
            }

            Task::query()->where('id', $id)->update([
                'status_id' => $request['status_id']
            ]);

            return back();
        }
        // edit whole task.
        $task = Task::query()->updateOrCreate(['id' => $id], $request->except(['_token','tags']));

        // info($request['tags']);

        $task->tags()->sync($request['tags']);

        return redirect()->route('home')->with('status', 'Task <strong>' . $task->title . '</strong> has been modified');
    }

    /**
     * destroy
     *
     * @param  int $id
     * @return View with status
     */
    public function destroy($id)
    {
        $task = Task::query()->with('subTasks')->findOrFail($id);

        $task->subTasks()->delete();
        $task->delete();

        return redirect()->route('home')->with('status', 'Task ' . $task->title . ' has been deleted');
    }
}
