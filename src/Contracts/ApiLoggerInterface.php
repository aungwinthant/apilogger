<?php

namespace AWT\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface ApiLoggerInterface{
    /**
     * saving methods in favourite driver
     *
     * @param Request   $request
     * @param Response $response
     *
     * @return void
     */
    public function saveLogs(Request $request, Response|JsonResponse|RedirectResponse $response);
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
