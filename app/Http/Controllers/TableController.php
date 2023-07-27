<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Table;
use Validator;
use App\Http\Resources\TableResource;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Tables = Table::all();
    
        return $this->sendResponse(TableResource::collection($Tables), 'All Tables retrieved successfully.');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'front_name' => 'required',
            'database_table_name' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $checktable = Table::where('database_table_name', $input["database_table_name"])->first();

        if (!is_null($checktable)) {
            return $this->sendError('Table already exists.');
        }

        $table = Table::create($input);
        return $this->sendResponse([$table], 'Given table has been registered and ready to add fields!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Table = Table::find($id);
  
        if (is_null($Table)) {
            return $this->sendError('Table not found.');
        }
   
        return $this->sendResponse(new TableResource($Table), 'Table retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'front_name' => 'required',
            'database_table_name' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $Table->front_name = $input['front_name'];
        $Table->database_table_name = $input['database_table_name'];
        $Table->save();
   
        return $this->sendResponse(new TableResource($Table), 'Table updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Table->find($id)->delete();
   
        return $this->sendResponse([], 'Table deleted successfully.');
    }
}
