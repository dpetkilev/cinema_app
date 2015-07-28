<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CinemasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $RS = DB::select('call cnm_get_cinemas()');
        
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
                    )
                )
            );
            
            return response()->json($Resp);
        }        
        else 
        {
            $RS = DB::select('call cnm_get_cinema_info('.$id.')');
            
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
////////////////////////////////////////////////////////////////////////////////
    public function GetClosestCinemas($lon, $lat, $max_km)
    {
        $FormDataOK = true;
        
        $pLon = substr(trim($lon), 0, 10);
        $pLat = substr(trim($lat), 0, 10);
        $pMax_km = substr(trim($max_km), 0, 3);
        
        $Resp = array
        (
            'error' => array()
        );
        
        if(!preg_match('/^[0-9.-]*$/', $pLon))
        {   
            $FormDataOK = false;
            $NewEntry =  array
            (
                'param' => 'Lon',
                'message' => 'Longitude parameter must contain only numbers, - and .'
            );
            array_push($Resp['error'],$NewEntry);
        }
        
        if(!preg_match('/^[0-9.-]*$/', $pLat))
        {   
            $FormDataOK = false;
            $NewEntry =  array
            (
                'param' => 'Lat',
                'message' => 'Latitude parameter must contain only numbers, - and .'
            );
            array_push($Resp['error'],$NewEntry);
        }
        
        if(!preg_match('/^[0-9]*$/', $pMax_km))
        {   
            $FormDataOK = false;
            $NewEntry =  array
            (
                'param' => 'km',
                'message' => 'Distance parameter must contain only numbers'
            );
            array_push($Resp['error'],$NewEntry);
        }
        
        if(!$FormDataOK)
        {
            return response()->json($Resp);
        }
        else
        {
            $RS = DB::select('call cnm_get_closest_cinemas('.$pLon.','.$pLat.','.$pMax_km.')');
            
            $Resp = array
            (
                'data' => json_decode(json_encode($RS), true)
            );
            
            return response()->json($Resp);
        }
    }
}
