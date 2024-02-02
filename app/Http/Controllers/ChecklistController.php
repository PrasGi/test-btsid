<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChecklistController extends Controller
{
    private $checklistModel;
    public function __construct()
    {
        $this->checklistModel = new Checklist();
    }

    public function index()
    {
        $checklists = $this->checklistModel->all();
        return response()->json([
            'status_code' => 200,
            'message' => 'Success',
            'data' => $checklists
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->only('name'), [
            'name' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status_code' => 400,
                'message' => $validate->errors()
            ], 400);
        }

        $payload = [
            'name' => $request->name,
            'user_id' => auth()->user()->id
        ];

        if ($checklist = $this->checklistModel->create($payload)) {
            return response()->json([
                'status_code' => 200,
                'message' => 'Checklist created successfully',
                'data' => $checklist
            ], 200);
        }

        return response()->json([
            'status_code' => 500,
            'message' => 'Internal server error'
        ], 500);
    }

    public function destroy(Checklist $checklist)
    {
        if ($checklist->delete()) {
            return response()->json([
                'status_code' => 200,
                'message' => 'Checklist deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
