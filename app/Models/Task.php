<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'due_date', 'status', 'created_at'
    ];

    /**
     * Get all of the subtasks for the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(SubTask::class, 'task_id', 'id');
    }
}
