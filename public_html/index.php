<?php
require '../vendor/autoload.php';
require '../config.php';

use Krizon\Google\Analytics\MeasurementProtocol\MeasurementProtocolClient;
use Rhumsaa\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Tybulewicz\ReferrerSpamDetector\Detector;

$request = Request::createFromGlobals();

$isSpam = false;
if ($request->server->has('HTTP_REFERER')) {
    $referrer = $request->server->get('HTTP_REFERER');
    $detector = new Detector();
    $isSpam = $detector->isSpam($referrer);
}

if (!$isSpam) {
    $client = MeasurementProtocolClient::factory(array(
        'ssl' => false // Enable/Disable SSL, default false
    ));
    $client->pageview(array(
        'tid' => $config['tracking-id'],
        'dh' => $config['hostname'],
        'dp' => $config['page'],
        'dt' => $config['title'],
        'cid' => Uuid::uuid4(),
        'ua' => $request->headers->get('User-Agent'),
        'uip' => $request->getClientIp(),
    ));
}

header("Location: {$config['link']}", true, 302);
exit();

