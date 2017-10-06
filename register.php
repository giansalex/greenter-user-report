<?php
require_once __DIR__ . '/lib/head.php';
if (isset($_POST['registrar'])) {
    $s = new Security(new UserRepository());
    $s->register($_POST['email'],$_POST['password']);

    if ($s->isLoggin()) {
        header('Location: index.php');
        exit();
    }
}
?>
<style>
    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        outline: none;
    }

    .login-form {
        margin: 50px auto;
        max-width: 760px;
    }

    .login-form .panel {
        border: 1px solid #e4e4e4 !important;
        border-radius: 5px !important;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
    }
    .login-form .panel hr {
        margin: 1.6em 0;
    }
    .login-form .panel a {
        color: #00aeef;
    }
    .login-form .panel .panel-body {
        padding: 10px 40px 10px 40px !important;
    }
    .login-form .panel .panel-footer {
        line-height: 2em;
        padding: 30px 40px 30px 40px !important;
        background: #f6f6f6 !important;
        color: #606060 !important;
    }
    form[role=login] {
        font: 16px/2.8em Lato, serif;
        color: #909090;
    }
    form[role=login] h2 {
        font-size: 34px;
        color: #000;
        margin-bottom: .5em;
    }
    form[role=login] input,
    form[role=login] select,
    form[role=login] button {
        font-size: 16px;
    }
    form[role=login] input.form-control {
        height: 2.2em;
    }
    form[role=login] input::-webkit-input-placeholder {
        color: #bbb;
    }
    form[role=login] input:-moz-placeholder {
        color: #bbb;
    }
    form[role=login] input::-moz-placeholder {
        color: #bbb;
    }
    form[role=login] input:-ms-input-placeholder {
        color: #bbb;
    }
    form[role=login] button {
        font-size: 16px;
        max-width: 320px;
    }
</style>
<div class="container">
    <section class="login-form">
        <div class="panel panel-default">
            <form method="post" action="">
                <div class="panel-body">
                    <h2>Sign up</h2>
                    <hr />
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                <input type="text" name="email" placeholder="Tu Email" required class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input type="password" name="password" placeholder="Contraseña" required class="form-control" />
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input type="password" name="password2" placeholder="Reingresar Contraseña" required class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button type="submit" name="registrar" class="btn btn-lg btn-block btn-primary">Registrarse</button>
                </div>
            </form>
        </div>

    </section>
</div>
<?php
include_once __DIR__ . '/lib/footer.php';
?>
