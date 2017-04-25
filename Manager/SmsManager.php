<?php

namespace RValin\InfoBipBundle\Manager;

use infobip\api\client\SendMultipleSmsTextual;
use infobip\api\client\SendSingleTextualSms;
use infobip\api\configuration\BasicAuthConfiguration;
use infobip\api\model\sms\mt\send\textual\SMSMultiTextualRequest;
use RValin\InfoBipBundle\Object\Message;

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

    public function __construct($from, $user, $password)
    {
        $this->_from = $from;
        $this->_infobipUserName = $user;
        $this->_infobipUserPassword = $password;
    }

    private function getAuthConfiguration(){
        return new BasicAuthConfiguration($this->_infobipUserName, $this->_infobipUserPassword);
    }

    /**
     * Create new Message object
     */
    public function createMessage(){
        $message = new Message();
        $message->setFrom($this->_from);
    }

    /**
     * @param Message $message
     */
    public function sendMessage(Message $message) {
        $client = new SendSingleTextualSms($this->getAuthConfiguration());
        $client->execute($message->getInfobipMessage());
    }

    /**
     * @param array $messages
     * @throws \Exception
     */
    public function sendMessages(array $messages) {
        $messagesInfobip = [];
        foreach ($messages as $message) {
            if (!$message instanceof Message) {
                throw new \Exception('This object should be an instance of '.Message::class);
            }
            $messagesInfobip[] = $message->getInfobipMessage();
        }
        $messagesRequest = new SMSMultiTextualRequest();
        $messagesRequest->setMessages($messagesInfobip);

        $client = new SendMultipleSmsTextual($this->getAuthConfiguration());
        $client->execute($messagesRequest);
    }
}