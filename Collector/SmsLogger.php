<?php

namespace RValin\InfoBipBundle\Collector;

class SmsLogger {
    private $logs = array();

    /**
     * @param $log
     */
    public function addLog($log) {
        $this->logs[] = $log;
    }

    /**
     * @return array
     */
    public function getLogs(){
        return $this->logs;
    }
}