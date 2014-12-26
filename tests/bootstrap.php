<?php

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('Fucoso\ORM\Tests', __DIR__ . '/');

define('TESTS_DIR', __DIR__);
define('ORM_CONFIG_FILE', __DIR__ . '/config.json');

// Setup the test database
//$tmpDir = __DIR__ . '/../tmp';
//$dbPath = "$tmpDir/test.db";
//
//if (!file_exists($tmpDir)) {
//    mkdir($tmpDir);
//}
//
//if (file_exists($dbPath)) {
//    unlink($dbPath);
//}
//
//$pdo = new PDO("sqlite:$dbPath");
//
//$sqlPath = __DIR__ . '/travis/sqlite/setup.sql';
//$sql = file_get_contents($sqlPath);
//$pdo->exec($sql);
//unset($pdo);
