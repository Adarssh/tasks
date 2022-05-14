<?php

namespace App\Console\Commands;

use App\Models\SubTask;
use App\Models\Task;
use Illuminate\Console\Command;

class DeleteSoftDeletedRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:softdeleted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all softdeleted records permanently from the db';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $deletedSubTaskIds = SubTask::select('id')->withTrashed()->where('deleted_at', '!=', null)->get()->toArray()->pluck('id')->toArray();
        SubTask::whereIn('id', $deletedSubTaskIds)->delete();
        $deletedTaskIds = Task::select('id')->withTrashed()->where('deleted_at', '!=', null)->get()->pluck('id')->toArray();
        Task::whereIn('id', $deletedTaskIds)->delete();
        return 'Softdeleted records deleted successfully';
    }
}
