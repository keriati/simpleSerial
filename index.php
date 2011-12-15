<?php

    require_once 'simpleSerial.php';

    $mySimpleSerial = new simpleSerial();

    $mySimpleSerial->setSecret('56dfg6486af5g468wtg6f5h454kju8z');

    $serials = $mySimpleSerial->generateSerials(2);

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Serial Test</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <meta name="viewport" content="width=device-width,initial-scale=1">

</head>

<body>

    <div id="container">
        <h1>Serials:</h1>
        <ul>
            <?php foreach ($serials as $serial) : ?>

            <li>
                <?php  echo $serial; ?>
            </li>

            <?php endforeach; ?>
        </ul>
        <h1>Validate:</h1>
        <ul>
            <?php foreach($serials as $serial) : ?>

            <li>
                <?php echo ($mySimpleSerial->validateSerial($serial)) ? $serial . ' is valid!' : ' is not valid!' ;?>
            </li>

            <?php endforeach; ?>
        </ul>

    </div>

</body>
</html>