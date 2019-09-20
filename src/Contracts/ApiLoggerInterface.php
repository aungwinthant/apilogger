<?php

namespace AWT\Contracts;

interface ApiLoggerInterface{

    /**
     * saving methods in favourite driver
     *
     * @param [type] $request
     * @param [type] $response
     * @return void
     */
    public function saveLogs($request,$response);
    /**
     * return logs to use in the frontend
     *
     * @return void
     */
    public function getLogs();
    /**
     * provide method to delete all the logs
     *
     * @return void
     */
    public function deleteLogs();

}