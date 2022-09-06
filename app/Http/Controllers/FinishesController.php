<?php

namespace App\Http\Controllers;

use App\Models\Finishes;
use Illuminate\Http\Request;

class FinishesController extends Controller
{
    
    public function index()
    {

        
    }

 

    public function store(Request $request)
    {

        $data = $request->all();
        $finishesCreate = [
            "name" => $data->get("name"),
            "parent_id" => $data->get("category_id"),
        ];
        $finishesCreate = Finishes::create($finishesCreate);
        if($data->category_id != null){

            $finishesValueCreate = [
                "name"  => $data->title,
                "featured_img"  => $data->featured_img,
                "code"  => $data->code,
                "material_id"  => $data->material_id
            ];
            $FinishesValue = FinishesValue::create($finishesValueCreate);

            $finishesValuePivotCreate = [
                'material_id' => $data->material_id,
                'finishes_id' => $finishesCreate->id,
                'finishes_value_id' => $finishesValueCreate->finishes_value_id,
            ];
            $FinishesValuePivot = FinishesValuePivot::create($finishesValuePivotCreate);

        }
        
    }

  
    public function show(Finishes $finishes)
    {
        
    }

 


    public function update(Request $request, Finishes $finishes)
    {
        
    }

    public function destroy(Finishes $finishes)
    {
        
    }
}
