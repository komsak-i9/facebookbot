
<?php

$access_token = 'EAAFUz3HsNTEBAFZAeDiZA8ywOtEUZB52ZBLYziFtXjZCZAUZBmqlUtuemBnbbkN3M25NBKutMZCDzngjD0Uxz530fFmIiJHnFDHZBen9KRGvWZBjnti5awKG6a1g4XfaO8ZCKVfG2Cer5nu3W0uvubzk4wYfpsZCc9QfLGe2tiVZA5JGY6QZDZD';

/* validate verify token needed for setting up web hook */ 

if (isset($_GET['hub_verify_token'])) { 
    if ($_GET['hub_verify_token'] === $access_token) {
        echo $_GET['hub_challenge'];
        return;
    } else {
        echo 'Invalid Verify Token';
        return;
    }
}

/* receive and send messages */
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['entry'][0]['messaging'][0]['sender']['id'])) {
    
    $sender = $input['entry'][0]['messaging'][0]['sender']['id']; //sender facebook id 
    
    $url = 'https://graph.facebook.com/v2.6/me/messages?access_token='. $access_token;

    /*initialize curl*/
    $ch = curl_init($url);
    
    /*prepare response*/
    $message = 'What sup man';
        
    $resp = array(
      'recipient' => array(
        'id' => $sender
      ),
      'message' => array(
        'text' => $message
      )
    );
    $jsonData = json_encode($resp);

    /* curl setting to send a json post data */
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $result = curl_exec($ch); // user will get the message
} 
