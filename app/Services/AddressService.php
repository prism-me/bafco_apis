<?php

namespace App\Services;
use App\Models\Address;
use App\Models\User;

class AddressService {

    public function addAddress($data){

        $user =  $data['user_id'];
        $exist = Address::where('user_id',$user)->first();
        $data = [
            'user_id' => $data['user_id'],
            'country' => $data['country'],
            'state' => $data['state'],
            'city' => $data['city'],
            'address_line1' => $data['address_line1'],
            'address_line2' => $data['address_line2'],
            'postal_code' => $data['postal_code'],
            'phone_number' => $data['phone_number'],
            'address_type' => $data['address_type'],
        ];
        if( $exist != null) {



            if (!empty($data['id'])) {

                #update
                $address = Address::where('id', $data['id'])->update($data);

            } else {

                #create
                $address = Address::create($data);
            }


        }else{

            $data['default'] = 1;
            $address = Address::create($data);



        }

        if ($address) {
            return response()->json('Data has been saved.', 200);
        }
    }

    public function setDefaultAddress($data,$id){

        $setDefault = [
            'default' => 1
        ];

        $unsetPreviousDefaultValue = [
            'default' => 0
        ];
        $previousDafaultAddress = Address::where('user_id',$data['user_id'])->where('default' , '=' , 1)->first();

        if(($previousDafaultAddress == null)){

            $updatePrevious = Address::where('id',$id)->update($setDefault);

        }else{
            $updatePrevious = $previousDafaultAddress->update($unsetPreviousDefaultValue);
            $updatePrevious = Address::where('id',$id)->update($setDefault);
        }

        $address = Address::where('id',$id)->update($setDefault);
        if($address){
            return  response()->json('Data has been updated.' , 200);
        }
    }
}
