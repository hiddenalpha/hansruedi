<?php


require_once( "server/Environment.php" );


// Defaults
//header( "Content-Type: text/plain;charset=utf-8" ); // TODO: Set charset to utf-8


$env = new Environment();

$env->restRequestHandler->handleRequest();




