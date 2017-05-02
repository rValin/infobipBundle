<?php
namespace RValin\InfoBipBundle\Collector;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class SmsCollector extends DataCollector
{
    private $logger;

    /**
     * SmsCollector constructor.
     * @param SmsLogger $logger
     */
    public function __construct(SmsLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Récupère les datas
     * @param Request $request
     * @param Response $response
     * @param \Exception|null $exception
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        if ($this->logger) {
            $this->data = array('sms' => $this->logger->getLogs());
        } else {
            $this->data = array('sms' => array());
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sms';
    }

    /**
     * Retourne le nombre d'image distinct utilisée
     * @return int
     */
    public function getCtCalls(){
        return count($this->data['sms']);
    }

    /**
     * Retourne les images
     * @return mixed
     */
    public function getSms()
    {
        return $this->data['sms'];
    }
}