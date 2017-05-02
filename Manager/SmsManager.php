<?php

namespace RValin\InfoBipBundle\Manager;

use infobip\api\client\SendMultipleBinarySms;
use infobip\api\client\SendMultipleSmsTextual;
use infobip\api\client\SendSingleBinarySms;
use infobip\api\client\SendSingleTextualSms;
use infobip\api\configuration\BasicAuthConfiguration;
use infobip\api\model\sms\mt\send\binary\SMSBinaryRequest;
use infobip\api\model\sms\mt\send\binary\SMSMultiBinaryRequest;
use infobip\api\model\sms\mt\send\textual\SMSMultiTextualRequest;
use infobip\api\model\sms\mt\send\textual\SMSTextualRequest;
use RValin\InfoBipBundle\Collector\SmsLogger;
use RValin\InfoBipBundle\Object\Message;
use RValin\InfoBipBundle\Object\SmsLog;

class SmsManager {
    /**
     * @var
     */
    private $_from;

    /**
     * @var
     */
    private $_infobipUserName;

    /**
     * @var
     */
    private $_infobipUserPassword;

    /**
     * @var
     */
    private $_redirectNumber;

    /**
     * @var
     */
    private $_sendSms;

    /**
     * @var SmsLogger
     */
    private $_smsLogger;

    public function __construct(SmsLogger $logger, $from, $user, $password, $redirectNumber = null, $sendSms = true)
    {
        $this->_smsLogger = $logger;
        $this->_from = $from;
        $this->_infobipUserName = $user;
        $this->_infobipUserPassword = $password;
        $this->_redirectNumber = $redirectNumber;
        $this->_sendSms = $sendSms;
    }

    /**
     * @return BasicAuthConfiguration
     */
    private function getAuthConfiguration(){
        return new BasicAuthConfiguration($this->_infobipUserName, $this->_infobipUserPassword);
    }

    /**
     * Create new Message object
     * @param $message
     * @return mixed
     */
    public function checkMessageFrom($message){
        if ($message instanceof SMSTextualRequest) {
            if(empty($message->getFrom())) {
                $message->setFrom($this->_from);
            }
        }

        return $message;
    }

    /**
     * @param $message
     * @throws \Exception
     */
    public function sendMessage($message) {
        // check if binary or textual
        if($message instanceof SMSTextualRequest) {
            $client = new SendSingleTextualSms($this->getAuthConfiguration());
        } elseif($message instanceof SMSBinaryRequest) {
            $client = new SendSingleBinarySms($this->getAuthConfiguration());
        } else {
            throw new \Exception('Not a sms object');   
        }

        $this->executeSms($client, $message);
    }

    /**
     * @param array $messages
     * @throws \Exception
     */
    public function sendMessages(array $messages) {
        $isBinary = null;
        
        if (empty($messages)){
            throw new \Exception('No message');
        }

        foreach ($messages as $message) {
            if($message instanceof SMSTextualRequest) {
                $messageBinary = false;
            } elseif($message instanceof SMSBinaryRequest) {
                $messageBinary = true;
            } else {
                throw new \Exception('Not a sms object');
            }
            
            if (!null !== $isBinary && $isBinary != $messageBinary) {
                throw new \Exception('All SMS should have the same type (binary / textual)');
            }
            $isBinary = $messageBinary;
        }
        
        if($isBinary) {
            $messagesRequest = new SMSMultiBinaryRequest();
            $client = new SendMultipleBinarySms($this->getAuthConfiguration());
        } else {
            $messagesRequest = new SMSMultiTextualRequest();
            $client = new SendMultipleSmsTextual($this->getAuthConfiguration());
        }

        $this->executeSms($client, $messagesRequest);
    }

    private function executeSms($client, $request) {
        $messages = array();

        // add log
        if ($request instanceof SMSTextualRequest || $request instanceof SMSBinaryRequest) {
            $log = new SmsLog($request);
            $this->_smsLogger->addLog($log);
            $mess = new Message($request, $log);
            $messages[] = $mess;
        } elseif ($request instanceof SMSMultiBinaryRequest || $request instanceof SMSMultiTextualRequest) {
            $messages += $request->getMessages();
            foreach ($request->getMessages() as $message) {
                $log = new SmsLog($message);
                $this->_smsLogger->addLog($log);
                $mess = new Message($request, $log);
                $messages[] = $mess;
            }
        }

        // check if intercept
        if ($this->_redirectNumber) {
            foreach ($messages as $message) {
                $message->getLog()->setOriginalTo($request->getTo());
                $message->getLog()->setIntercept(true);
                $message->getMessage()->setTo(array($this->_redirectNumber));
            }
        }

        if (!$this->_sendSms) {
            return;
        }

        if(!$client instanceof SendMultipleBinarySms && !$client instanceof SendMultipleSmsTextual && !$client instanceof SendSingleTextualSms && !$client instanceof SendSingleBinarySms) {
            throw new \Exception('Not a client');
        }

        // update log
        foreach ($messages as $message) {
            $message->getLog()->setSend(true);
        }

        $client->execute($request);
    }
}