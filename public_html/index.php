<?php
require '../vendor/autoload.php';
require '../config.php';

use Krizon\Google\Analytics\MeasurementProtocol\MeasurementProtocolClient;
use Rhumsaa\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();

$client = MeasurementProtocolClient::factory(array(
    'ssl' => false // Enable/Disable SSL, default false
));
$client->pageview(array(
    'tid' => $config['tid'],
    'dh' => $config['hostname'],
    'dp' => $config['page'],
    'dt' => $config['title'],
    'cid' => Uuid::uuid4(),
    'ua' => $request->headers->get('User-Agent'),
    'uip' => $request->getClientIp(),
));

header("Location: {$config['link']}", true, 302);
exit();

