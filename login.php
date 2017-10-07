<?php
require_once __DIR__ . '/lib/lib.php';
if (isset($_POST['login'])) {
    if (isset($_POST['email']) &&
        isset($_POST['pass'])) {
        $s->login($_POST['email'], $_POST['pass']);

        if ($s->isLoggin()) {
            header('Location: index.php');
            exit();
        }

        $message = 'Acceso denegado';
    }
}
include __DIR__ . '/lib/head.php';
?>
<style>
    @import url(http://fonts.googleapis.com/css?family=Roboto);
    .loginmodal-container {
        padding: 30px;
        max-width: 350px;
        width: 100% !important;
        background-color: #F7F7F7;
        margin: 0 auto;
        border-radius: 2px;
        box-shadow: 0 2px 2px rgba(0, 0, 0, 0.3);
        overflow: hidden;
    }

    .loginmodal-container h1 {
        text-align: center;
        font-size: 1.8em;
    }

    .loginmodal-container input[type=submit] {
        width: 100%;
        display: block;
        margin-bottom: 10px;
        position: relative;
    }

    .loginmodal-container input[type=text], input[type=email], input[type=password] {
        height: 44px;
        font-size: 16px;
        width: 100%;
        margin-bottom: 10px;
        -webkit-appearance: none;
        background: #fff;
        border: 1px solid #d9d9d9;
        border-top: 1px solid #c0c0c0;
        /* border-radius: 2px; */
        padding: 0 8px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .loginmodal-container input[type=text]:hover, input[type=email]:hover, input[type=password]:hover {
        border: 1px solid #b9b9b9;
        border-top: 1px solid #a0a0a0;
        -moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
        -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
    }
    .loginmodal-submit {
        /* border: 1px solid #3079ed; */
        border: 0;
        color: #fff;
        text-shadow: 0 1px rgba(0,0,0,0.1);
        background-color: #4d90fe;
        padding: 17px 0;
        font-size: 14px;
        /* background-image: -webkit-gradient(linear, 0 0, 0 100%,   from(#4d90fe), to(#4787ed)); */
    }

    .loginmodal-submit:hover {
        /* border: 1px solid #2f5bb7; */
        border: 0;
        text-shadow: 0 1px rgba(0,0,0,0.3);
        background-color: #357ae8;
        /* background-image: -webkit-gradient(linear, 0 0, 0 100%,   from(#4d90fe), to(#357ae8)); */
    }

    .loginmodal-container a {
        text-decoration: none;
        color: #666;
        font-weight: 400;
        text-align: center;
        display: inline-block;
        opacity: 0.6;
        transition: opacity ease 0.5s;
    }

    .login-help{
        font-size: 12px;
    }
</style>
<div class="container">
    <?php if (isset($message)): ?>
        <div class="alert alert-dismissible alert-warning">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Atencion!</h4>
            <p>Error de credenciales</p>
        </div>
    <?php endif; ?>
    <div class="loginmodal-container">
        <h1>Login</h1><br>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="pass" placeholder="Password" required>
            <input type="submit" name="login" class="login loginmodal-submit" value="Login">
        </form>

        <div class="login-help">
            <a href="register.php">Register</a>
        </div>
    </div>
</div>
<?php
include_once __DIR__ . '/lib/footer.php';
?>
