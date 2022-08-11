<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    private $sucessStatus = 200;

    // --------------- [ Create Task ] ------------------
    public function createTask(Request $request)
    {

        $user = Auth::user();
        $validator = Validator::make($request->all(),
            [
                "title" => "required",
                "description" => "required",
            ]
        );

        if ($validator->fails()) {
            return response()->json(["validation_errors" => $validator->errors()]);
        }

        $task_array = array(
            "title" => $request->title,
            "description" => $request->description,
            "status" => $request->status,
            "user_id" => $user->id,
            "filename" => Task::saveImage($request)
        );

        $task = Task::create($task_array);

        if (!is_null($task)) {
            return response()->json(["status" => $this->sucessStatus, "success" => true, "data" => $task]);
        } else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! task not created."]);
        }

    }

    // --------------- [ Update Task ] ------------------
    public function updateTask(Request $request)
    {

        $user = Auth::user();
        $validator = Validator::make($request->all(),
            [
                "title" => "required",
                "description" => "required",
            ]
        );

        if ($validator->fails()) {
            return response()->json(["validation_errors" => $validator->errors()]);
        }

        $task_array = array(
            "title" => $request->title,
            "description" => $request->description,
        );

        $task_id = $request->task_id;
        if ($task_id && Task::where('user_id', $user->id)->where('id', $task_id)->exists()) {
            $task_status = Task::where("id", $task_id)->update($task_array);
            if ($task_status == 1) {
                return response()->json(["status" => $this->sucessStatus, "success" => true, "message" => "Todo updated successfully", "data" => $task_array]);
            } else {
                return response()->json(["status" => $this->sucessStatus, "success" => false, "message" => "Todo not updated"]);
            }
        }
        return response()->json(["status" => $this->sucessStatus, "success" => false, "message" => "Not valid task id: " . $task_id]);
    }

    // ---------------- [ Task Listing ] -----------------
    public function tasks(Request $request)
    {
        $tasks = array();
        $user = Auth::user();

        $tag_id = $request->tag;

        if ($tag_id) {
            // @TODO фильтрация по тегу
        } else {
            $tasks = Task::where("user_id", $user->id)->get();
        }

        $aTasks = [];
        foreach ($tasks as $task) {
            $aTasks[] = [
                "id" => $task->id,
                "title" => $task->title,
                "description" => $task->description,
                "filename" => $task->filename,
            ];
        }
        return response()->json(["status" => $this->sucessStatus, "success" => true, "count" => count($tasks), "data" => $aTasks]);
    }

    // ------------------ [ Task Detail ] -------------------
    public function task($task_id)
    {
        if ($task_id == 'undefined' || $task_id == "") {
            return response()->json(["status" => "failed", "success" => false, "message" => "Alert! enter the task id"]);
        }

        $task = Task::find($task_id);

        if (!is_null($task)) {
            return response()->json(["status" => $this->sucessStatus, "success" => true, "data" => $task]);
        } else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no todo found"]);
        }
    }

    // ----------------- [ Delete Task ] -------------------
    public function deleteTask($task_id)
    {
        if ($task_id == 'undefined' || $task_id == "") {
            return response()->json(["status" => "failed", "success" => false, "message" => "Alert! enter the task id"]);
        }

        $task = Task::find($task_id);

        if (!is_null($task)) {

            @unlink(public_path('images') . '/' . $task->filename);
            @unlink(public_path('images') . '/' . "th_" . $task->filename);

            $delete_status = Task::where("id", $task_id)->delete();

            if ($delete_status == 1) {

                return response()->json(["status" => $this->sucessStatus, "success" => true, "message" => "Success! todo deleted"]);
            } else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Alert! todo not deleted"]);
            }
        } else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Alert! todo not found"]);
        }
    }

    // ----------------- [ Delete Task Img ] -------------------
    public function deleteTaskImg($task_id)
    {
        if ($task_id == 'undefined' || $task_id == "") {
            return response()->json(["status" => "failed", "success" => false, "message" => "Alert! enter the task id"]);
        }

        $task = Task::find($task_id);

        if (!is_null($task)) {

            @unlink(public_path('images') . '/' . $task->filename);
            @unlink(public_path('images') . '/' . "th_" . $task->filename);
            $task_status = Task::where("id", $task_id)->update(['filename' => ""]);

            if ($task_status == 1) {
                return response()->json(["status" => $this->sucessStatus, "success" => true, "message" => "Success! img deleted"]);
            } else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Update error"]);
            }
        } else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Alert! todo not found"]);
        }
    }

    // ----------------- [ Update Task Img ] -------------------
    public function updateTaskImg(Request $request)
    {
        $user = Auth::user();
        $task_id = $request->task_id;
        if ($task_id && Task::where('user_id', $user->id)->where('id', $task_id)->exists()) {

            $task = Task::find($task_id);

            @unlink(public_path('images') . '/' . $task->filename);
            @unlink(public_path('images') . '/' . "th_" . $task->filename);

            $task_status = Task::where("id", $task_id)->update(['filename' => Task::saveImage($request)]);

            if ($task_status == 1) {
                return response()->json(["status" => $this->sucessStatus, "success" => true, "message" => "Success! img deleted"]);
            } else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Update error"]);
            }
        } else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Alert! todo not found"]);
        }
    }

}
