<?php
require_once __DIR__ . '/lib/head.php';

if (!$s->isLoggin()) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['']))
?>
<div class="container">
    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="print.php">
        <fieldset>
            <legend>Cargar archivo XML</legend>
            <div class="form-group">
                <label for="xml" class="col-lg-2 control-label">Xml File</label>
                <div class="col-lg-10">
                    <input type="file" class="form-control" id="xml" name="xml" accept=".xml" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-10 col-lg-offset-2">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>
<?php
include_once __DIR__ . '/lib/footer.php';
?>

