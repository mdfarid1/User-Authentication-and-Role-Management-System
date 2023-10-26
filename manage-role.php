<?php
session_start();
include './functions.php';
$usernameError = $emailError = $passwordError = "";

if(isset($_SESSION['user'])){

    $user = $_SESSION['user'];
    if(!($user['role'] === 'admin')){
        header("Location: ./index.php");
    }


    $users = getUsers();


    if(isset($_POST['addUser'])){
        $role = trim($_POST['role']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if(!is_valid_username($username)){
            $usernameError = "* username is not valid!";
        }
        if(is_exists_username($username)){
            $usernameError = "* username is already exists!";
        }
        if(!is_valid_email($email)){
            $emailError = "* email is not valid!";
        }
        if(is_exists_email($email)){
            $emailError = "* email is already exists!";
        }
        if(!is_valid_password($password)){
            $passwordError = "* password is too short!";
        }


        if($usernameError == "" && $emailError=="" && $passwordError==""){
            addUser($role, $username, $email, $password);

            header("Location: ./manage-role.php");
        }
    }

    
    if(isset($_POST['editRole'])){
        $userId = $_POST['userId'];
        $newRole = $_POST['role'];
        editUserRole($userId, $newRole);
        header("Location: ./manage-role.php");
    }

    if(isset($_POST['saveChanges'])){
        $userId = $_POST['userId'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        echo $userId . " " . $username . " " . $email . " " . $role;
        editUser($userId,$username,$email,$role);
        header("Location: ./manage-role.php");
    }

    if(isset($_POST['deleteuser'])){
        $userId = $_POST['userId'];
        deleteUser($userId);
        header("Location: ./manage-role.php");
    }

}else{
    header("Location: ./login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Management</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <div class="container mt-4 p-3"  style="background-color:#FF99FF; box-shadow: 0 0 10px rgba(4, 5, 6, 5);" >
        <div class="row" >
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><strong>ADMIN PANEL</strong></h2>
                    </div>
                    <div class="d-flex">
                        <p class="me-3">Welcome, <?php echo $_SESSION['user']['username']; ?>!</p>
                        <a href="./logout.php" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3>Admin Details</h3>
                        <p><strong>Username:</strong> <?php echo $user['username'];?></p>
                        <p><strong>Email:</strong> <?php echo $user['email'];?></p>
                        <p><strong>Role:</strong> <?php echo $user['role'];?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-3">
        <h2 class="mx-auto">Role Management</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createRoleModal">Create User</button>
        <table class="table table-bordered table-striped">
            <thead class="">
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <!-- <th>Edit Role</th> -->
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php
                $roles=['User','Manager','Admin'];

                foreach($users as $oneUser){
                    
                    if(!($oneUser['username'] === $user['username'])){
                        $str = json_encode($oneUser);
                        echo "<tr>
                        <td>{$oneUser['username']}</td>
                        <td>{$oneUser['email']}</td>
                        <td>{$oneUser['role']}</td>
                        <td>
                            <form method='post' action=''>
                                <button onclick='return passValue(JSON.parse(\"" . addslashes($str) . "\"))' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editRoleModal'>Edit</button>

                                <input type='hidden' id='userID' name='userId' value='{$oneUser['id']}'></input>
                                <button type='submit' onclick='return confirmDelete()' class='btn btn-danger btn-sm' name='deleteuser'>Delete</button>
                            </form>
                        </td>
                        </tr>";
                    }else{
                        
                        // $str = json_encode($oneUser);
                        echo "<tr class='table-success'>
                        <td>{$oneUser['username']}</td>
                        <td>{$oneUser['email']}</td>
                        <td>{$oneUser['role']}</td>
                        <td>
                            
                        </td>
                        </tr>";
                    }
                }
                
                ?>
            </tbody>
        </table>
    </div>


    <!-- Create Role Modal -->
    <div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php $_SERVER['PHP_SELF']?>">
                        <div class="mb-3">
                            <label for="editRole" class="form-label">Role</label>
                            <select class="form-select" id="editRole" name="role" required>
                                <option value="user">User</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label> <?php echo "<label for='username' class='text-danger'>$usernameError</label>";?>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label> <?php echo "<label for='email' class='text-danger'>$emailError</label>";?>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label> <?php echo "<label for='password' class='text-danger'>$passwordError</label>";?>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="addUser">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" method="POST" action="<?php $_SERVER['PHP_SELF']?>">
                        <div class="mb-3">
                            <label for="editRole" class="form-label">Role</label>
                            <select class="form-select" id="Role" name="role" required>
                                <option value="user">User</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="userName" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="Email" name="email" required>
                        </div>
                        <input type="hidden" id="editUserId" name="userId">
                        <button type="submit" class="btn btn-primary" name="saveChanges">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this record?");
        }

        function passValue(obj){
            document.getElementById('Role').value = obj['role'];
            document.getElementById('userName').value = obj['username'];
            document.getElementById('Email').value = obj['email'];
            document.getElementById('editUserId').value = obj['id'];

            // document.getElementById('editUserId').value = user;
            // console.log(obj);
            console.log(obj['id']);

            return false;
        }
    </script>
</body>

</html>
