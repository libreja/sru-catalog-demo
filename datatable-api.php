<?php
require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload
use Libreja\SruCatalog;


function jsonAPI($request)
{
  $rows = [
    "title" => [],
    "author" => [],
    "secondaryPerson" => [],
    "listingPrice" => [],
    "publisher" => [],
    "datePublished" => ["function"=>"date"],
    "publisherPlace" => [],
     "isbn10" => [],
    "isbn13" => [],
    "numberOfPages" => [],
    "inLanguage" => [], // formatLanguage

    "permalink" => [
      "render" => "if(type === 'display') {
       data = '<a href=\"' + data + '\" target=\"_blank\">&#x1F517;</a>';
        }"],
    "boid" => ["hide" => true],
  ];


  $sruCatalog = new SruCatalog\CatalogMain();
  $sruCatalog->service = $request["service"];
  unset($request["service"]);
  $params = [
  "maximumRecords" => $request["maximumRecords"]
];

  $data = $sruCatalog->parse($request,$params);
  $json = ["error" => $data["error"], "numberOfRecords" => $data["numberOfRecords"], "fields" => $request, "service" => NULL, "data" => []];
  foreach ($data["records"] as $record) {
    $medium = [];
    foreach ($rows as $row => $options) {
      $medium[$row] = $record[$row];
      if(@$options["function"] == "date" && $medium[$row]){
        $medium[$row] = ($medium[$row]); //Error supressed for certain dates...
      }
      if($medium[$row] === false){
        $medium[$row] =  null;
      }
    }
    $json["data"][] = $medium;
  }
  return json_encode($json);
}
header('Content-Type: application/json');

echo jsonAPI($_GET["appbundle_bibliographysearch"]);