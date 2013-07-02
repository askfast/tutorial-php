<?php
session_start();
require_once('askfast/AskFast.php');
require_once('askfast/lib/session.php');
require_once('askfast/lib/answerresult.php');

    $filename = 'agent.php';
    $askfast = new AskFast();
    
    // Please fill your database information and uncomment
    //define("DB_SERVER", "");
    //define("DB_USER", "");
    //define("DB_PASS", "");
    //define("DB_DB", "");
    
    function getDBConn() {
        $conn = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
        mysql_select_db(DB_DB);
        
        return $conn;
    }

    function app_start() {
        global $askfast;
        global $filename;
        $session = new Session();
        
        $askfast->say('/audio/start.wav', $filename.'?function=bid&responder='.$session->getResponder());
        $askfast->finish();
    }
    
    function app_bid() {
        
        $session = new Session();
        $responder = str_ireplace("@outbound","",$session->getResponder());
        
        getDBConn();
        $maxBids = 2;
        $query = "SELECT * FROM bid";
        $res = mysql_query($query);
        $count = 0;
            while(($row = mysql_fetch_assoc($res))!==false) {
                if($row["Phonenumber"]==$responder)
                    return app_double_bid();
            
            $count++;
        }
        
        if($count>=$maxBids)
            return app_bidding_closed();
        
        return app_request_bid();        
    }
    
    function app_receivebid() {
        
        getDBConn();
        $answer = new AnswerResult();
        $responder = str_ireplace("@outbound","",$_GET["responder"]);
        
        $query = "INSERT INTO bid (Amount, Phonenumber, DateTime) VALUES (".$answer->getAnswerText().", \"".$responder."\", NOW())";
        $res = mysql_query($query);
        
        return app_thankyou();
    }
    
    function app_request_bid() {
        
        global $askfast;
        global $filename;
                
        $session = new Session();
        $askfast->ask('/audio/bid.wav', AskFast::QUESTION_TYPE_OPEN, $filename.'?function=receivebid&responder='.$session->getResponder());
        $askfast->finish();
    }
    
    function app_bidding_closed() {
        global $askfast;
        global $filename;
        
        $askfast->say('/audio/closed.wav', $filename.'?function=hangup');
        $askfast->finish();
    }
    
    function app_double_bid() {
        global $askfast;
        global $filename;
        
        $askfast->say('/audio/double.wav', $filename.'?function=hangup');
        $askfast->finish();
    }
    
    function app_thankyou() {
        global $askfast;
        global $filename;
        
        $askfast->say('/audio/thankyou.wav', $filename.'?function=hangup');
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
        case 'bid':        app_bid();        break;
        case 'receivebid':        app_receivebid();        break;
        default:        app_failure();
    }
?>
