<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;
        /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tasks';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * attributes default values
     *
     * @var array
     */
    protected $attributes = [
        'status_id' => 1,
        // 'created_at' => Carbon::now()->timestamp,
    ];

    /**
     * status relation
     *
     * @return void
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    /**
     * subTasks relation
     *
     * @return void
     */
    public function subTasks()
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    /**
     * subTask relation
     *
     * @return void
     */
    public function subTask()
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    /**
     * tags relation
     *
     * @return void
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tasks_tags_pivot', 'task_id', 'tag_id');
    }
}
