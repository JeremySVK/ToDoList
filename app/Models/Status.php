<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'statuses';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'status_id', 'id');
    }

    public function lastTask()
    {
        return $this->tasks()->one()->latestOfMany();
    }
}
