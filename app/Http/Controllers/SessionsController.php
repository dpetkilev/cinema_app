<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class SessionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $PageParam = substr(trim(Input::get("page")), 0, 3);
        $CntParam = substr(trim(Input::get("cnt")), 0, 3);
        
        $DispPage = 1;
        $RecPerPage = 5;
        
        if($PageParam != ''){
            $DispPage = $PageParam;}
        
        if($CntParam != ''){
            $RecPerPage = $CntParam;}
 
        $RecOffset = $RecPerPage * $DispPage - $RecPerPage;

        $RS_Obj = DB::select('call cnm_get_sessions(null, null, null, '.$RecOffset.', '.$RecPerPage.')');
        
        if(count($RS_Obj) > 0)
        {
        
            $RS = json_decode(json_encode($RS_Obj), true);

            $TotalRecCount = $RS[0]['RecCount'];
        
            $PrevPage = null;
            $NextPage = null;
        
            if($DispPage > 1){
                $PrevPage = $DispPage - 1;}
        
            if($RecOffset + $RecPerPage < $TotalRecCount){
                $NextPage = $DispPage + 1;}
        
            $Resp = array
            (
                'data' => $RS,
                'total_count' => $TotalRecCount,
                'rec_per_page' => $RecPerPage,
                'disp_page' => $DispPage,
                'prev_page' => $PrevPage,
                'next_page' => $NextPage,
                'last_page' => ceil($TotalRecCount/$RecPerPage)
            );         
        }
        else
        {
            $Resp = array
            (
                'error' => array
                (
                    array
                    (
                       'param' => 'DB',
                       'message' => 'Search did not return any results'
                    )
                )
            ); 
        }       
        return response()->json($Resp);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        echo "create";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        echo "store";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        echo "Show";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        echo "edit";
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
        echo "udate";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        echo "destroy";
    }
////////////////////////////////////////////////////////////////////////////////    
    public function GetCinemaSessions($id, $date)
    {     
        $FormDataOK = true;

// Pagination control
        $PageParam = substr(trim(Input::get("page")), 0, 3);
        $CntParam = substr(trim(Input::get("cnt")), 0, 3);
        
        $DispPage = 1;
        $RecPerPage = 5;
        
        if($PageParam != ''){
            $DispPage = $PageParam;}
        
        if($CntParam != ''){
            $RecPerPage = $CntParam;}
 
        $RecOffset = $RecPerPage * $DispPage - $RecPerPage;
// end of Pagination control       
        $pID = substr(trim($id), 0, 3);
        $pDate = substr(trim($date), 0, 10);
        
        $Resp = array
        (
            'error' => array()
        );
        
        if(!preg_match('/^[0-9]*$/', $pID))
        {        
            $FormDataOK = false;
            $NewEntry =  array
            (
                'param' => 'ID',
                'message' => 'ID parameter must contain only numbers'
            );
            array_push($Resp['error'],$NewEntry);
        }
        if(!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $pDate))
        {        
            $FormDataOK = false;
            $NewEntry =  array
            (
                'param' => 'Date',
                'message' => 'Wrong date format, must be YYYY-MM-DD'
            );
            array_push($Resp['error'],$NewEntry);
        }
        else
        {
            $Year = substr($pDate,0,4);
            $Month = substr($pDate,5,2);
            $Day = substr($pDate,8,2);
            
            if(!checkdate($Month, $Day, $Year))
            {
                $FormDataOK = false;
                $NewEntry =  array
                (
                    'param' => 'Date',
                    'message' => 'Invalid date'
                );
                array_push($Resp['error'],$NewEntry);               
            }
        }
        
        if(!$FormDataOK)
        {
            return response()->json($Resp);
        }
        else 
        {       
            $RS_Obj = DB::select('call cnm_get_sessions('.$pID.', null, \''.$date.'\', '.$RecOffset.', '.$RecPerPage.')');

            if(count($RS_Obj) > 0)
            {                
                $RS = json_decode(json_encode($RS_Obj), true);

                $TotalRecCount = $RS[0]['RecCount'];
        
                $PrevPage = null;
                $NextPage = null;
        
                if($DispPage > 1){
                    $PrevPage = $DispPage - 1;}
        
                if($RecOffset + $RecPerPage < $TotalRecCount){
                    $NextPage = $DispPage + 1;}
        
                $Resp = array
                (
                    'data' => $RS,
                    'total_count' => $TotalRecCount,
                    'rec_per_page' => $RecPerPage,
                    'disp_page' => $DispPage,
                    'prev_page' => $PrevPage,
                    'next_page' => $NextPage,
                    'last_page' => ceil($TotalRecCount/$RecPerPage)
                );                                
            }
            else
            {
                $Resp = array
                (
                    'error' => array
                    (
                        array
                        (
                            'param' => 'DB',
                            'message' => 'Search did not return any results'
                        )
                    )
                ); 
            }
            return response()->json($Resp);
        }
    }
