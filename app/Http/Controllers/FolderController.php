<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Folder;
use Validator;
use App\Http\Resources\FolderResource;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Folders = Folder::all();
    
        return $this->sendResponse(FolderResource::collection($Folders), 'Folders retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'path' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $Folder = Folder::create($input);
   
        return $this->sendResponse(new FolderResource($Folder), 'Folder created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Folder = Folder::find($id);
  
        if (is_null($Folder)) {
            return $this->sendError('Folder not found.');
        }
   
        return $this->sendResponse(new FolderResource($Folder), 'Folder retrieved successfully.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Folder $Folder)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'path' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $Folder->name = $input['name'];
        $Folder->path = $input['path'];
        $Folder->save();
   
        return $this->sendResponse(new FolderResource($Folder), 'Folder updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folder $Folder)
    {
        $Folder->delete();
   
        return $this->sendResponse([], 'Folder deleted successfully.');
    }
}