<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martin
 * Date: 19/12/13
 * Time: 07:43 PM
 * To change this template use File | Settings | File Templates.
 */

class Mandrill {

    public static function SendDefault($text,$html,$subject,$to,$tags){

        $uri = 'https://mandrillapp.com/api/1.0/messages/send.json';

        $postString = array(
            "key" => "T6CP35RL9BM5FaAs37RnNw",
            "message" => array(
                "html" => $html,
                "text" => $text,
                "subject" => $subject,
                "from_email" => "pagos@csc1.aecu.org.uy",
                "from_name" => "Mi Club Social de Cannabis",
                "to" => $to,
                "headers" => array(
                    "Reply-To" => "pagos@csc1.aecu.org.uy"),
                "track_opens" => true,
                "track_clicks" => false,
                "async" => true,
                "auto_text" => true,
                "tags" => $tags),
            "async" => false);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postString));

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