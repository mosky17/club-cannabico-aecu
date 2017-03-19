<?php

include(dirname(__FILE__)."/../../config.php");

class Mandrill {

    public static function SendDefault($text,$html,$subject,$to,$tags){

//        $uri = 'https://mandrillapp.com/api/1.0/messages/send.json';
//
//        $postString = array(
//            "key" => $GLOBALS['mandrill_api_key'],
//            "message" => array(
//                "html" => $html,
//                "text" => $text,
//                "subject" => $subject,
//                "from_email" => $GLOBALS['mandrill_reply_email'],
//                "from_name" => $GLOBALS['name'],
//                "to" => $to,
//                "headers" => array(
//                    "Reply-To" => $GLOBALS['mandrill_reply_email']),
//                "track_opens" => true,
//                "track_clicks" => false,
//                "async" => true,
//                "auto_text" => true,
//                "tags" => $tags),
//            "async" => false);

        $uri = 'http://email-sender.aecu.org.uy/send.php';

        $postData = array(
            "to" => $to,
            "from" => "=?utf-8?B?" . base64_encode("Club Cannábico El Piso") . "?= <ccelpiso@gmail.com>",
            "message" => $html,
            "subject" => $subject);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $result = curl_exec($ch);

        echo $result;
    }



    private static function _PrepareAttachmentsForSending($attachments){
        $keys = array_keys($attachments);
        for($i=0;$i<count($keys);$i++){
            if($attachments[$keys[$i]]['type'] == "text/plain" || $attachments[$keys[$i]]['type'] == "text/x-log"){
                $attachments[$keys[$i]]['content'] = base64_encode($attachments[$keys[$i]]['content']);
            }
        }
        return $attachments;
    }
}