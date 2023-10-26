<?php
function is_valid_email($email) {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function is_exists_email($email){
    $users = getUsers();

    foreach($users as &$user){
        if($user['email'] === $email){
            return true;
        }
    }
    return false;
}

function is_valid_username($username) {
    return preg_match('/^[a-zA-Z0-9]{3,20}$/', $username);
}

function is_exists_username($username){
    $users = getUsers();

    foreach($users as &$user){
        if($user['username'] === $username){
            return true;
        }
    }
    return false;
}

function is_valid_password($password){
    return strlen($password)>=4 ? true:false;
}

function registerUser($username, $email, $password){
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $user = [
        'id' => uniqid(),
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword,
        'role' => 'user'
    ];

    $users = getUsers();
    $users[] = $user;
    file_put_contents("./userDetails.txt", json_encode($users));
}

function getUsers(){
    if(file_exists("./userDetails.txt")){
        $users = file_get_contents("./userDetails.txt");
        // echo strlen($users);
        if(strlen($users)>0){
        return json_decode($users,true);
        }
        else{
            return [];
        }
    }
    return [];
}

function authenticateUser($email, $password){
    $users = getUsers();
    foreach($users as $user){
        if($user['email'] === $email && password_verify($password, $user['password'])){
            return $user;
        }
    }
    return null;
}

function editUserRole($userId, $newRole){
    $users = getUsers();
    foreach($users as &$user){
        if($user['id'] === $userId){
            $user['role'] = $newRole;
            break;
        }
    }
    file_put_contents("./userDetails.txt", json_encode($users));
}

function editUser($userId, $username, $email, $role){
    $users = getUsers();
    foreach($users as &$user){
        if($user['id'] === $userId){
            $user['username']=$username;
            $user['email']=$email;
            $user['role'] = $role;
            break;
        }
    }
    file_put_contents("./userDetails.txt", json_encode($users));
}

function deleteUser($userId){
    $users = getUsers();
    foreach($users as $key => $user){
        if($user['id'] === $userId){
            unset($users[$key]);
            break;
        }
    }
    file_put_contents("./userDetails.txt", json_encode($users));
}

function addUser($role,$username, $email, $password){
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $user = [
        'id' => uniqid(),
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword,
        'role' => $role
    ];

    $users = getUsers();
    $users[] = $user;
    file_put_contents("./userDetails.txt", json_encode($users));
}
?>
