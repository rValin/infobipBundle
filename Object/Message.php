<?php

namespace RValin\InfoBipBundle\Object;

class Message{
    private $_message;
    private $_log;

    public function __construct($message, $log)
    {
        $this->_message = $message;
        $this->_log = $log;
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
     * @return Message
     */
    public function setMessage($message)
    {
        $this->_message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLog()
    {
        return $this->_log;
    }

    /**
     * @param mixed $log
     * @return Message
     */
    public function setLog($log)
    {
        $this->_log = $log;
        return $this;
    }


}