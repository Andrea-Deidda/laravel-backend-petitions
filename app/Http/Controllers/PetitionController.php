<?php

namespace App\Http\Controllers;

use App\Http\Resources\PetitionCollection;
use App\Http\Resources\PetitionResource;
use App\Models\Petition;
//use Facade\FlareClient\Http\Response;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new PetitionCollection(Petition::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->only([
            'title', 'description', 'category', 'author', 'signees'
        ]), [
            'title' =>'required|string|max:255',
            'description' =>'required|string|max:255',
            'category' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'signees' => 'required|integer|between:1,1000000'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $petition = Petition::create($request->only([
            'title', 'description', 'category', 'author', 'signees'
        ]));

        return response()->json(new PetitionResource($petition), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function show(Petition $petition)
    {
        if($petition){
            return response()->json(new PetitionResource($petition), Response::HTTP_OK);
        }

        return response()->json(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Petition $petition)
    {
        $petition->update($request->only([
            'title', 'description', 'category', 'author', 'signees'
        ]));

        return response()->json(new PetitionResource($petition), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Petition $petition)
    {
        $petition->delete();

        return response()->json([
            null, Response::HTTP_NO_CONTENT
        ]);
    }
}
