<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Table;
use Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CustomTableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    /**
     *  creating a new resource.
     */
    public function createTable($table_name, $fields)
    {
        // print_r($fields);
        // dd($fields);
        // laravel check if table is not already exists
        if (!Schema::hasTable($table_name)) {
            Schema::create($table_name, function (Blueprint $table) use ($fields, $table_name) {
                $table->increments('id');
                if (count($fields) > 0) {
                    $count = 0;
                    foreach ($fields as $field) {
                        $table->{$field['column_data_type'.$count]}($field['column_name'.$count]);
                        $count ++;
                    }
                }
                $table->timestamps();
            });
            return $this->sendResponse([], 'Given table has been successfully created!');
        }

        return $this->sendResponse([], 'Given table is already exists.');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { 
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'column_name.*' => 'required',
            'column_data_type.*' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $Table = Table::find($inputs["table_id"]);

        if (is_null($Table)) {
            return $this->sendError('Table not found.');
        }

        $table_name = $Table->database_table_name;

        
        $fields = array_slice($inputs, 1);
        $fields_array = array();
        for ($x = 0; $x <= count($fields) - 2; $x+=2) {
            $fieldgroup = array_slice($fields, $x, 2);
            array_push($fields_array,$fieldgroup);
          }
        return $this->createTable($table_name, $fields_array);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Table = Table::find($id);
  
        if (is_null($Table)) {
            return $this->sendError('Table not found.');
        }
        $table = DB::table($Table->database_table_name)->get();
        
        // dd($table);
        return $this->sendResponse([$table], 'Table retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $inputs = $request->all();
   
        $validator = Validator::make($inputs, [
            'column_name.*' => 'required',
            'column_data_type.*' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $Table = Table::find($id);

        if (is_null($Table)) {
            return $this->sendError('Table not found.');
        }

        $table_name = $Table->database_table_name;
        
        $fields = array_slice($inputs, 1);
        $fields_array = array();
        for ($x = 0; $x <= count($fields) - 2; $x+=2) {
            $fieldgroup = array_slice($fields, $x, 2);
            array_push($fields_array,$fieldgroup);
          }

        //   dd($fields_array);
        // laravel check if table is not already exists
        if (Schema::hasTable($table_name)) 
        {
            Schema::table($table_name, function (Blueprint $table) use ($fields_array, $table_name) 
            {
                if (count($fields_array) > 0) {
                    $count = 0;
                    foreach ($fields_array as $field) {
                        $has_column = False;
                        if (Schema::hasColumn($table_name, $field['column_name'.$count])) 
                        {
                            $has_column = True;
                        }
                        if( !$has_column )
                        {
                            $table->{$field['column_data_type'.$count]}($field['column_name'.$count]);
                        }
                        $count ++;
                    }
                }
            });
            return $this->sendResponse([], 'Given table has been successfully edited!');
        }

        return $this->sendResponse([], 'Given table does not exist, please create to edit.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Table = Table::find($id);
  
        if (is_null($Table)) {
            return $this->sendError('Table not found.');
        }
        Schema::dropIfExists($Table->database_table_name); 
    
        return true;
    }
}
