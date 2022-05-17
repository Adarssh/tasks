<?php

namespace App\Repositories;

use App\Http\Resources\SubTaskCollection;
use App\Http\Resources\SubTaskResource;
use App\Exceptions\DBException;
use App\Models\SubTask;
use App\Models\Task;
use Carbon\Carbon;

class SubTaskRepository implements IRepository
{
    protected $SubTaskModel;

public function __construct(SubTask $SubTask)
    {
        $this->SubTaskModel = $SubTask;
    }

    public function all(array $data): SubTaskCollection
    {
        $condition = $this->SubTaskModel::orderBy('due_date');
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
                $condition = $condition->where('due_date', '>', $now->format('Y-m-d H:i'))->where('due_date', 'Pending');
            }
        }

        $collection = $condition->get();

        return new SubTaskCollection($collection);
    }

    public function find(int $id): SubTaskResource
    {
        $resource = $this->SubTaskModel::findOrFail($id);

        return new SubTaskResource($resource);
    }

    public function create(array $data): SubTaskResource
    {
        extract($data);

        $resource = new $this->SubTaskModel;

        $resource->task_id = $task_id;
        $resource->title = $title;
        $resource->due_date = $due_date;
        $resource->status = $status;
        if (!$resource->save()) {
            throw new DBException();
        }

        return new SubTaskResource($resource);
    }

    public function update(int $id, array $data): SubTaskResource
    {
        extract($data);

        $resource = $this->SubTaskModel::findOrFail($id);
        $resource->status = $status;

        if (!$resource->save()) {
            throw new DBException();
        }

        return new SubTaskResource($resource);
    }

    public function delete(int $id): void
    {
        $SubTask = $this->SubTaskModel::findOrFail($id);

        if (!$SubTask->delete()) {
            throw new DBException();
        }
    }
}
