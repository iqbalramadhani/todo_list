<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $item = Item::select('*');
        if ($request->status == "on_going") {
            $item->where('completed', 0);
        } else if ($request->status == "done") {
            $item->where('completed', 1);
        }
        $items = $item->orderBy('created_at', 'DESC')->get();
        return $items;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newData = new Item;
        $newData->name = $request->name;
        $newData->completed = false;
        $newData->save();

        return $newData;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $updateItem = Item::find($id);
        if ($updateItem) {
            $updateItem->completed = !$updateItem->completed;
            $updateItem->completed_at = $updateItem->completed ? Carbon::now() : null;
            $updateItem->name = trim($request->name) ?? $updateItem->name;
            $updateItem->save();
            return $updateItem;
        }

        return 'Item not found';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $checkItem = Item::find($id);

        if ($checkItem) {
            $checkItem->delete();
            return response()->json([
                'status' => true,
                'message' => "success delete item"
            ]);
        }

        return "Item not found";
    }
}
