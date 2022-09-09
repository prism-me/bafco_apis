<?php

namespace App\Http\Controllers;

use App\Models\Finishes;
use App\Models\FinishesValuePivot;
use App\Models\FinishesValue;
use App\Models\Material;

use Illuminate\Http\Request;

class FinishesController extends Controller
{

    public function index()
    {

        $finishes = Finishes::with('child.value')->get();
        return response()->json($finishes);

    }

    public function store(Request $request)
    {

        $data = $request->all();

        #Update
        if(Finishes::where('id',$request->id)->exists()){
            $finishesCreate = [
                "name" => $data['name'],
                "parent_id" => isset($data['category_id']) ? $data['category_id'] : 0 ,
            ];
            $finishesCreated = Finishes::firstOrUpdate($finishesCreate);
            $finishesValueCreate = [
                "finishes_id" => $finishesCreated['id'],
                "featured_img" => isset($data['featured_img']) ? $data['featured_img'] : '',
                "code" => isset($data['code']) ? $data['code'] : '',

            ];
            $FinishesValue = FinishesValue::where('finishes_id',$request->id)->firstOrUpdate($finishesValueCreate);
            $finishesValuePivotCreate = [
               'material_id' => $data['material_id'],
               'finishes_id' => isset($finishesCreated['id']) ? $finishesCreated['id'] : '' ,
               'finishes_value_id' => isset($FinishesValue->id) ? $FinishesValue->id : '',
            ];
            $finishesValuePivot = FinishesValuePivot::where('finishes_id',$request->id)->firstOrUpdate($finishesValuePivotCreate);



        }else {

                #create
                $finishesCreate = [
                    "name" => $data['name'],
                    "parent_id" => isset($data['category_id']) ? $data['category_id'] : 0,
                ];
                $finishesCreated = Finishes::firstOrCreate($finishesCreate);

                    $finishesValueCreate = [
                        "finishes_id" => $finishesCreated->id,
                        "featured_img" => isset($data['featured_img']) ? $data['featured_img'] : '',
                        "code" => isset($data['code']) ? $data['code'] : '',

                    ];
                    $FinishesValue = FinishesValue::firstOrCreate($finishesValueCreate);

                    $finishesValuePivotCreate = [
                        'material_id' => $data['material_id'],
                        'finishes_id' => isset($finishesCreated->id) ? $finishesCreated->id : '' ,
                        'finishes_value_id' => isset($FinishesValue->id) ? $FinishesValue->id : '',
                    ];
                    $finishesValuePivot = FinishesValuePivot::create($finishesValuePivotCreate);

        }


        return response()->json('Data Saved Successfully');

    }

    public function show( $id)
    {

        $finishes = Finishes::where('id',$id)->with('value')->first();
        //$finshesValue = FinishesValue::where('id',$id)->first();

        return response()->json($finishes);

    }

    public function destroy($id)
    {
        $finishes = Finishes::where('id',$id)->delete();
        return response('Data Deleted Successfully')->json();
    }


    public function finishesCategoryList(){
        $finishes = Finishes::get(['id','name']);
        return response()->json($finishes);
    }

    public function materialList()
    {
        $material = Material::get(['id','name']);
        return response()->json($material);
    }

    }