////////////////////////////////////////////////////////////////////////////////    
    public function GetMovieSessions($id, $date)
    {     
        $FormDataOK = true;
 // Pagination control
        $PageParam = substr(trim(Input::get("page")), 0, 3);
        $CntParam = substr(trim(Input::get("cnt")), 0, 3);
        
        $DispPage = 1;
        $RecPerPage = 5;
        
        if($PageParam != ''){
            $DispPage = $PageParam;}
        
        if($CntParam != ''){
            $RecPerPage = $CntParam;}
 
        $RecOffset = $RecPerPage * $DispPage - $RecPerPage;
// end of Pagination control         
        $pID = substr(trim($id), 0, 3);
        $pDate = substr(trim($date), 0, 10);
        
        $Resp = array
        (
            'error' => array()
        );
        
        if(!preg_match('/^[0-9]*$/', $pID))
        {        
            $FormDataOK = false;
            $NewEntry =  array
            (
                'param' => 'ID',
                'message' => 'ID parameter must contain only numbers'
            );
            array_push($Resp['error'],$NewEntry);
        }
        if(!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $pDate))
        {        
            $FormDataOK = false;
            $NewEntry =  array
            (
                'param' => 'Date',
                'message' => 'Wrong date format, must be YYYY-MM-DD'
            );
            array_push($Resp['error'],$NewEntry);
        }
        else
        {
            $Year = substr($pDate,0,4);
            $Month = substr($pDate,5,2);
            $Day = substr($pDate,8,2);
            
            if(!checkdate($Month, $Day, $Year))
            {
                $FormDataOK = false;
                $NewEntry =  array
                (
                    'param' => 'Date',
                    'message' => 'Invalid date'
                );
                array_push($Resp['error'],$NewEntry);               
            }
        }
        
        if(!$FormDataOK)
        {
            return response()->json($Resp);
        }
        else 
        {       
            $RS_Obj = DB::select('call cnm_get_sessions(null, '.$pID.', \''.$date.'\', '.$RecOffset.', '.$RecPerPage.')');

            if(count($RS_Obj) > 0)
            {
                $RS = json_decode(json_encode($RS_Obj), true);

                $TotalRecCount = $RS[0]['RecCount'];
        
                $PrevPage = null;
                $NextPage = null;
        
                if($DispPage > 1){
                    $PrevPage = $DispPage - 1;}
        
                if($RecOffset + $RecPerPage < $TotalRecCount){
                    $NextPage = $DispPage + 1;}
        
                $Resp = array
                (
                    'data' => $RS,
                    'total_count' => $TotalRecCount,
                    'rec_per_page' => $RecPerPage,
                    'disp_page' => $DispPage,
                    'prev_page' => $PrevPage,
                    'next_page' => $NextPage,
                    'last_page' => ceil($TotalRecCount/$RecPerPage)
                );
            }
            else
            {
                $Resp = array
                (
                    'error' => array
                    (
                        array
                        (
                            'param' => 'DB',
                            'message' => 'Search did not return any results'
                        )
                    )
                ); 
            }
            return response()->json($Resp);
        }
    }
////////////////////////////////////////////////////////////////////////////////    
    public function GetDateSessions($date)
    {     
        $FormDataOK = true;
// Pagination control
        $PageParam = substr(trim(Input::get("page")), 0, 3);
        $CntParam = substr(trim(Input::get("cnt")), 0, 3);
        
        $DispPage = 1;
        $RecPerPage = 5;
        
        if($PageParam != ''){
            $DispPage = $PageParam;}
        
        if($CntParam != ''){
            $RecPerPage = $CntParam;}
 
        $RecOffset = $RecPerPage * $DispPage - $RecPerPage;
// end of Pagination control          
        $pDate = substr(trim($date), 0, 10);
        
        $Resp = array
        (
            'error' => array()
        );

        if(!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $pDate))
        {        
            $FormDataOK = false;
            $NewEntry =  array
            (
                'param' => 'Date',
                'message' => 'Wrong date format, must be YYYY-MM-DD'
            );
            array_push($Resp['error'],$NewEntry);
        }
        else
        {
            $Year = substr($pDate,0,4);
            $Month = substr($pDate,5,2);
            $Day = substr($pDate,8,2);
            
            if(!checkdate($Month, $Day, $Year))
            {
                $FormDataOK = false;
                $NewEntry =  array
                (
                    'param' => 'Date',
                    'message' => 'Invalid date'
                );
                array_push($Resp['error'],$NewEntry);               
            }
        }
        
        if(!$FormDataOK)
        {
            return response()->json($Resp);
        }
        else 
        {       
            $RS_Obj = DB::select('call cnm_get_sessions(null, null, \''.$date.'\', '.$RecOffset.', '.$RecPerPage.')');

            if(count($RS_Obj) > 0)
            {
                $RS = json_decode(json_encode($RS_Obj), true);

                $TotalRecCount = $RS[0]['RecCount'];
        
                $PrevPage = null;
                $NextPage = null;
        
                if($DispPage > 1){
                    $PrevPage = $DispPage - 1;}
        
                if($RecOffset + $RecPerPage < $TotalRecCount){
                    $NextPage = $DispPage + 1;}
        
                $Resp = array
                (
                    'data' => $RS,
                    'total_count' => $TotalRecCount,
                    'rec_per_page' => $RecPerPage,
                    'disp_page' => $DispPage,
                    'prev_page' => $PrevPage,
                    'next_page' => $NextPage,
                    'last_page' => ceil($TotalRecCount/$RecPerPage)
                );
            }
            else
            {
                $Resp = array
                (
                    'error' => array
                    (
                        array
                        (
                            'param' => 'DB',
                            'message' => 'Search did not return any results'
                        )
                    )
                ); 
            }
            return response()->json($Resp);
        }
    }
    
    public function userauth()
    {
        echo "Authentication";
    }
}
