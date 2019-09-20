<?php

namespace AWT;

use AWT\Contracts\ApiLoggerInterface;

class DBLogger extends AbstractLogger implements ApiLoggerInterface{

    /**
     * Model for saving logs
     *
     * @var [type]
     */
    protected $logger; 

    public function __construct(ApiLog $logger)
    {
        parent::__construct();
        $this->logger = $logger;
    }
    /**
     * return all models
     */
    public function getLogs()
    {
        return $this->logger->all();
    }
    /**
     * save logs in database
     */
    public function saveLogs($request,$response)
    {
        $data = $this->logData($request,$response);
        
        $this->logger->fill($data);

        $this->logger->save();
    }
    /**
     * delete all logs
     */
    public function deleteLogs()
    {
        $this->logger->truncate();
    }
}