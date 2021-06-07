<?php
require "vendor/autoload.php";
session_start();

$invalidInputs = [];
$alertMessages = [];
$alertType = 'alert-danger';
$regexPassword = '/(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}/';

// check if form is submitted
$submittedForm = !empty($_POST);
if ($submittedForm) {

    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm = htmlspecialchars(trim($_POST['confirm']));

    //signup form checks
    if ($username == null) {
        array_push($alertMessages, 'Please enter your username');
        array_push($invalidInputs, 'username');
    }
    if (!preg_match($regexPassword, $password)) {
        array_push($alertMessages, 'Use a password with at least 8 characters and 1 number ');
        array_push($invalidInputs, 'password');
    }
    if ($password !== $confirm) {
        array_push($alertMessages, 'Passwords dont match');
        array_push($invalidInputs, 'confirm');
    }

    if (!count($alertMessages)) {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $db = new Database();

        try {
            $db->insert("
            INSERT INTO `users`(`username`, `password`) VALUES (:username,:password)",
                [
                    'username' => $username,
                    'password' => $password,
                ]
            );
        } catch (Exception $e) {
            preg_match('/[^:\s]+(?=;[^;]*$)/',$e,$match);
            if (array_shift($match) == '23000'){
                array_push($alertMessages,'User already exists');
            } else array_push($alertMessages,$e->getMessage());
        }

        header('Location: login.php?ref=registration&username=' . $username);
    }
}
?>

<?php require './incl/header.php'; ?>
    <main class="text-center px-md-4 m-5">
        <div class="container" style="width:50vw">
            <div class="d-flex justify-content-center">
                <form class="form-signup" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <?php if ($submittedForm): ?>
                        <div class="alert <?= $alertType; ?>"><?= implode('<br>', $alertMessages); ?></div>
                    <?php endif; ?>
                    <h1 class="mb-4 font-weight-normal">Please sign up</h1>
                    <label for="inputUsername" class="sr-only">Username</label>
                    <input autofocus=""
                           class="mb-3 form-control <?= in_array('username', $invalidInputs) ? ' is-invalid' : '' ?>"
                           id="inputUsername"
                           name="username" placeholder="Your username"
                           required=""
                           value="<?= $username ?? '' ?>">
                    <label for="inputPassword" class="sr-only">Password</label>
                    <input type="password"
                           id="inputPassword"
                           class="mb-3 form-control <?= in_array('password', $invalidInputs) ? ' is-invalid'     : '' ?>"
                           name="password" value="<?= $password ?? '' ?>"
                           placeholder="Password"
                           required="">
                    <label for="inputConfirm" class="sr-only">Confirm password</label>
                    <input type="password"
                           id="inputConfirm"
                           class="mb-3 form-control <?= in_array('confirm', $invalidInputs) ? ' is-invalid' : '' ?>"
                           name="confirm" value="<?= $confirm ?? '' ?>"
                           placeholder="Password again"
                           required="">
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
                </form>
            </div>
        </div>
    </main>
<?php require './incl/footer.php'; ?>