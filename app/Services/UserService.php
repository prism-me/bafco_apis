<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use auth;
use Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
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

    public function changePassword($data)
    {


        $user = User::where('id', $data['user_id'])->first();

        if ($user->exists()) {


            $password =  Hash::check($data['change_password'],  $user['password']);
            $currentPassword =  Hash::check($data['password'],  $user['password']);

            if ($currentPassword == 1) {

                if ($password == 1) {

                    echo json_encode(['message' => 'Current Password and New Password cannot be same']);
                } else {

                    $update['password'] = bcrypt($data['change_password']);
                    unset($data['user_id']);
                    unset($data['change_password']);
                    unset($data['confirm_password']);

                    $isUserUpdated = User::where('email', $user['email'])->update($update);

                    if ($isUserUpdated) {

                        echo json_encode(['message' => 'Data Updated successfully.']);
                    } else {

                        echo json_encode(['message' => 'Wrong Information']);
                    }
                }
            } else {

                echo json_encode(['message' => 'Wrong Current Password']);
            }
        } else {

            echo json_encode(['message' => 'User doesnot exist']);
        }
    }

    //convert guest user into registered user
    public function createUser($user)
    {
        DB::beginTransaction();

        try {

            $isExist = User::where('email', $user['customer']['email'])->first(['id']);
            $data = $user['billing_address'];
            $getId = $isExist;
            if (!$isExist) {

                $getId = User::create([
                    'name' => $data['first_name'] . $data['last_name'],
                    'email' => $user['customer']['email'],
                    'password' => bcrypt('Bafco123'),
                    'user_type' => 'user',
                ]);

                Address::create([
                    'user_id' => $getId->id,
                    'name' => $data['first_name'] . $data['last_name'],
                    'country' => $data['country'],
                    'state' => $data['state'],
                    'city' => $data['city'],
                    'address_line1' => $data['line1'],
                    'address_line2' => $data['line2'],
                    'postal_code' => $data['postal_code'],
                    'phone_number' => $data['phone'],
                    'default' => 1,
                    'address_type' => 'billing'
                ]);

            } else {

                Address::create([
                    'user_id' => $getId->id,
                    'name' => $data['first_name'] . " " . $data['last_name'],
                    'country' => $data['country'],
                    'state' => $data['state'],
                    'city' => $data['city'],
                    'address_line1' => $data['line1'],
                    'address_line2' => $data['line2'],
                    'postal_code' => $data['postal_code'],
                    'phone_number' => $data['phone'],
                    'default' => 1,
                    'address_type' => 'billing'
                ]);
            }

            DB::commit();
            return $getId->id;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}


//when user is logged in then only address id will be sent to me and i have to get it from database.


// in case of guest users we have to first check if that user exists or not


// if user exist then we dont need to resigter just add the address in the database

// if user does not exist then add address and user data into database
