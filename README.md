[![Latest Stable Version](https://poser.pugx.org/khaliilii/raygansms/v/stable)](https://packagist.org/packages/khaliilii/raygansms)
[![License](https://poser.pugx.org/khaliilii/raygansms/license)](https://packagist.org/packages/khaliilii/raygansms)
[![Total Downloads](https://poser.pugx.org/khaliilii/raygansms/downloads)](https://packagist.org/packages/khaliilii/raygansms)
[![Latest Unstable Version](https://poser.pugx.org/khaliilii/raygansms/v/unstable)](https://packagist.org/packages/khaliilii/raygansms)

# how install raygansms

## for laravel 5.6, 5.7 install
### step 1
### composer require mohkhmk/raygansms
### step 2
### add username and password to .env file
### RAYGANSMS_USERNAME=******
### RAYGANSMS_PASSWORD=******
-----------------------------------------------
## for laravel 5.3 to 5.5 install
### step 1
### composer require mohkhmk/raygansms
### step 2
### service provider add to config/app.php (For Laravel: v5.3, v5.4)
##### Khaliilii\Raygansms\Providers\RaygansmsServiceProvider::class,
### step 3
### aliases add to config/app.php (For Laravel: v5.3, v5.4)
##### 'RayganSmsFacade' => Khaliilii\Raygansms\Facade\RayganSmsFacade::class,
### step 4
### add username and password to .env file
### RAYGANSMS_USERNAME=******
### RAYGANSMS_PASSWORD=******
----------------------------------------------------------
# how use class


### get Credit

#### recieve get credit account Raygansms.ir
#### how use:
#### getCredit();
#### return  {
#### "Code":0,
#### "Message":"عملیات با موفقیت انجام شد",
#### "Result":5292
#### }
#### example read return $result->Message;
######*****************************************
### get price message send

#### how use:
#### getPrices();
#### return
#### {
#### "Fa_Price": 129
#### "En_Price": 295
#### }
######*****************************************
### Send Message

#### how use:
#### SendMessage($username,$password,$number,$message,explode(",",$mobile));
#### return {
#### "Code": 0
#### "Message": "عملیات با موفقیت انجام شد"
#### "Result": "c0613e77-69b8-43f4-a1fd-59e50da41dd6"
#### }
######*****************************************
###  show message status

#### use methode: RayganSmsFacade::getStatusMessage(
#### 'sendNumber',
#### 'phoneNumber',
#### 'MessageId');
#### return SimpleXMLElement {
#### return [
#### "mobile" => "9358487574"
#### ]
#### +0: "1"
#### }
######*****************************************
###  showWhiteList

#### how use:
#### RayganSmsFacade::showWhiteList(explode(',','phone');
#### how return
#### return {
#### "Code": 0
#### "Message": "عملیات با موفقیت انجام شد"
#### "Result": array:1 [
#### 0 => 9392280806
#### ]
#### }
######*****************************************
### recieve Message
#### start page from 1
#### how use:
#### RayganSmsFacade::receiveMessages(
#### 'phone',
#### '2018-06-06 21:26:30',
#### '2018-11-01 20:59:29',
#### 1);
#### ***************
#### return {
#### "Code": 0
#### "Message": "عملیات با موفقیت انجام شد"
#### "Result": {
#### "Page": 1
#### "TotalPage": 1
#### "ReceivedMsgs": []
#### }
#### }
######*****************************************
### verification send code
#### how use:
#### verifyMessageSend($phoneNumber,$code);
#### return {true} or return {false}
   