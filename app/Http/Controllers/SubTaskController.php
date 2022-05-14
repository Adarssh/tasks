<?php

namespace App\Http\Controllers;

use App\Http\Validators\SubTaskValidator;
use App\Repositories\SubTaskRepository;
use Illuminate\Http\Request;

class SubTaskController extends Controller
{
    private $repository;
    private $validator;

    public function __construct(SubTaskRepository $repository, SubTaskValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;

    }

    /**
     * @OA\Get(
     *     path="/api/sub-tasks",
     *     description="Display all the SubTasks",
     *     tags={"SubTask_manager"},
     *     operationId="lgetAllSubTasks",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(),
     *         ),
     *     )
     * )
     */
    public function index(Request $request)
    {
        return $this->repository->all($request->all());
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     description="Add a new SubTask to the database",
     *     tags={"SubTask_manager"},
     *     operationId="storeSubTask",
     *     @OA\Parameter(
     *         name="task_id",
     *         in="query",
     *         description="Task Id",
     *         required=false,
     *         @OA\Schema(
     *             type="int",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Task title",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="due_date",
     *         in="query",
     *         description="Task Due Date",
     *         required=true,
     *         @OA\Schema(
     *             type="date",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Task status",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *              enum={"Pending", "Completed"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="created",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task Type not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $this->validator->params($request);
        return $this->repository->create($request->all());
    }

    /**
     * @OA\Get(
     *     path="/api/sub-tasks/{id}",
     *     tags={"SubTask_manager"},
     *     description="Finds SubTasks by id",
     *     operationId="showTask",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Task",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
    */
    public function show($id)
    {
        $this->validator->id($id);

        return $this->repository->find($id);
    }

    /**
     * @OA\Put(
     *     path="/api/sub-tasks/{id}",
     *     description="Update an existing Task",
     *     tags={"SubTask_manager"},
     *     operationId="updateSubTask",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Identifier value",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Task status",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *              enum="Pending", "Completed"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found / Task Type not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $this->validator
        ->id($id)
        ->params($request);

        return $this->repository->update($id, $request->all());
    }

    /**
     * @OA\Delete(
     *     path="/api/sub-tasks/{id}",
     *     description="Deletes and existing Task",
     *     tags={"SubTask_manager"},
     *     operationId="deleteTask",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Identifier value",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )s
     * )
     */
    public function destroy($id)
    {
        $this->validator->id($id);

        $this->repository->delete($id);

        return response()->json("Record deleted successfully", 200);
    }
}
