<?php
require '../vendor/autoload.php';

$app = (new Tracker\Routes\App())->get();
$app->run();
