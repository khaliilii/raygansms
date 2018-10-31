<?php
/**
 * Created by PhpStorm.
 * User: mohsen khalili
 * Date: 10/21/18
 * Time: 9:51 PM
 */

namespace Khaliilii\Raygansms\Facade;
use Exception;
use HttpRequest;
use Illuminate\Foundation\Testing\HttpException;
use SoapClient;


class RayganSms {

    /**
     * RayganSms constructor.
     */
    public function __construct()
    {

    }


    /**
     * get Credit
     * recieve get credit account Raygansms.ir
     * how use:
     * getCredit();
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
        //print_r($return);
        $httpcode = curl_getinfo($process, CURLINFO_HTTP_CODE);
        curl_close($process);
        if($httpcode==401)
        {
            throw new exception("نام کاربری یا کلمه عبور صحیح نمی باشد");
        }
        $decoded = json_decode($return);
        return $decoded;
    }


    /**
     *   get price message send
     * how use:
     * getPrices();
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
        curl_close($process);
        if($httpcode==401)
        {
            throw new exception("نام کاربری یا کلمه عبور صحیح نمی باشد");
        }
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
     * SendMessage($username,$password,$number,$message,explode(",",$mobile));
     * number is your number on raygansms.ir
     * if your multi mobile send on arra use top way for explode on mobiles number
     * return function your status send
     * return {
     * "Code": 0
     * "Message": "عملیات با موفقیت انجام شد"
     * "Result": "c0613e77-69b8-43f4-a1fd-59e50da41dd6"
     * }
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
        curl_close($process);
        if($httpcode==401)
        {
            throw new exception("نام کاربری یا کلمه عبور صحیح نمی باشد");
        }
        $decoded = json_decode($return);
        return $decoded;
    }


    /**
     *   show message status
     * use methode: RayganSmsFacade::getStatusMessage(
     * 'sendNumber',
     * 'phoneNumber',
     * 'MessageId');
     * return SimpleXMLElement {
     * return [
     * "mobile" => "9358487574"
     * ]
     * +0: "1"
     * }
     */
    public function getStatusMessage($sendNumber,$phone,$messageId)
    {
        $client = new SoapClient('http://sms.trez.ir/XmlForSMS.asmx?WSDL');
        $username = env('RAYGANSMS_USERNAME');
        $password = env('RAYGANSMS_PASSWORD');
        $action='status';
        $type='0';
        $status='';
        $usergroupid= $messageId;
        $xmlreq='<?xml version="1.0" encoding="UTF-8"?>
                  <xmlrequest>
                  <username>'.$username.'</username>
                  <password>'.$password.'</password>
                  <number>'.$sendNumber.'</number>
                  <action>'.$action.'</action>
                  <type>'.$type.'</type>
                  <usergroupid>'.$usergroupid.'</usergroupid>
                  <body status="1">
                  <recipient mobile="'.$phone.'">"'.$status.'"</recipient>
                  </body>
                  </xmlrequest>';
        $xmlres=$client->getxml(array('xmlString'=>$xmlreq));
        $xml=simplexml_load_string($xmlres->getxmlResult);
        $getstatus=$xml->body->recipient;
        return $getstatus;
    }


    /**
     *   showWhiteList
     * how use:
     * RayganSmsFacade::showWhiteList(explode(',','phone');
     * how return
     * return {
     * "Code": 0
     * "Message": "عملیات با موفقیت انجام شد"
     * "Result": array:1 [
     * 0 => 9392280806
     * ]
     * }
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
        curl_close($process);
        if($httpcode==401)
        {
            throw new exception("نام کاربری یا کلمه عبور صحیح نمی باشد");
        }

        $decoded = json_decode($return);
        return $decoded;
    }


    /**
     *   recieve Message
     * start page from 1
     * how use:
     * RayganSmsFacade::receiveMessages(
     * 'phone',
     * '2018-06-06 21:26:30',
     * '2018-11-01 20:59:29',
     * 1);
     *****************
     * return {
     * "Code": 0
     * "Message": "عملیات با موفقیت انجام شد"
     * "Result": {
     * "Page": 1
     * "TotalPage": 1
     * "ReceivedMsgs": []
     * }
     * }
     */
    public function receiveMessages($phoneNnumber,$startDate,$endDate,$page)
    {
        $url = "http://smspanel.trez.ir/api/smsAPI/ReceiveMessages";
        $username = env('RAYGANSMS_USERNAME');
        $password = env('RAYGANSMS_PASSWORD');
        $post_data = json_encode(array(
            'PhoneNumber' => $phoneNnumber,
            'StartDate'=>strtotime($startDate),
            'EndDate'=>strtotime($endDate),
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
        curl_close($process);
        if($httpcode==401)
        {
            throw new exception("نام کاربری یا کلمه عبور صحیح نمی باشد");
        }
        $decoded = json_decode($return);
        return $decoded;
    }


    /**
     *   send Corresponding Message
     */
    public function sendCorrespondingMessage($phoneNumber,$recipientsMessage)
    {
        $url = "http://smspanel.trez.ir/api/smsAPI/SendCorrespondingMessage";
        $username = env('RAYGANSMS_USERNAME');
        $password = env('RAYGANSMS_PASSWORD');
        $post_data = json_encode(array(
            'PhoneNumber' => $phoneNumber,
            'RecipientsMessage' => $recipientsMessage,
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
        curl_close($process);
        if($httpcode==401)
        {
            throw new exception("نام کاربری یا کلمه عبور صحیح نمی باشد");
        }

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
        curl_close($process);
        if($httpcode==401)
        {
            throw new exception("نام کاربری یا کلمه عبور صحیح نمی باشد");
        }
        $decoded = json_decode($return);
        return $decoded;
    }


    /**
     * verification send code
     * how use:
     * verifyMessageSend($phoneNumber,$code);
     * return {true} or return {false}
     */
    public function verifyMessageSend($phoneNumber,$code)
    {
        $username = env('RAYGANSMS_USERNAME');
        $password = env('RAYGANSMS_PASSWORD');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://raygansms.com/SendMessageWithCode.ashx?UserName={$username}&Password={$password}&Mobile={$phoneNumber}&Message={$code}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "Authorization: key=",
                "Content-Type: ",
                "Postman-Token: 7c73065e-3b98-4b0c-8fe3-802aacc220af",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
            }
        else {
            return $response;
        }
    }
}
?>