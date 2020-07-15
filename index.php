<?php
require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload
use Libreja\SruCatalog;
$sruCatalog = new SruCatalog\ServicesList();
 $rows = [
    "title" => [],
    "author" => [],
    "secondaryPerson" => [],
    "listingPrice" => [],
    "publisher" => [],
    "datePublished" => ["function"=>"date"],
    "publisherPlace" => [],
   // "isbn10" => [],
    "isbn13" => [],
    "numberOfPages" => [],
//    "inLanguage" => [""], // formatLanguage

    "permalink" => [
      "render" => "if(type === 'display') {
       data = '<a href=\"' + data + '\" target=\"_blank\">&#x1F517;</a>';
        }"],
    "boid" => ["hide" => true],
  ];


$columns = "";
foreach($rows as $row => $option){
  if(!isset($option["hide"])) {
    $columns .= ($columns ? ",
      " : "") . '{ data: "' . $row . '"';
    if (isset($option["render"])) {
      $columns .= ', render: function(data, type, row, meta){
          ' . $option["render"] . 'return data;}';
    }
    $columns .= '}';
  }else{
    unset($rows[$row]);
  }
}
?><!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.21/b-1.6.2/b-colvis-1.6.2/datatables.min.css"/>
    <style>
        div.dataTables_wrapper {
            width: 100%;
            margin: 0 auto;
        }
    </style>
  <title>Document</title>
</head>
<body>


<div class="container">
    <h1>SRU Catalog Search</h1>
    <form name="appbundle_bibliographysearch" method="post" class="modal-form-new order">



      <div class="row">
        <div class="col-sm-6 col-lg-3">
          <label class="control-label" for="appbundle_bibliographysearch_all">Alles</label>
          <input type="text" id="appbundle_bibliographysearch_all" name="appbundle_bibliographysearch[all]" class="form-control">
        </div>
        <div class="col-sm-6 col-lg-3">
          <label class="control-label" for="appbundle_bibliographysearch_title">Gesamttitel</label>
          <input type="text" id="appbundle_bibliographysearch_title" name="appbundle_bibliographysearch[title]" maxlength="800" class="form-control">
        </div>
        <div class="col-sm-6 col-lg-3">
          <label class="control-label" for="appbundle_bibliographysearch_author">Person</label>
          <input type="text" id="appbundle_bibliographysearch_author" name="appbundle_bibliographysearch[author]" maxlength="750" class="form-control">
        </div>
        <div class="col-sm-6 col-lg-3">
          <label class="control-label" for="appbundle_bibliographysearch_isxn">ISXN</label>
          <input type="text" id="appbundle_bibliographysearch_isxn" name="appbundle_bibliographysearch[isxn]" class="form-control">
        </div>
      </div>

      <div class="row">
        <div class="col-sm-6 col-lg-3">
          <label class="control-label" for="appbundle_bibliographysearch_year">Erschienen im Jahr</label>
          <input type="text" id="appbundle_bibliographysearch_year" name="appbundle_bibliographysearch[year]" class="form-control">
        </div>
        <div class="col-sm-6 col-lg-3">
          <label class="control-label" for="appbundle_bibliographysearch_publisher">Verlag</label>
          <input type="text" id="appbundle_bibliographysearch_publisher" name="appbundle_bibliographysearch[publisher]" maxlength="60" class="form-control">
        </div>
        <div class="col-sm-6 col-lg-3">
          <label class="control-label" for="appbundle_bibliographysearch_publisherPlace">Verlagsort</label>
          <input type="text" id="appbundle_bibliographysearch_publisherPlace" name="appbundle_bibliographysearch[publisherPlace]" maxlength="50" class="form-control">
        </div>
        <div class="col-sm-6 col-lg-3">
          <label class="control-label" for="appbundle_bibliographysearch_corporation">Körperschaft</label>
          <input type="text" id="appbundle_bibliographysearch_corporation" name="appbundle_bibliographysearch[corporation]" maxlength="300" class="form-control">
        </div>

      </div>
      <div class="row">
        <div class="col-sm-6 col-lg-3">
          <label class="control-label" for="appbundle_bibliographysearch_idn">Katalogsnr. (zb. PPN)</label>
          <input type="text" id="appbundle_bibliographysearch_idn" name="appbundle_bibliographysearch[idn]" class="form-control">
        </div>
        <div class="col-sm-6 col-lg-3">
          <label class="control-label required" for="appbundle_bibliographysearch_service">Katalog</label>
          <select id="appbundle_bibliographysearch_service" name="appbundle_bibliographysearch[service]" class="form-control">
              <option value="gvk" data-supported-fields="all,title,author,subject,idn,isxn,isbn,issn,publisher,publisherPlace,year,language,corporation">Gemeinsamer Verbundkatalog (GVK)</option>
              <option value="bvb" data-supported-fields="title,author,subject,idn,gndId,isbn,isxn,year">Bibliotheksverbund Bayern</option>
              <option value="dnb" data-supported-fields="all,title,fullTitle,author,subject,isxn,publisher,publisherPlace,year,language,corporation,idn">Deutsche Nationalbibliothek</option>
              <option value="loc" data-supported-fields="all,title,author,subject,isbn,issn,lccn,isxn,corporation,idn">Library of Congress</option>
