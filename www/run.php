<?php
$dir = __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR .'app'.DIRECTORY_SEPARATOR;
$files = [
    'IGrabber.php',
    'IOutput.php',
    'Dispatcher.php',
    'CurlGrabber.php',
    'Output.php',
    'Product.php',
];

foreach ($files as $file) {
    $fullFilePath = $dir . $file;
    if(file_exists($fullFilePath)) {
        require_once $fullFilePath;
    }
}

$fileOutput = new \App\Output();
$curlGrabber = new \App\CurlGrabber();

$dispatcher = new App\Dispatcher($curlGrabber, $fileOutput);
$dispatcher->loadInput(__DIR__.DIRECTORY_SEPARATOR. 'vstup.txt');
$result = $dispatcher->run();

echo $result;

