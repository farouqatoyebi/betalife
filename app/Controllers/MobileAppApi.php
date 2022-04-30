<?php

namespace App\Controllers;

use App\Models\UserModel;
use Config\Validation;

class MobileAppApi extends BaseController
{
    public function index()
    {
        # code...
    }
    
    // Process user registration
    public function processSignUp()
    {
        $response = [];
        helper(['form']);

        // Only post requests are accepted
        if ($this->request->getMethod() == "post") {
            $validation =  \Config\Services::validation();

            $rules = [
                "firstname" => [
                    "label" => "First Name", 
                    "rules" => "required|min_length[3]|max_length[20]"
                ],
                "lastname" => [
                    "label" => "Last Name", 
                    "rules" => "required|min_length[3]|max_length[20]"
                ],
                "email" => [
                    "label" => "Email", 
                    "rules" => "required|min_length[3]|max_length[20]|valid_email|is_unique[users.email]"
                ],
                "phone" => [
                    "label" => "Phone Number", 
                    "rules" => "required|min_length[8]|max_length[20]"
                ],
                "password" => [
                    "label" => "Password", 
                    "rules" => "required|min_length[8]|max_length[20]"
                ],
                "password_confirm" => [
                    "label" => "Confirm Password", 
                    "rules" => "matches[password]"
                ]
            ];

            if ($this->validate($rules)) {
                $user = new UserModel();

                $userRegData = [
                    "first_name" => $this->request->getVar("firstname"),
                    "last_name" => $this->request->getVar("lastname"),
                    "email" => $this->request->getVar("email"),
                    "phone" => $this->request->getVar("phone"),
                    "password" => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
                    "date_created" => date("Y-m-d H:i:s"),
                ];

                $formSubmitted = $user->submitSignUpForm($userRegData);

                if ($formSubmitted) {
                    $response["message"] = "Registration Successful.";
                }
                else {
                    $response["message"] = "We could not complete your request. Please try again.";
                }
            } 
            else {
                $response["validation"] = $validation->getErrors();
            }
        }

        return $this->response->setJSON($response);
    }

    // Process user logging in
    public function processSignIn()
    {
        $response = [];
        helper(['form']);

        if ($this->request->getMethod() == "post") {
            $validation =  \Config\Services::validation();

            $rules = [
                "email" => [
                    "label" => "Email", 
                    "rules" => "required|min_length[3]|max_length[20]|valid_email"
                ],
                "password" => [
                    "label" => "Password", 
                    "rules" => "required|min_length[8]|max_length[20]"
                ],
            ];

            if ($this->validate($rules)) {
                $user = new UserModel();
                $email = $this->request->getVar("email");
                $password = $this->request->getVar("password");

                $userLoggedIn = $user->authenticateUser($email, $password);

                if (count($userLoggedIn)) {
                    $response["message"] = 'Logged in successfully';
                    $response["user"] = $userLoggedIn;
                }
                else {
                    $response["message"] = "Invalid Username/Password";
                }
            }
            else {
                $response["validation"] = $validation->getErrors();
            }
        }

        return $this->response->setJSON($response);
    }

    // User Profile
    public function userInformation()
    {
        $response = [];
        helper(['form']);

        if ($this->request->getMethod() == "post") {
            $validation =  \Config\Services::validation();

            $rules = [
                "email" => [
                    "label" => "Email", 
                    "rules" => "required|min_length[3]|max_length[20]|valid_email"
                ],
            ];

            if ($this->validate($rules)) {
                $user = new UserModel();
                $email = $this->request->getVar("email");

                $userInformation = $user->getUsersInformation($email);

                if ($userInformation) {
                    $response["message"] = $userInformation;
                }
                else {
                    $response["message"] = "Invalid Request.";
                }
            }
            else {
                $response["validation"] = $validation->getErrors();
            }
        }

        return $this->response->setJSON($response);
    }
}
