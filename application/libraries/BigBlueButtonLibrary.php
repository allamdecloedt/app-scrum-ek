<?php
defined('BASEPATH') or exit('No direct script access allowed');


use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;


class BigBlueButtonLibrary {
    private $bbb;

    public function __construct() {
       
        require_once APPPATH . '../vendor/autoload.php';
      
        $ci =& get_instance();
        $ci->load->config('bigbluebutton');
      
        $url = $ci->config->item('bbb_url');
        $secret = $ci->config->item('bbb_secret');
       

        if (!$url || !$secret) {
            throw new Exception('BigBlueButton configuration values are missing.');
        }
      
        // Pass only the base URL to the constructor
        $this->bbb = new BigBlueButton($url, $secret);
  
        // Set the secret using the appropriate method
    
    }

     /**
     * Créer une réunion.
     *
     * @param string $meetingID
     * @param string $meetingName
     * @param string $moderatorPW
     * @param string $attendeePW
     * @return \BigBlueButton\Responses\CreateMeetingResponse
     */
    public function createMeeting($meetingID, $meetingName, $moderatorPW, $attendeePW) {
     
        // $createParams = new CreateMeetingParameters($meetingID, $meetingName);
        // $createParams->setModeratorPassword($moderatorPW);
        // $createParams->setAttendeePassword($attendeePW);
        // $createParams->setDuration(60);
        //  $createParams->setRecord(true);
        // // $createParams->setWelcomeMessage('Bienvenue à tous!');

        $createParams = new CreateMeetingParameters($meetingID, $meetingName);
       
        $createParams->setModeratorPassword($moderatorPW);
        $createParams->setAttendeePassword($attendeePW);
        $createParams->setDuration(60);
       
        $createParams->setAllowStartStopRecording(true);
        $createParams->setAutoStartRecording(true); // Démarre automatiquement l'enregistrement
        $createParams->setGuestPolicy('ALWAYS_ACCEPT');
 
        $createParams->setWebcamsOnlyForModerator(true); // Désactive la restriction des webcams aux modérateurs
        // $createParams->setMuteOnStart(false); // Ne pas désactiver les micros au début
        $createParams->setAllowRequestsWithoutSession(true);

     
        $createParams->setWelcomeMessage('Bienvenue à tous !');

        $response = $this->bbb->createMeeting($createParams);
        if ($response->getReturnCode() === 'FAILED') {
            echo "Error: " . $response->getMessage();
            return;
        }

        // Supprimez cet appel redondant : return $this->bbb->createMeeting($createParams);
        return $response;


        // var_dump($this->bbb->createMeeting($createParams));
        // $response = $this->bbb->createMeeting($createParams);
        // if ($response->getReturnCode() === 'FAILED') {
        //     echo "Error: " . $response->getMessage();
        //     return;
        // }
        //  die('ffffff : '.$meetingName);
        return $this->bbb->createMeeting($createParams);
    }

    /**
     * Générer l'URL pour rejoindre une réunion.
     */
    public function joinMeeting($meetingID, $fullName, $password) {
        $joinParams = new JoinMeetingParameters($meetingID, $fullName);
        $joinParams->setPassword($password);
        
        return $this->bbb->getJoinMeetingURL($joinParams);
    }

    /**
     * Vérifier si une réunion est en cours.
     */
    public function isMeetingRunning($meetingID) {
        // isMeetingRunning attend un objet IsMeetingRunningParameters
        $params = new IsMeetingRunningParameters($meetingID);
        return $this->bbb->isMeetingRunning($params);
    }
    
    
    
}