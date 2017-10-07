<?php
require_once __DIR__ . '/lib/head.php';
$repo = new UserRepository();
$s = new Security($repo);
if (!$s->isLoggin()) {
    header('Location: login.php');
    exit();
}
$setting = $repo->getSetting($s->getUser()->getId());

if (isset($_POST['save']) && isset($_FILES['logo']) &&
    $_FILES["logo"]["error"] == UPLOAD_ERR_OK) {

    $tempPath = $_FILES["logo"]["tmp_name"];
    $check = getimagesize($tempPath);
    if($check !== false) {
        $name = md5(uniqid()).'.'. pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $target_file = UPLOAD_DIR . DIRECTORY_SEPARATOR . $name;
        move_uploaded_file($tempPath, $target_file);

        if ($setting->getLogo()) {
            $old_file = UPLOAD_DIR . DIRECTORY_SEPARATOR . $setting->getLogo();
            unlink($old_file);
        }

        $setting = new Setting();
        $setting->setLogo($name)
            ->setIdUser($s->getUser()->getId())
            ->setParameters([]);

        $repo->saveSetting($setting);
        $saved = true;
    }
}
?>
<div class="container">
    <?php if (isset($saved)): ?>
        <div class="alert alert-dismissible alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Bien hecho!</strong> configuracion guardada.
        </div>
    <?php endif; ?>
    <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
        <fieldset>
            <legend>Configuraciones</legend>
            <div class="row">
                <div class="col-md-3 hidden-xs"></div>
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label for="file" class="col-lg-2 control-label">Logo</label>
                        <div class="col-lg-10">
                            <input type="file" class="form-control" id="file" name="logo" accept="image/*" required>
                        </div>
                    </div>
                    <?php if ($setting->getLogo()): ?>
                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-2">
                            <div style="margin: 0 auto;display: table;">
                                <img src="<?php echo "lib/data/".$setting->getLogo(); ?>" class="img-responsive">
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-2">
                            <button style="margin: 30px auto;display: table;" type="submit" name="save" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
</div>
<?php
include_once __DIR__ . '/lib/footer.php';
?>
