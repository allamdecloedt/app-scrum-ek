<?php
require_once 'vendor/autoload.php';

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;

$bbb = new BigBlueButton([
    'url' => 'http://192.168.119.129/bigbluebutton/',
    'secret' => 'cnPnuRsbm2WcHP4gGrOcPo0xMCQaeHByDP2Mw0Pfm44',
]);
$createMeetingParams = new CreateMeetingParameters('demo123', 'Test Meeting');
$createMeetingParams->setModeratorPassword('mod123');
$createMeetingParams->setAttendeePassword('att123');

try {
    $response = $bbb->createMeeting($createMeetingParams);
    if ($response->getReturnCode() === 'SUCCESS') {
        echo 'Meeting created successfully!';
    } else {
        echo 'Error: ' . $response->getMessage();
    }
} catch (Exception $e) {
    echo 'Exception: ' . $e->getMessage();
}
