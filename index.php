<?php
require_once __DIR__ . '/lib/head.php';

if (!$s->isLoggin()) {
    header('Location: login.php');
}
?>
<div class="container">
    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="lib/print.php">
        <fieldset>
            <legend>Xml File</legend>
            <div class="form-group">
                <label for="xml" class="col-lg-2 control-label">Xml File</label>
                <div class="col-lg-10">
                    <input type="file" class="form-control" id="xml" name="xml" >
                </div>
            </div>
            <div class="form-group">
                <label for="logo" class="col-lg-2 control-label">Logo Image</label>
                <div class="col-lg-10">
                    <input type="file" class="form-control" id="logo" name="logo">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-10 col-lg-offset-2">
                    <button type="reset" class="btn btn-default">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>
<?php
include_once __DIR__ . '/lib/footer.php';
?>