<!--              <option value="amazon" data-supported-fields="all">Amazon</option>-->
          </select>
        </div>
        <div class="col-sm-6 col-lg-3">
          <br>
          <button class="btn btn-primary" id="searchform_submit" type="submit"><span id="search_search">Suche </span><span id="search_running" style="display:none;"><span id="search_running_text">Suche läuft</span> &#8987;</span></button>

          <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
        </div>
        <div class="col-sm-6 col-lg-1">
          <label class="control-label required" for="appbundle_bibliographysearch_maximumRecords">Anzahl</label>
          <select id="appbundle_bibliographysearch_maximumRecords" name="appbundle_bibliographysearch[maximumRecords]" class="form-control"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>
        </div>

      </div>

    </form>

    <div id="bibliography_datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
        <table id="bibliography_datatable" class="table table-striped table-bordered dataTable no-footer" style="width:100%">
            <thead>
                <?php foreach($rows as $row => $val){
                    echo '<th>'.
                      $row.
                    '</th>';
                }
                ?>
            </thead>
        </table>

    </div>
</div>

    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.21/b-1.6.2/b-colvis-1.6.2/datatables.min.js"></script>    <script type="text/javascript">

        //Hide and show input fields based on Service
        $("#appbundle_bibliographysearch_service").change(function() {
          var fields = $(this).children('option:selected').data('supported-fields').split(",");//create array of supported fields
          $('input[name^="appbundle_bibliographysearch"]').each(function() {
            var name = $(this).attr("name").replace( /(^.*\[|\].*$)/g, '' );
            $(this).prop( "disabled", ( fields.indexOf(name) == -1 ) );
          });
        });

        var timer1;
        function takesLonger(){
          // if a query takes longer than 2 seconds
          if($("#searchform_submit").attr("disabled") ){
            $("#search_running_text").html("taking longer");
          }
        }

        //prevent errors
        // $.fn.dataTable.ext.errMode = 'none';
        table = $('#bibliography_datatable') .on( 'error.dt', function ( e, settings, techNote, message ) {
          console.log( 'An error has been reported by DataTables: ', message );
          buttonEn();
        } ).DataTable({
          //initiliaze Datatables
          scrollX: true,
          order: [], //disable initial ordering
          dom: "<'row'<'col-sm-8 datatableInfo'><'col-sm-4'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-4'><'col-sm-4'p><'col-sm-4'l>>",
          data: [],
          columns: [<?php echo $columns; ?> ]
        });
        $("div.datatableInfo").html('<br />Info: <span id="recordsCount"></span>');

        //if request is sent, disable submit button
        $(".modal-form-new").submit(function(e) {
          if (e) e.preventDefault();
          $("#searchform_submit").attr("disabled", true);

          $("#search_search").hide();
          $("#search_running").show();

          timer1 = setTimeout(takesLonger, 2000);

          var form = $(this).serialize();
          var url = "datatable-api.php?" + form;

          table.ajax.url(url).load(function (data) {
            if(data.error) {
              $(".dataTables_empty").html(data.errorMessage);
            }
            // $("#recordsCount").html(data.numberOfRecords + " {{ "bibliography.entries"|trans }} {% if constant('TEST') == "_test" %}<a href='"+ data.service.lasturl + "'>API</a>{%  endif %}");
            clearTimeout(timer1);
            buttonEn();
          });
          return false;

        });

        //enable submit button again
        function buttonEn(){
          $("#searchform_submit").attr("disabled", false);
          $("#search_running").hide();
          $("#search_running_text").html("running");
          $("#search_search").show();
        }


      </script>
</body>
</html>