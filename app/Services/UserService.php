<?php

namespace App\Services;
use App\Models\User;
use auth;

use Hash;

class UserService {


        public function update($data)
        {
            $user = User::where('id', $data['user_id'])->first();
            unset($data['user_id']);
            unset($data['email']);
                if ($user->exists()) {
//                    if (!empty($data['password'])) {
//
//                        $data['password'] = bcrypt($data['changed_password']);
//
//                        $isUserUpdated = $user->update($data);
//
//                    } else {

                        $isUserUpdated = $user->update($data);

                    //}
                    if ($isUserUpdated) {

                        echo json_encode(['message' => 'Data Updated successfully.']);

                    } else {
                        echo json_encode(['message' => 'Wrong Information']);
                    }

                } else {

                    echo json_encode(['message' => 'User doesnot exist']);
                }
        }

        public function changePassword($data){

            $user = User::where('id', $data['user_id'])->first();

            if ($user->exists()) {
                $password =  Hash::check( $data['password'],  $user['password']);
                if($password == 1){

                    echo json_encode(['message' => 'Current Password and New Password cannot be same.']);

                }else{

                    $data['password'] = bcrypt($data['password']);
                    unset($data['user_id']);
                    unset($data['change_password']);
//
//                   User::where('id', $data['user_id'])->first();
                    $isUserUpdated = User::where('email', $user['email'])->update($data);

                    if ($isUserUpdated) {

                        echo json_encode(['message' => 'Data Updated successfully.']);

                    } else {

                        echo json_encode(['message' => 'Wrong Information']);
                    }

                }





            } else {

                echo json_encode(['message' => 'User doesnot exist']);
            }

        }




}


