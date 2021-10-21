<?php

$data = [
"data" => "hello worl",
"expired" => 3600
];
$token = "4edc3e02a3ccf20c213131efa271b79b.vJjR1CIHHdfiCj4Tt+weTtnAZ7PVQw7e1eeQtdT3/qWY43pZH91r9mO92UhXrJB2NGoSv10j.c2f8ab9f30e19e14d47a6491ca77fe36";
$tken = new Wepesi\App\Token();
$token = $tken->generate($data);
// var_dump($token);
// echo "\n\n";

$dec = $tken->decode($token);
var_dump($dec);