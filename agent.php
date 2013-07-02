<?php
session_start();
require_once('askfast/AskFast.php');
require_once('askfast/lib/session.php');
require_once('askfast/lib/answerresult.php');

    $filename = 'agent.php';
    $askfast = new AskFast();

    function app_start() {
        global $askfast;
        global $filename;
        $session = new Session();
        
        $askfast->say('/audio/start.wav', $filename.'?function=hangup');
        $askfast->finish();
    }
        
    function app_hangup() {
        global $askfast;
        $askfast->hangup();
        $askfast->finish();
    }
    
    function app_failure() {
        
        $askfast->say('/audio/fout.wav');
        $askfast->finish();
    }

    $function    =    'start';

    if (isset($_REQUEST['function']) && $_REQUEST['function'] != '') {
        $function    =    $_REQUEST['function'];
    }

    switch ($function) {
        case 'hangup':        app_hangup();        break;
        case 'start':        app_start();        break;
        default:        app_failure();
    }
?>
