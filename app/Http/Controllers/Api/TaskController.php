<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Tag;
use App\Models\Status;
use App\Http\Requests\StoreTaskRequest;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Display list of resources
     *
     * @return JsonResponse
     */
    public function index()
    {

        $data = Status::query()
            ->with(['tasks' => function($q) {
                $q->with('tags');
                $q->with('subTasks');
            }])
            ->get();

        return response()->json($data);
    }

    /**
     * tagsList
     *
     * @return JsonResponse
     */
    public function getTags()
    {
        $tags = Tag::query()->get();

        return response()->json($tags);
    }

    /**
     * statusList
     *
     * @return JsonResponse
     */
    public function getStatuses()
    {
        $statuses = Status::query()->get();

        return response()->json($statuses);
    }
    /**
     * Display a specific resource
     *
     * @param  int $id
     * @return JsonResponse with resource
     */
    public function show($id)
    {
        $task = Task::query()->with(['status', 'tags'])->findOrFail($id);

        $subTasks = $task->subTasks()->with('status','tags')->paginate(4);

        $task->setRelation('subTasks', $subTasks);

        $task->tags = $task->tags->transform(function ($tag) {
            return $tag->id;
        })->toArray();;

        return response()->json($task);
    }

    /**
     * Store a newly created resource
     *
     * @param  StoreRequest $request
     * @return JsonResponse with status
     */
    public function store(StoreTaskRequest $request)
    {
        info($request);
        $task = Task::query()->create($request->except('tags', 'subTasks', 'status'));

        $task->tags()->attach($request['tags']);

        $message = 'Task: ' . $task->title . ' has been created';

        return response()->json($message);
    }

    /**
     * update the specified resource
     *
     * @param  Request $request
     * @param  int $id
     * @return  JsonResponse with status
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
                    return back()->with('status', 'Some associated tasks are not marked as done.');
                }
            }

            Task::query()->where('id', $id)->update([
                'status_id' => $request['status_id']
            ]);

            return back();
        }
        info($request['tags']);

        // edit whole task.
        $task = Task::query()->updateOrCreate(['id' => $id], $request->except(['_token','tags', 'sub_tasks']));

        $task->tags()->sync($request['tags']);

        $message = 'Task: ' . $task->title . ' has been modified';
        return response()->json($message);
    }

    /**
     * destroy
     *
     * @param  int $id
     * @return JsonResponse with status
     */
    public function destroy($id)
    {
        $task = Task::query()->with('subTasks')->findOrFail($id);

        $task->subTasks()->delete();
        $task->delete();
        
        $message = 'Task ' . $task->title . 'has been deleted';
        return response()->json($message);
    }
}
