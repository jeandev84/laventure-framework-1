<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="icon" sizes="300x300" href="/media/v1/favicon.png?>" type="image/png"/>
    <title><?= env('APP_NAME')?></title>
    <style>
        /*
        .block {
           margin: auto 0;
        }

        .block img {
            display: block;
            margin: 0 auto;
        }
        */
         */
    </style>
</head>
<body>
    <div class="container-md text-center block">
        <?php include('partials/nav.php'); ?>
        <div>
            <img src="<?= assets('images/laventure.png') ?>" alt="laventure.png">
        </div>
        <div>
            <small>You are ready for building a web application ... :)</small>
        </div>
    </div>

</body>
</html>