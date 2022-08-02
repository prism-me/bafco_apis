<?php

namespace App\Services;
use App\Models\User;
use auth;


class UserService {


        public function update($data)
        {
            $user = User::where('id', $data['user_id'])->first();
            unset($data['user_id']);
            unset($data['email']);
                if ($user->exists()) {
                    if (!empty($data['password'])) {

                        $data['password'] = bcrypt($data['changed_password']);

                        $isUserUpdated = $user->update($data);

                    } else {

                        $isUserUpdated = $user->update($data);

                    }
                    if ($isUserUpdated) {

                        echo json_encode(['message' => 'Data Updated successfully.']);

                    } else {
                        echo json_encode(['message' => 'Wrong Information']);
                    }

                } else {

                    echo json_encode(['message' => 'User doesnot exist']);
                }
        }




}


