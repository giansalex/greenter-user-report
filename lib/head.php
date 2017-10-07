<!DOCTYPE html>
<html lang="es">
<head>
    <title>Report Sunat</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://bootswatch.com/paper/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-nav">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand">XML to PDF</a>
        </div>
        <div class="collapse navbar-collapse" id="menu-nav">
            <ul class="nav navbar-nav navbar-right" style="font-size: 18px">
            <?php if ($s->isLoggin()): ?>
                <li>
                    <a href="./" title="Upload">
                        <i class="fa fa-upload"></i> Upload
                    </a>
                </li>

                <li>
                    <a href="settings.php" title="config">
                        <i class="fa fa-cog"></i> Configuracion
                    </a>
                </li>
                <li>
                    <a href="lib/logout.php" title="Logout">
                        <i class="fa fa-toggle-on"></i> Logout
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="login.php" title="Login">
                        <i class="fa fa-toggle-off"></i> Login
                    </a>
                </li>
            <?php endif; ?>
            </ul>
        </div>

    </div>
</nav>