<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Meeting extends CI_Controller {
    public function __construct() {
        parent::__construct();
		// require_once APPPATH . '../vendor/autoload.php';
        $this->load->library('BigBlueButtonLibrary');
    }

    public function create() {
	
        $meetingID = 'demo123';
        $meetingName = 'Réunion Démo';
        $moderatorPW = 'mod123';
        $attendeePW = 'att123';
		// die('jjjjjjjjjjjjj');
        $response = $this->bigbluebuttonlibrary->createMeeting($meetingID, $meetingName, $moderatorPW, $attendeePW);

        if ($response->getReturnCode() === 'SUCCESS') {
            echo 'Meeting successfully created!';
        } else {
            echo 'Error: ' . $response->getMessage();
        }
    }

	public function join() {
		$meetingID = 'demo123'; // Assurez-vous que c'est une chaîne et non un tableau
		$fullName = 'Utilisateur Test';
		$password = 'att123';
		
		
		$joinURL = $this->bigbluebuttonlibrary->joinMeeting($meetingID, $fullName, $password);
		redirect($joinURL); // Redirige l'utilisateur vers l'URL de la réunion
	}

	public function isMeetingRunning($meetingID) {
		$response = $this->bigbluebuttonlibrary->isMeetingRunning($meetingID);
	
		if ($response->getReturnCode() === 'SUCCESS' && $response->isRunning()) {
			echo 'La réunion est en cours.';
			return true;
		} else {
			echo 'La réunion n’est pas encore démarrée.';
			return false;
		}
	}
	public function createAndJoinMeeting() {
        // Étape 1 : Créer une réunion
        $meetingID = 'demo1235ff';
        $meetingName = 'Réunion Démo';
        $moderatorPW = 'mod123';
        $attendeePW = 'att123';

        $response = $this->bigbluebuttonlibrary->createMeeting($meetingID, $meetingName, $moderatorPW, $attendeePW);
		
        if ($response->getReturnCode() === 'SUCCESS') {
            echo 'Réunion créée avec succès !';

            // Étape 2 : Obtenez le lien pour rejoindre en tant que modérateur
            $fullName = 'John Doe';
            $joinURL = $this->bigbluebuttonlibrary->joinMeeting($meetingID, $fullName, $moderatorPW);
			// $this->isMeetingRunning($meetingID);
            // Redirigez vers la réunion
            redirect($joinURL);
        } else {
            echo 'Erreur lors de la création de la réunion : ' . $response->getMessage();
        }
    }


	// ----------------------------------test
    // public function start()
    // {
    //     $meetingID   = 'testMeetingXYZ';
    //     $meetingName = 'test Meeting XYZ';
    //     $moderatorPW = 'mod1234';
    //     $attendeePW  = 'att1234';
    
    //     // 1) Créer la réunion via la librairie BigBlueButton
    //     $response = $this->bigbluebuttonlibrary->createMeeting(
    //         $meetingID,
    //         $meetingName,
    //         $moderatorPW,
    //         $attendeePW
    //     );
    
    //     // 2) Vérifier la réussite de la création
    //     if ($response->getReturnCode() === 'SUCCESS') {
    //         echo "Meeting successfully created!";
    //         echo "<br>Meeting ID: $meetingID";
    //         echo "<br>Moderator Password: $moderatorPW";
    //         echo "<br>Attendee Password: $attendeePW";
    
    //         // 3) Générer l’URL pour rejoindre en tant que modérateur
    //         $moderatorJoinURL = $this->bigbluebuttonlibrary->joinMeeting(
    //             $meetingID,
    //             'Moderator',     // Nom d’affichage dans la réunion
    //             $moderatorPW
    //         );
    //         echo "<br><a href='$moderatorJoinURL' target='_blank'>Join as Moderator</a>";
    
    //         // 4) Générer l’URL pour rejoindre en tant que participant
    //         $attendeeJoinURL = $this->bigbluebuttonlibrary->joinMeeting(
    //             $meetingID,
    //             'Attendee',      // Nom d’affichage dans la réunion
    //             $attendeePW
    //         );
    //         echo "<br><a href='$attendeeJoinURL' target='_blank'>Join as Attendee</a>";
    
    //         // 5) Vérifier si la réunion est en cours (optionnel)
    //         //    Tant qu'aucun participant n'a rejoint, isMeetingRunning() renverra "false".
    //         echo "<br>meetingID : ".$meetingID;
    //         $isRunning = $this->bbb->isMeetingRunning($meetingID);
            
           
    //         echo '<br> isRunning : '.$isRunning->getReturnCode();
    //         if ($isRunning->getReturnCode() === 'SUCCESS') {
    //             if ($isRunning->isRunning()) {
    //                 echo "<br>The meeting is currently running!";
    //             } else {
    //                 echo "<br>The meeting is not running yet (no one has joined).";
    //             }
    //         } else {
    //             echo "<br>Could not check if the meeting is running.";
    //         }
    
    //     } else {
    //         // 6) Gérer l’erreur
    //         echo "Error: " . $response->getMessage();
    //     }
    // }
    
    // public function start() {
    //     // 1) Définir quelques variables
    //     $meetingID   = 'testMeeting_'.uniqid();;
    //     $meetingName = 'Test Meeting';
    //     $moderatorPW = 'mod12388';
    //     $attendeePW  = 'att12388';

    //     // 2) Créer la réunion via notre library
    //     $response = $this->bigbluebuttonlibrary->createMeeting(
    //         $meetingID,
    //         $meetingName,
    //         $moderatorPW,
    //         $attendeePW
    //     );

    //     // 3) Vérifier le code retour
    //     if ($response->getReturnCode() === 'SUCCESS') {
    //         echo "Meeting successfully created!<br>";
    //         echo "Meeting ID: $meetingID<br>";
    //         echo "Moderator Password: $moderatorPW<br>";
    //         echo "Attendee Password: $attendeePW<br>";

    //         // 4) Générer URL modérateur
    //         $moderatorJoinURL = $this->bigbluebuttonlibrary->joinMeeting(
    //             $meetingID,
    //             'Moderator',
    //             $moderatorPW
    //         );
    //         echo "<a href='$moderatorJoinURL' target='_blank'>Join as Moderator</a><br>";

    //         // 5) Générer URL participant
    //         $attendeeJoinURL = $this->bigbluebuttonlibrary->joinMeeting(
    //             $meetingID,
    //             'Attendee',
    //             $attendeePW
    //         );
    //         echo "<a href='$attendeeJoinURL' target='_blank'>Join as Attendee</a><br>";

    //         // 6) Vérifier si la réunion est en cours
    //         $isRunningResponse = $this->bigbluebuttonlibrary->isMeetingRunning($meetingID);
    //         if ($isRunningResponse->getReturnCode() === 'SUCCESS') {
    //            print_r($isRunningResponse);
    //             if ($isRunningResponse->isRunning()) {
    //                 echo "The meeting is currently running!";
    //             } else {
    //                 echo "The meeting is not running yet (no one has joined).";
    //             }
    //         } else {
    //             echo "Could not check if the meeting is running.";
    //         }

    //     } else {
    //         // Erreur
    //         echo "Error creating meeting: " . $response->getMessage();
    //     }
    // }
    // public function start()
    // {
    //     $meetingID   = 'testMeeting_'.uniqid(); 
    //     $meetingName = 'Test Meeting';
    //     $moderatorPW = 'modPW';
    //     $attendeePW  = 'attPW';
    
    //     $response = $this->bigbluebuttonlibrary->createMeeting(
    //         $meetingID,
    //         $meetingName,
    //         $moderatorPW,
    //         $attendeePW
    //     );
    
    //     if ($response->getReturnCode() === 'SUCCESS') {
    //         echo "Meeting successfully created!<br>";
    //         echo "Meeting ID: $meetingID<br>";
    //                 // Vérifier si la réunion est en cours
    //     // $isRunning = $this->bigbluebuttonlibrary->isMeetingRunning($meetingID);
    //     print_r($response);
    //     $isRunning = $this->bigbluebuttonlibrary->isMeetingRunning($meetingID);
    //     print_r($isRunning);

    //     if ($isRunning->isRunning()) {
    //         echo "The meeting is running!<br>";

    //         // Générer URL modérateur
    //         $moderatorJoinURL = $this->bigbluebuttonlibrary->joinMeeting(
    //             $meetingID,
    //             'Moderator',
    //             $moderatorPW
    //         );
    //         echo "<a href='$moderatorJoinURL' target='_blank'>Join as Moderator</a><br>";

    //         // Générer URL participant
    //         $attendeeJoinURL = $this->bigbluebuttonlibrary->joinMeeting(
    //             $meetingID,
    //             'Attendee',
    //             $attendeePW
    //         );
    //         echo "<a href='$attendeeJoinURL' target='_blank'>Join as Attendee</a><br>";

    //     } else {
    //         echo "The meeting is not running. Please start the meeting first.<br>";
    //     }

    //     } else {
    //         echo "Error: " . $response->getMessage();
    //     }
    // }
    public function start()
{ 
    
    $meetingID   = 'testMeeting_'.uniqid(); 
    $meetingName = 'Test Meeting';
    $moderatorPW = 'modPW';
    $attendeePW  = 'attPW';

    // Créer la réunion
    $response = $this->bigbluebuttonlibrary->createMeeting(
        $meetingID,
        $meetingName,
        $moderatorPW,
        $attendeePW
    );
    // die('rrrrrrr');
    if ($response->getReturnCode() === 'SUCCESS') {
        echo "Meeting successfully created!<br>";
        echo "Meeting ID: $meetingID<br>";

        // Vérifiez si la réunion est active
        $isRunning = $this->bigbluebuttonlibrary->isMeetingRunning($meetingID);
        print_r($response);
        $isRunning = $this->bigbluebuttonlibrary->isMeetingRunning($meetingID);
        print_r($isRunning);

        if (!$isRunning->isRunning()) {
            echo "The meeting is not running. Attempting to join as Moderator...<br>";

            // Joindre en tant que modérateur pour démarrer la réunion
            $moderatorJoinURL = $this->bigbluebuttonlibrary->joinMeeting(
                $meetingID,
                'Moderator',
                $moderatorPW
            );
            // header("Location: $moderatorJoinURL");
            // exit;

            echo "<a href='$moderatorJoinURL' target='_blank'>Join as Moderator</a><br>";
        }

        // Générer l'URL pour un participant
        $attendeeJoinURL = $this->bigbluebuttonlibrary->joinMeeting(
            $meetingID,
            'Attendee',
            $attendeePW
        );

        echo "<a href='$attendeeJoinURL' target='_blank'>Join as Attendee</a><br>";
    } else {
        echo "Error: " . $response->getMessage();
    }
}


    public function joinAsAttendee() {
        $meetingID = 'test123';
        $attendeePW = 'att123';

        // Générer l'URL pour rejoindre en tant que participant
        $attendeeJoinURL = $this->bigbluebuttonlibrary->joinMeeting(
            $meetingID,
            'Attendee',
            $attendeePW
        );

        redirect($attendeeJoinURL);
    }
	
	
}
