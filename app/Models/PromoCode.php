<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $table ='promo_codes';

    use HasFactory;
    protected $fillable = [ 
      'name', 
      'value', 
      'usage', 
      'usage_per_person', 
      'start_date', 
      'end_date'
    ];
    public function code(){
        return $this->hasMany(PromoUser::class,'promo_code_id','name');
    }
}
