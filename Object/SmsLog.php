<?php

namespace RValin\InfoBipBundle\Object;

class SmsLog
{
    private $_message;

    private $_send;

    private $_originalTo;

    private $_intercept = false;

    /**
     * SmsLog constructor.
     * @param $message
     */
    public function __construct($message)
    {
        $this->_message = $message;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @param mixed $message
     * @return SmsLog
     */
    public function setMessage($message)
    {
        $this->_message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSend()
    {
        return $this->_send;
    }

    /**
     * @param mixed $send
     * @return SmsLog
     */
    public function setSend($send)
    {
        $this->_send = $send;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOriginalTo()
    {
        return $this->_originalTo;
    }

    /**
     * @param mixed $originalTo
     * @return SmsLog
     */
    public function setOriginalTo($originalTo)
    {
        $this->_originalTo = $originalTo;
        return $this;
    }

    /**
     * @return bool
     */
    public function isIntercept()
    {
        return $this->_intercept;
    }

    /**
     * @param bool $intercept
     * @return SmsLog
     */
    public function setIntercept($intercept)
    {
        $this->_intercept = $intercept;
        return $this;
    }
}