<?php


require_once( "server/Environment.php" );


$env = new Environment();

$env->restRequestHandler->handleRequest();


