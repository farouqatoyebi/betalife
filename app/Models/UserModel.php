<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class UserModel extends Model
{
    public function submitSignUpForm($data)
    {
        $db = Database::connect();
        $builder = $db->table('users');
        $builder->insert($data);

        return true;
    }

    public function authenticateUser($email, $password)
    {
        $db = Database::connect();
        $builder = $db->table('users');
        $user_info = $db->query("SELECT * FROM users WHERE email = '".trim($email)."'");
        $user_info = $user_info->getRow();

        if ($user_info) {
            if (password_verify($password, $user_info->password)) {
                return array(
                    'first_name' => $user_info->first_name,
                    'last_name' => $user_info->last_name,
                    'email' => $user_info->email,
                    'phone' => $user_info->phone,
                );
            }
        }

        return false;
    }

    public function getUsersInformation($user_email)
    {
        $db = Database::connect();
        $builder = $db->table('users');
        $user_info = $db->query("SELECT * FROM users WHERE email = '".trim($user_email)."'");
        $user_info = $user_info->getRow();

        if ($user_info) {
            $user_info = array(
                'first_name' => $user_info->first_name,
                'last_name' => $user_info->last_name,
                'email' => $user_info->email,
                'phone' => $user_info->phone,
            );
        }
        else {
            $user_info = [];
        }

        return $user_info;
    }
}