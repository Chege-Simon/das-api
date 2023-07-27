<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $table_id)
    {
        $Table = Table::find($request->table_id);

        if (is_null($Table)) {
            return $this->sendError('Table not found.');
        }
        $table = DB::table($table_name)->get();
 
        return $this->sendResponse([$table], $table->database_table_name.' table has been retrived successfully!');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  
        $inputs = $request->all();

        $Table = Table::find($request->table_id);

        if (is_null($Table)) {
            return $this->sendError('Table not found.');
        }

        $table_name = $Table->database_table_name;

        DB::table($table_name)->insert($inputs);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $table_id, string $id)
    {       
        $data = DB::table($table_name)->find($id);
 
        return $this->sendResponse([$data], 'Data has been retrived successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $inputs = $request->all();

        $TargetData = DB::table($table_name)->find($id);

        if (is_null($TargetData)) {
            return $this->sendError('TargetData not found.');
        }
        $updated_data = DB::table($table_name)
        ->where('id', $id)
        ->update($inputs);

        return $this->sendResponse([$updated_data], 'Data has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $TargetData = DB::table($table_name)->find($id);

        if (is_null($TargetData)) {
            return $this->sendError('TargetData not found.');
        }

        $TargetData->delete();

        return $this->sendResponse([], 'TargetData has been deleted successfully!');
    }
}
