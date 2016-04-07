<!doctype html>
<?php

//require_once('checklogin.php');
require_once('init.php');
$mode = -1; //0edit, 1del, 2new
$cmd;//current database request;
$user;
$page = "permissions";
session_start();
//Check if the user is an admin
if (!isset($_SESSION['pigeon_admin'])){
    //Redirect to unauth page indicating reason for login denial
    header("Location: http://hcs.mcmaster.ca/apps/pigeon/Main/unauth.php?service=".$page);
    die();
}


if (isset($_POST['edit'])){
    $resultstat;
    $dbaction = new Db();
    $edit_user = $dbaction->edit_user_permission($_POST['edit'], $_POST['user_level']);
    if ($edit_user == 1){
        $resultstat = 'success';
    }else{
        $resultstat = 'bad';
    }

    //Set browser header
    header('Location: permissions.html.php?id='.$resultstat);
}

if (isset($_POST['del'])){
    $resultstat;
    $dbaction = new Db();
    $edit_user = $dbaction->del_user($_POST['del']);
    if ($edit_user == 1){
        $resultstat = 'success';
    }else{
        $resultstat = 'bad';
    }

    header('Location: permissions.html.php?id='.$resultstat);
}

if (isset($_POST['addnew'])){
// 	echo $_POST['del'];
// 	exit;
    $resultstat;
    $dbaction = new Db();
    $edit_user = $dbaction->add_user($_POST['new_user_name'], $_POST['user_level']);
    if ($edit_user == 1){
        $resultstat = 'success';
    }else{
        $resultstat = 'bad';
    }

    header('Location: permissions.html.php?id='.$resultstat);
}

if (isset($_GET['cmd']) && isset($_GET['user'])){
    $cmd = $_GET['cmd'];
    if ($cmd == 'edit'){
        $mode = 0;
        $user = $_GET['user'];
    }elseif($cmd == 'del'){
        $mode = 1;
        $user = $_GET['user'];
    }

}elseif(isset($_GET['cmd'])){
    $cmd = $_GET['cmd'];
    if ($cmd = 'addnew'){
        $mode = 2;
    }


}

?>

<html>
    <head>
        <title>Permissions</title>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.2/css/uikit.min.css" />
    </head>
    <body>
        <div class="uk-grid">
        <div class="uk-panel uk-panel-box uk-panel-box-primary uk-width-1-2 uk-container-center uk-text-center">
        <h1>Welcome to the hyper-sophisticated user-management system.</h1>
        <p>
            You can add, edit or delete users.  Admin users will have access to analytic data and functions, and this page here.  Regular users will only be able to use the inspection app.  If you delete yourself or set yourself as a user, you still have admin access until you log out, so you can fix any accidental user-self-immolation.
        </p>

        <?php
        if ($mode >= 0){

        ?>

        <h3>Action</h3>



        <?php
            if ($mode == 0){
                echo 'Edit '.$user.'\'s permission level (select option)';

                ?>
                <form action = "permissions.html.php" method = "post">
                    <input type="hidden" name="edit" value="<?php echo $user; ?>" />
                    <select name="user_level">
                        <option value="1">User</option>
                        <option value="2">Admin</option>
                    </select>
                    <input type="submit" value="Submit Edit" />
                </form>

                <?php

            }elseif($mode == 1){
                echo 'Delete'.$user.'?  Are you certain?';

                ?>
                <form action = "permissions.html.php" method = "post">
                    <input type="hidden" name="del" value="<?php echo $user; ?>" />
                    <input type="submit" value="Yes, delete user." />
                </form>

                <?php

            }elseif($mode == 2){
                echo 'Add New User<br>';
                ?>
                <form action = "permissions.html.php" method = "post">
                    <input type="hidden" name="addnew" value="1" />
                    <label>User macid</label>
                    <input type = "text" name ="new_user_name"></input>
                    <label>User Level</label>
                    <select name="user_level">
                        <option value="1">User</option>
                        <option value="2">Admin</option>
                    </select>
                    <input type="submit" value="Submit new user" />
                </form>
                <?php
            }


        }

        ?>

        <h3><a class="uk-button uk-button-primary" href = 'permissions.html.php?cmd=addnew'>Add User</a></h3>

        <table class="uk-table uk-text-left">
            <tr>
                <th>User</th><th>Level</th><th>action</th>
            </tr>
            <?php

                $users = new Db();
                $list_users = $users->get_all_users();

                while($row = $list_users->fetch_assoc()){
                    echo '<tr>';
                    echo '<td>'.$row['macid'].'</td><td>'.$row['level'].'</td><td><a href = permissions.html.php?cmd=edit&user='.$row['macid'].'>edit</a> | <a href = permissions.html.php?cmd=del&user='.$row['macid'].'>del</a></td>';
                    echo '</tr>';
                }

            ?>


        </table>
        </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.2/js/uikit.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.2/js/components/grid.min.js"></script>
    </body>
</html>
