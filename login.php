<?php
require "vendor/autoload.php";
session_start();

$submittedForm = !empty($_POST);
if($submittedForm) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db = new Database();

    @$user = $db->select('SELECT * FROM `users` WHERE `username` = :username',
        [
            'username'=> $username
        ]
    );
    if(password_verify($password, $user[0]['password'])) {
        $_SESSION['user_id'] = $user[0]['id'];
        $_SESSION["flash"] = ["type" => "success", "message" => "You are now signed in!"];

        header('Location: index.php');
    } else {
        echo '<button type="button" class="btn btn-danger" disabled>You are not signed in.</button>';
    }
}

?>

<?php require './incl/header.php'; ?>
    <main class="text-center px-md-4 m-5">
        <div class="container" style="width:50vw">
            <div class="d-flex justify-content-center">
                <form class="form-signup" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <h1 class="mb-4 font-weight-normal">Please sign in</h1>
                    <label for="inputUsername" class="sr-only">Username</label>
                    <input type="username"
                           id="inputUsername"
                           class="form-control mb-3"
                           name="username" value="<?= $username ?? '' ?>"
                           placeholder="Your username"
                           required=""
                           autofocus="">
                    <label for="inputPassword" class="sr-only">Password</label>
                    <input type="password"
                           id="inputPassword"
                           class="form-control mb-3"
                           name="password" value="<?= $password ?? '' ?>"
                           placeholder="Password"
                           required="">
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                </form>
            </div>
        </div>
    </main>
<?php require './incl/footer.php'; ?>