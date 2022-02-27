<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?= assets("assets/bootstrap/css/bootstrap.min.css") ?>">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!--    <link rel="stylesheet" href="--><?//= asset("assets/bootstrap/fonts/font-awesome.min.css") ?><!--">-->
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= env('APP_NAME')?> | default</title>
</head>
<body>
<div class="container mt-4">
    <div class="text-center">
        <h1>Ooops! Something went wrong!</h1>
        <code><?= __FILE__ ?></code>
        <p><a href="/">Go back ?</a></p>
    </div>
</div>

<!-- scripts -->
<script src="<?= assets("assets/bootstrap/js/bootstrap.min.js") ?>"></script>
<script src="<?= assets("assets/bootstrap/js/jquery-3.4.1.slim.min.js")?>"></script>
<script src="<?= assets("assets/bootstrap/js/popper.min.js")?>"></script>
</body>
</html>