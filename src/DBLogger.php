<?php

namespace AWT;

use AWT\Contracts\ApiLoggerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        return $this->logger->orderByDesc('created_at')->paginate(config('apiloger.per_page', 25));
    }
    /**
     * save logs in database
     */
    public function saveLogs(Request $request, Response|JsonResponse|RedirectResponse $response)
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
