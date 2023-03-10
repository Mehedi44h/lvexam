<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function addSubject(Request $request){
        
        try{
            
            Subject::insert([
               'subjects' =>$request->subject
            ]);

            return response()->json([
                'success' => true, 'msg' =>'Subject added succesfully'
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'success'=>false,'msg'=>$e->getMessage()
            ]);
            
        }
    }
}