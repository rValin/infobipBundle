<?php
/**
 * creator: Romain Valin
 * Date: 25/04/17
 * Time: 10:28
 */

namespace RValin\InfoBipBundle\Object;


use infobip\api\model\sms\mt\send\textual\SMSTextualRequest;

class Message
{
    /**
     * Send of the message
     * @var string
     */
    private $_from;

    /**
     * @var array
     */
    private $_to = array();

    /**
     * sms text
     * @var string
     */
    private $_text;

    public function getInfobipMessage() {
        $requestBody = new SMSTextualRequest();
        $requestBody->setFrom($this->_from);
        $requestBody->setTo($this->_to);
        $requestBody->setText($this->_text);

        return $requestBody;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->_from;
    }

    /**
     * @param mixed $from
     * @return Message
     */
    public function setFrom($from)
    {
        $this->_from = $from;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->_to;
    }

    /**
     * @param mixed $to
     * @return Message
     */
    public function setTo($phoneNumbers)
    {
        // must be an array
        if (!is_array($phoneNumbers)) {
            $phoneNumbers = array($phoneNumbers);
        }
        $this->_to = array_unique($phoneNumbers);
        return $this;
    }

    /**
     * @param $phoneNumber
     * @return $this
     */
    public function addTo($phoneNumber){
        if (!in_array($phoneNumber, $this->_to)) {
            $this->_to[] = $phoneNumber;
        }

        return $this;
    }

    /**
     * @param $to
     * @return $this
     */
    public function removeTo($to){
        if(($key = array_search($to, $this->_to)) !== false) {
            unset($this->_to[$key]);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * @param mixed $text
     * @return Message
     */
    public function setText($text)
    {
        $this->_text = $text;
        return $this;
    }
}