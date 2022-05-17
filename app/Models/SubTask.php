<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubTask extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'sub_tasks';

    protected $fillable = [
        'task_id', 'title', 'due_date', 'status'
    ];

    /**
     * Get the Task that owns the SubTask
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
