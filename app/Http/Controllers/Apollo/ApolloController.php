<?php

namespace App\Http\Controllers\Apollo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Random;
use App\Models\Breakdown;
use DB;
use paginate;
class ApolloController extends Controller
{
    //

    public function create(){
        if(Random::count() != 0){
            return response()->json([
                "message" => "Already filled"
            ]);
        }

        /* 
        
        5.	Create a loop (random number of iterations from 5-10) and inside that, we need to generate a random 
        name for its value which we will save in Random (table) together with another loop (random number of iterations from 5-10) 
        that will generate a random 5-character ( alphanumeric ) string for the breakdown (table)
        
        */
        $const_iteration = 5;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $lettersLength = strlen($letters);
        $randomString = '';
        $breakDownString = '';
        $randomStringArray = array();
        $breakDownStringArray = array();

        /*6.	Use your eloquent relationships for creating and retrieving the data from your database*/
        //DB::beginTransaction();
        try{
            for ($i = 0; $i < $const_iteration; $i++) {
                $randomString = '';
                
                //Values inside random
                for($j = 0; $j < $const_iteration; $j++){
                    $randomString .= $characters[rand(0, $charactersLength-1)];
                }
                $randomModel = Random::create(["values" => $randomString]);
                for($k = 0; $k < $const_iteration; $k++){
                    $breakDownString = '';
                    for($k1 = 0; $k1 < $const_iteration; $k1++){
                        $breakDownString .= $randomString[rand(0, strlen($randomString)-1)];
                    }
                    array_push($breakDownStringArray, $breakDownString);
                    $randomModel->breakdown()->create(["values" => $breakDownString]);
                    
                }
                $breakDownStringArray = implode(" ", $breakDownStringArray);
                
                array_push($randomStringArray, $randomString);
                $randomStringArray[$randomString][] = $breakDownStringArray;
                $breakDownStringArray = array();
                
                
              }
              DB::commit();
              
        }
        catch(\Exception $e){
            //DB::rollback();
            return response()->json([
                'errors'    =>  [ 'Can`t create your entry as of now. Contact the developer to fix it. Error Code : AM-comp-0x05' ],
                'msg'   =>  $e->getMessage()
             ],500);
        }
        
        return response()->json([
            "message" => "Controller Connectefddsffsafsdfsdd!",
            "random" => $randomStringArray,
        ]);
    }

    public function view(){
        return response()->json([
            "breakdown" => Breakdown::paginate(25), 
            "random" => Random::paginate(5)
        ]);
    }
}
