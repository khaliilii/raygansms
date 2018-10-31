<?php
/**
 * Created by PhpStorm.
 * User: mohsen khalili
 * Date: 10/21/18
 * Time: 9:51 PM
 */

namespace Khaliilii\Raygansms\Facade;
use Exception;
use SoapClient;

class RayganSms {


    public function __construct()
    {

    }
    /**
     * get Credit
     * recieve get credit account Raygansms.ir
     * return  {
     * "Code":0,
     * "Message":"عملیات با موفقیت انجام شد",
     * "Result":5292
     * }
     * example read return $result->Message;
     */
    public function getCredit()
    {
        $url = "http://smspanel.trez.ir/api/smsAPI/GetCredit";
        $username = env('RAYGANSMS_USERNAME');
        $password = env('RAYGANSMS_PASSWORD');

        $process = curl_init();
        curl_setopt($process,CURLOPT_URL,$url);
        curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, "");
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, true);
        $return = curl_exec($process);
        print_r($return);
        $httpcode = curl_getinfo($process, CURLINFO_HTTP_CODE);
        if($httpcode==401)
        {
            throw new exception("نام کاربری یا کلمه عبور صحیح نمی باشد");
        }
        curl_close($process);
        $decoded = json_decode($return);

        return $decoded;
    }


    /**
     *   get price message send
     * return
     * {
        "Fa_Price": 129
        "En_Price": 295
        }
     */

    public function getPrices()
    {
        $url = "http://smspanel.trez.ir/api/smsAPI/GetPrices";
        $username = env('RAYGANSMS_USERNAME');
        $password = env('RAYGANSMS_PASSWORD');
        $process = curl_init();
        curl_setopt($process,CURLOPT_URL,$url);
        curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, "");
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, true);
        $return = curl_exec($process);
        $httpcode = curl_getinfo($process, CURLINFO_HTTP_CODE);
        if($httpcode==401)
        {
            throw new exception("نام کاربری یا کلمه عبور صحیح نمی باشد");
        }
        curl_close($process);

        $decoded = json_decode($return);
        if(isset($decoded->Result))
        {
            return $decoded->Result;
        }
        else
        {
            return $decoded->Message;
        }
    }


    /**
     *   Send Message
     * Send_Message($username,$password,$number,$message,explode(",",$mobile));
     * number is your number on raygansms.ir
     * if your multi mobile send on arra use top way for explode on mobiles number
     * return function your status send
     *
    */

    public function sendMessage($sendNumber,$message,$phones)
    {
        $url = "http://smspanel.trez.ir/api/smsAPI/SendMessage";
        $username = env('RAYGANSMS_USERNAME');
        $password = env('RAYGANSMS_PASSWORD');
        $post_data = json_encode(array(
            'PhoneNumber' => $sendNumber,
            'Message' => $message,
            'Mobiles' => $phones,
            'UserGroupID' => uniqid(),
            'SendDateInTimeStamp' => time(),
        ));

        $process = curl_init();
        curl_setopt($process,CURLOPT_URL,$url);
        curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($process, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($process, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'));

        $return = curl_exec($process);
        $httpcode = curl_getinfo($process, CURLINFO_HTTP_CODE);
        print_r($post_data);
        if($httpcode==401)
        {
            throw new exception("نام کاربری یا کلمه عبور صحیح نمی باشد");
        }
        curl_close($process);
        $decoded = json_decode($return);

        print_r($decoded);
        return $decoded;
    }



    /**
     *   show message status
     */

    public function getStatusMessage($number,$mobile,$message_id_list)
    {
        $client = new soapclient('http://sms.trez.ir/XmlForSMS.asmx?WSDL');
        $username = env('RAYGANSMS_USERNAME');
        $password = env('RAYGANSMS_PASSWORD');
        $action='status';
        $type='0';
        $status='';
        $usergroupid= $message_id_list;

        $xmlreq='<?xml version="1.0" encoding="UTF-8"?>
                  <xmlrequest>
                  
                  <username>'.$username.'</username>
                  <password>'.$password.'</password>
                  <number>'.$number.'</number>
                  <action>'.$action.'</action>
                  <type>'.$type.'</type>
                  <usergroupid>'.$usergroupid.'</usergroupid>
                  <body status="1">
                  <recipient mobile="'.$mobile.'">"'.$status.'"</recipient>
                  </body>
                  </xmlrequest>';

        $xmlres=$client->getxml(array('xmlString'=>$xmlreq));
        $xml=simplexml_load_string($xmlres->getxmlResult);
        $getstatus=$xml->body->recipient;
        return $getstatus;
    }


    /**
     *   showWhiteList
     */
    public function showWhiteList($mobiles_list)
    {
        $url = "http://smspanel.trez.ir/api/smsAPI/ShowWhiteList";
        $username = env('RAYGANSMS_USERNAME');
        $password = env('RAYGANSMS_PASSWORD');
        $post_data = json_encode(array(
            'MobilesList' => $mobiles_list
        ));
        $process = curl_init();
        curl_setopt($process,CURLOPT_URL,$url);
        curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($process, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($process, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'));

        $return = curl_exec($process);
        $httpcode = curl_getinfo($process, CURLINFO_HTTP_CODE);
        if($httpcode==401)
        {
            throw new exception("نام کاربری یا کلمه عبور صحیح نمی باشد");
        }
        curl_close($process);
        $decoded = json_decode($return);
        return $decoded;
    }


    /**
     *   recieve Message
     */
    public function receiveMessages($phone_number,$start_date,$end_date,$page)
    {
        $url = "http://smspanel.trez.ir/api/smsAPI/ReceiveMessages";
        $username = env('RAYGANSMS_USERNAME');
        $password = env('RAYGANSMS_PASSWORD');
        $post_data = json_encode(array(
            'PhoneNumber' => $phone_number,
            'StartDate'=>strtotime($start_date),
            'EndDate'=>strtotime($end_date),
            'Page' => $page
        ));
        $process = curl_init();
        curl_setopt($process,CURLOPT_URL,$url);
        curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($process, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($process, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'));

        $return = curl_exec($process);
        $httpcode = curl_getinfo($process, CURLINFO_HTTP_CODE);
        if($httpcode==401)
        {
            throw new exception("نام کاربری یا کلمه عبور صحیح نمی باشد");
        }
        curl_close($process);
        $decoded = json_decode($return);
        return $decoded;
    }


    /**
     *   send Corresponding Message
     */
    public function sendCorrespondingMessage($phone_number,$recipientsmessage)
    {
        $url = "http://smspanel.trez.ir/api/smsAPI/SendCorrespondingMessage";
        $username = env('RAYGANSMS_USERNAME');
        $password = env('RAYGANSMS_PASSWORD');
        $post_data = json_encode(array(
            'PhoneNumber' => $phone_number,
            'RecipientsMessage' => $recipientsmessage,
            'UserGroupID' => uniqid(),
        ));

        $process = curl_init();
        curl_setopt($process,CURLOPT_URL,$url);
        curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($process, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($process, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'));

        $return = curl_exec($process);
        $httpcode = curl_getinfo($process, CURLINFO_HTTP_CODE);
        if($httpcode==401)
        {
            throw new exception("نام کاربری یا کلمه عبور صحیح نمی باشد");
        }
        curl_close($process);
        $decoded = json_decode($return);
        return $decoded;
    }


    /**
     *   corresponding Message Status
     */

    public function correspondingMessageStatus($message_id_list)
    {
        $url = "http://smspanel.trez.ir/api/smsAPI/CorrespondingMessageStatus";
        $username = env('RAYGANSMS_USERNAME');
        $password = env('RAYGANSMS_PASSWORD');
        $post_data = json_encode(array(
            'messageId' => $message_id_list
        ));
        $process = curl_init();
        curl_setopt($process,CURLOPT_URL,$url);
        curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($process, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($process, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'));

        $return = curl_exec($process);
        $httpcode = curl_getinfo($process, CURLINFO_HTTP_CODE);
        if($httpcode==401)
        {
            throw new exception("نام کاربری یا کلمه عبور صحیح نمی باشد");
        }
        curl_close($process);
        $decoded = json_decode($return);
        return $decoded;
    }



}
?>