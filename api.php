<?php
require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload
use Libreja\SruCatalog;
$sruCatalog = new SruCatalog\CatalogMain();

$requested = $_GET;
$sruCatalog->service = $requested["service"];
header('Content-Type: application/json');
echo json_encode($sruCatalog->parse($requested["fields"]));
