
<?php

$access_token = 'EAAFUz3HsNTEBACQcoC5nzuLMKxDYfaAzdnbEX3pThnbzQZAjQSDDzzoZC7FLGmk0ZAO3wm6a90MiDVZB3a81zrqaFakTBmK2ZCxsySoQTZA7ZBeCBemeWcV5hFtLDKLdY5SN3ZBVNvxli0g28zCidUf82Jine21D76vKrlCAuNthqAZDZD';

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
    
    // Get recipe list.
    $recipe = array(
        'Padthai',
        'KanaMooKrob',
        'Koytiew',
        'KoytiewNamTok',
        'TomyumKung',
        'FriedBuffaloSkin',
        'TomLonglegGhost',
    );

    // Random recipe.
    $prefer = array_rand($recipe);

    // Send recipe back.
    $url = 'https://graph.facebook.com/v2.6/me/messages?access_token='. $access_token;

    /*initialize curl*/
    $ch = curl_init($url);
    
    /*prepare response*/
    $resp     = array(
        'messaging_type' => 'RESPONSE',
        'recipient' => array(
            'id' => $sender
        ),
        'message' => array(
            'text' => 'Recommend recipe for today is '.$recipe[$prefer],
        ),
    );
    $jsonData = json_encode($resp);

    /* curl setting to send a json post data */
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $result = curl_exec($ch); // user will get the message
} 
