<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?= assets("assets/bootstrap/css/bootstrap.min.css") ?>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= env('APP_NAME')?> | default</title>
</head>
<body>
<div class="container mt-4">
    <div class="">
        <?php

        // TODO Implement Error Handler
        echo '<div class=""><h2>Fatal error</h2>'. "\n";
        echo "<b>Message</b> : ". $e->getMessage()."<br>\n";
        echo '<b>Code</b> : '. $e->getCode().'<br>'."\n";
        echo '<b>Line</b> : '. $e->getLine().'<br>'. "\n";
        echo '<b>File path</b> : '. $e->getFile().'<br>'. "\n";
        echo '<b>Trace String</b> : ';

        /*
        foreach($traces = explode('#', $e->getTraceAsString()) as $trace) {
            echo '<div><a href="#">'. $trace . '</a></div>';
        }

        ///echo 'Trace : '. implode('<br>', $e->getTrace());
        echo 'Details';
        $i = 1;
        foreach ($e->getTrace() as $trace)
        {
            echo '<ul>Trace '. $i++;
            echo '<li>File : <a href="#">'. $trace['file'] .'</a></li>';
            echo '<li>Line : <a href="#">'. $trace['line'] .'</a></li>';
            echo '<li>Function : <a href="#">'. $trace['function'] .'</a></li>';
            echo '<li>Class : <a href="#">'. $trace['class'] .'</a></li>';
            echo '<li>Type : <a href="#">'. $trace['type'] .'</a></li>';
            echo '<li>Args : </li>';
            dump($trace['args']);
            echo '</ul>';
        }
        echo '</div>';
        */
        echo '<a href="/">Go back</a>';
        ?>
    </div>
</div>

<!-- scripts -->
<?= assets("assets/bootstrap/js/bootstrap.min.js") ?>
<?= assets("assets/bootstrap/js/jquery-3.4.1.slim.min.js")?>
<?= assets("assets/bootstrap/js/popper.min.js")?>
</body>
</html>