<?php

namespace App\Http\Controllers;

use App\Models\Finishes;
use App\Models\FinishesValue;
use App\Models\FinishesValuePivot;
use App\Models\Material;
use Illuminate\Http\Request;

class FinishesController extends Controller
{

    public function index()
    {

        //$finishes = Finishes::with('child.value')->where('parent_id', 0)->get();
        $finishes = Finishes::with('parent')->get();
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
                "seo" => isset($data['seo']) ? $data['seo'] : 0 ,

            ];
            $finishesCreated = Finishes::where('id', $data['id'])->update($finishesCreate);
            $finishesValueCreate = [
                "title" => isset($data['title']) ? $data['title'] : '',
                "finishes_id" => $data['id'],
                "material_id" => isset($data['material_id']) ? $data['material_id'] : '',
                "featured_img" => isset($data['featured_img']) ? $data['featured_img'] : '',
                "additional_img" => isset($data['additional_img']) ? $data['additional_img'] : '',
                "code" => isset($data['code']) ? $data['code'] : '',

            ];
            $FinishesValue = FinishesValue::where('finishes_id',$request->id)->where('material_id',$data['material_id'])->first();
            $FinishesValue->update($finishesValueCreate);
            $finishesValuePivotCreate = [
               'material_id' => $data['material_id'],
               'finishes_id' => isset($finishesCreated['id']) ? $finishesCreated['id'] : '' ,
               'finishes_value_id' => isset($FinishesValue->id) ? $FinishesValue->id : '',
            ];
            $finishesValuePivot = FinishesValuePivot::where('finishes_id',$request->id)->where('material_id',$data['material_id'])->first();
            $finishesValuePivot->update($finishesValuePivotCreate);

        }else {

                #create
                $finishesCreate = [
                    "name" => $data['name'],
                    "parent_id" => isset($data['category_id']) ? $data['category_id'] : 0,
                    "seo" => isset($data['seo']) ? $data['seo'] : 0 ,

                ];
                $finishesCreated = Finishes::firstOrCreate($finishesCreate);

                    $finishesValueCreate = [
                        "title" => isset($data['title']) ? $data['title'] : '',
                        "material_id" => isset($data['material_id']) ? $data['material_id'] : '',
                        "finishes_id" => $finishesCreated->id,
                        "featured_img" => isset($data['featured_img']) ? $data['featured_img'] : '',
                        "additional_img" => isset($data['additional_img']) ? $data['additional_img'] : '',
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

        $finshesValue = [
            'id' => $finishes->id,
            'name' => $finishes->name,
            'seo' => $finishes->seo,
            'category_id' => $finishes->parent_id,
            'material_id' => $finishes['value']['material_id'],
            'title' => $finishes['value']['title'],
            'featured_img' => $finishes['value']['featured_img'],
            'additional_img' => $finishes['value']['additional_img'],
            'code' => $finishes['value']['code']
        ];

        return response()->json($finshesValue);

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
