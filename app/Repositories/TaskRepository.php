<?php

namespace App\Repositories;

use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Exceptions\DBException;
use App\Models\SubTask;
use App\Models\Task;
use Carbon\Carbon;

class TaskRepository implements IRepository
{
    protected $TaskModel;
    protected $SubTaskModel;

    public function __construct(Task $Task, SubTask $SubTask)
    {
        $this->TaskModel = $Task;
        $this->SubTaskModel = $SubTask;
    }

    public function all(array $data): TaskCollection
    {
        $condition = $this->TaskModel::orderBy('due_date');
        if (isset($data['title'])) {
            $condition = $condition->where('title', $condition);
        }

        if (isset($data['due_date']) ) {

            $now = Carbon::now();
            if ($data['due_date'] == 'Today') {
                $condition = $condition->where('due_date', $now->format('Y-m-d H:i'));
            }

            if ($data['due_date'] == 'This Week') {
                $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
                $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
                $condition = $condition->whereBetween('due_date', [$weekStartDate, $weekEndDate]);
            }

            if ($data['due_date'] == 'Next Week') {
                $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
                $condition = $condition->whereBetween('due_date', [$now->addweek(1)->format('Y-m-d H:i'), $weekEndDate]);
            }

            if ($data['due_date'] == 'Overdue') {
                $condition = $condition->where('due_date', '>', $now->format('Y-m-d H:i'));
            }
        }

        $collection = $condition->get();
        return new TaskCollection($collection);
    }

    public function find(int $id): TaskResource
    {
        $resource = $this->TaskModel::findOrFail($id);

        return new TaskResource($resource);
    }

    public function create(array $data): TaskResource
    {
        extract($data);

        $resource = $this->TaskModel;

        $resource->title = $title;
        $resource->due_date = $due_date;
        $resource->status = $status;

        if (!$resource->save()) {
            throw new DBException();
        }

        return new TaskResource($resource);
    }

    public function update(int $id, array $data): TaskResource
    {
        extract($data);

        $resource = $this->TaskModel::findOrFail($id);
        $resource->status = $status;

        if ($status == 'Completed') {
            SubTask::where('task_id', $id)->update(['status' => 'Completed']);
        }

        if (!$resource->save()) {
            throw new DBException();
        }

        return new TaskResource($resource);
    }

    public function delete(int $id): void
    {
        $Task = $this->TaskModel::findOrFail($id);

        if (!$Task->delete()) {
            throw new DBException();
        }
    }
}
