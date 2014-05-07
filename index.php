<?php

use Silex\Application;

require_once __DIR__.'/config.php';

require_once __DIR__.'/vendor/autoload.php';

$cb = new Couchbase(
    COUCHBASE_HOSTS,
    COUCHBASE_USERNAME,
    COUCHBASE_PASSWORD,
    COUCHBASE_BUCKET,
    COUCHBASE_CONN_PERSIST
);

$app = new Silex\Application();

$app->get('/hello/{name}', function($name) use($app) {
    return 'Hello '.$app->escape($name);
});

$app->get('/', function() use($app, $cb) {

    $results = $cb->view("brewery", "by_name_doc");

    return json_encode( $results['rows'] ) ;
});


$app->get('/state/{name}', function($name) use($app, $cb) {

    $results = $cb->view("state", "by_state", array ( "key" => $name ) );

    return json_encode( $results['rows'] ) ;
});


$app->run();


