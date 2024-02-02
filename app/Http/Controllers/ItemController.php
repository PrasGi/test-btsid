<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    private $itemModel;
    public function __construct()
    {
        $this->itemModel = new Item();
    }

    public function index(Checklist $checklist)
    {
        $items = $this->itemModel->where('checklist_id', $checklist->id)->get();
        return response()->json([
            'status_code' => 200,
            'message' => 'Checklist items',
            'data' => $items
        ], 200);
    }

    public function store(Request $request, Checklist $checklist)
    {
        $validate = Validator::make($request->only('itemName', 'checklist_id'), [
            'itemName' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status_code' => 400,
                'message' => $validate->errors()
            ], 400);
        }

        $payload = [
            'itemName' => $request->itemName,
            'checklist_id' => $checklist->id
        ];

        if ($item = $this->itemModel->create($payload)) {
            return response()->json([
                'status_code' => 200,
                'message' => 'Item created successfully',
                'data' => $item
            ], 200);
        }

        return response()->json([
            'status_code' => 500,
            'message' => 'Internal server error'
        ], 500);
    }

    public function getItemByChecklistIdItemId(Checklist $checklist, Item $item)
    {
        $item = $this->itemModel->where('checklist_id', $checklist->id)->where('id', $item->id)->first();
        if ($item) {
            return response()->json([
                'status_code' => 200,
                'data' => $item
            ], 200);
        }

        return response()->json([
            'status_code' => 404,
            'message' => 'Item not found'
        ], 404);
    }

    public function updateStatusItemByChecklistIdItemId(Checklist $checklist, Item $item)
    {
        $item = $this->itemModel->where('checklist_id', $checklist->id)->where('id', $item->id)->first();
        $item->status = !$item->status;
        if ($item->save()) {
            return response()->json([
                'status_code' => 200,
                'data' => $item
            ], 200);
        }

        return response()->json([
            'status_code' => 404,
            'message' => 'Item not found'
        ], 404);
    }

    public function destroy(Checklist $checklist, Item $item)
    {
        $item = $this->itemModel->where('checklist_id', $checklist->id)->where('id', $item->id)->first();
        if ($item->delete()) {
            return response()->json([
                'status_code' => 200,
                'message' => 'Item deleted successfully'
            ], 200);
        }

        return response()->json([
            'status_code' => 404,
            'message' => 'Item not found'
        ], 404);
    }

    public function renameItemByChecklistIdItemId(Request $request, Checklist $checklist, Item $item)
    {
        $validate = Validator::make($request->only('itemName'), [
            'itemName' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status_code' => 400,
                'message' => $validate->errors()
            ], 400);
        }

        $item = $this->itemModel->where('checklist_id', $checklist->id)->where('id', $item->id)->first();
        $item->itemName = $request->itemName;
        if ($item->save()) {
            return response()->json([
                'status_code' => 200,
                'data' => $item
            ], 200);
        }

        return response()->json([
            'status_code' => 404,
            'message' => 'Item not found'
        ], 404);
    }
}
