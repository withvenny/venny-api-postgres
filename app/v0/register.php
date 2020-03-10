<?php

header('Content-Type: application/json');

// This class is in charge of sending new user (registartion) information to the server

// STEP 1. Receiving data passed to current PHP file. Executing IF statement. 
// If not all data has been passed, return / stop execution by throwing JSON message
if (empty($_REQUEST['email']) || empty($_REQUEST['firstName']) || empty($_REQUEST['lastName']) || 
empty($_REQUEST['password']) || empty($_REQUEST['birthday']) || empty($_REQUEST['gender'])) {
    
    $return['status'] = '400';
    $return['message'] = 'Missing user information';
    echo json_encode($return);
    return;
    
}

// using safe method to cast received data in current PHP 
$email = htmlentities($_REQUEST['email']);
$firstName = htmlentities($_REQUEST['firstName']);
$lastName = htmlentities($_REQUEST['lastName']);
$password = htmlentities($_REQUEST['password']);
$birthday = htmlentities($_REQUEST['birthday']);
$gender = htmlentities($_REQUEST['gender']);

// generating random 20 chars pseudo
$salt = openssl_random_pseudo_bytes(20);
$encryptedPassword = sha1($password . $salt);


// STEP 2. Build Connection
// Secure way to store Connection Infromation
$file = parse_ini_file("../../app.ini");   // accessing the file with connection infromation

//echo json_encode($file);
//exit;

// retrieve data from file
$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);

// include MySQLDAO.php for connection and interacting with db
require("secure/access.php");

// running MySQLDAO Class with constructed variables
$access = new access($host, $user, $pass, $name);
$access->connect();   // launch opend connection function


// STEP 3. Check availability of the login / user information
$user = $access->selectUser($email);

// found user with the same Email address
if (!empty($user)) {
    
    // throw back JSON to user
    $return['status'] = '400';
    $return['message'] = 'The Email is already registered';   
    
} else {
    
    // STEP 4. Send request to Insert the data in the server
    $result = $access->insertUser($email, $firstName, $lastName, $encryptedPassword, $salt, $birthday, $gender);
    
    // result is positive - inserted
    if ($result) {
        
        // select currently inserted user
        $user = $access->selectUser($email);
        
        // throw back the user details
        $return['status'] = '200';
        $return['message'] = 'Successfully registered';
        $return['id'] = $user['id'];
        $return['email'] = $email;
        $return['firstName'] = $firstName;
        $return['lastName'] = $lastName;
        $return['birthday'] = $birthday;
        $return['gender'] = $gender;
        $return['cover'] = $user['cover'];
        $return['ava'] = $user['ava'];
        $return['bio'] = $user['bio'];
        $return['allow_friends'] = $user['allow_friends'];
        $return['allow_follow'] = $user['allow_follow'];

    // result is negative - couldn't insert
    } else {
        
        $return['status'] = '400';
        $return['message'] = 'Could not insert information';
        
    }
    
}


echo json_encode($return);        
$access->disconnect();
