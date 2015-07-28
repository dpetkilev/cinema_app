<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $RS = DB::select('call cnm_get_movies()');
 
        $Resp = array
        (
            'data' => json_decode(json_encode($RS), true)
        );           
        
        return response()->json($Resp);       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $pID = substr(trim($id), 0, 3);
        if(!preg_match('/^[0-9]*$/', $pID))
        {   
       
            $Resp = array
            (
                'error' => array
                (
                    array
                    (
                        'param' => 'ID',
                        'message' => 'ID parameter must contain only numbers'
                    )/*,
                    array
                    (
                        'param' => 'date',
                        'message' => 'ID parameter must contain only numbers12313'
                    )*/
                )
            );
            
            return response()->json($Resp);
        }        
        else 
        {
            $RS = DB::select('call cnm_get_movie_info('.$id.')');  
            
            $Resp = array
            (
                'data' => json_decode(json_encode($RS), true)
            );
            
            return response()->json($Resp);
        }   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
