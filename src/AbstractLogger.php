<?php

namespace AWT;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

abstract class AbstractLogger{

    protected $logs = [];

    protected $models = [];

    public function __construct()
    {
        $this->boot();
    }
    /**
     * starting method just for cleaning code
     *
     * @return void
     */
    public function boot(){
        Event::listen('eloquent.*', function ($event, $models) {
            if (Str::contains($event, 'eloquent.retrieved')) {
                foreach (array_filter($models) as $model) {
                    $class = get_class($model);
                    $this->models[$class] = ($this->models[$class] ?? 0) + 1;
                }
            }
        });
    }
    /**
     * logs into associative array
     *
     * @param  $request
     * @param  $response
     * @return array
     */
    public function logData($request,$response){
        $currentRouteAction = Route::currentRouteAction();

        // Initialiaze controller and action variable before use them
        $controller = "";
        $action = "";

        /*
         * Some routes will not contain the `@` symbole (e.g. closures, or routes using a single action controller).
         */
        if ($currentRouteAction) {
            if (strpos($currentRouteAction, '@') !== false) {
                list($controller, $action) = explode('@', $currentRouteAction);
            } else {
                // If we get a string, just use that.
                if (is_string($currentRouteAction)) {
                    list ($controller, $action) = ["", $currentRouteAction];
                } else {
                    // Otherwise force it to be some type of string using `json_encode`.
                    list ($controller, $action) = ["", (string)json_encode($currentRouteAction)];
                }
            }
        }

        $endTime = microtime(true);

        $implode_models = $this->models;

        array_walk($implode_models, function(&$value, $key) {
            $value = "{$key} ({$value})";
        });

        $models = implode(', ',$implode_models);
        $this->logs['created_at'] = Carbon::now();
        $this->logs['method'] = $request->method();
        $this->logs['url'] = $request->path();
        $this->logs['payload'] = $this->payload($request);
        $this->logs['response'] = $response->status();
        $this->logs['duration'] = number_format($endTime - LARAVEL_START, 3);
        $this->logs['controller'] = $controller;
        $this->logs['action'] = $action;
        $this->logs['models'] = $models;
        $this->logs['ip'] = $request->ip();

        return $this->logs;
    }
    /**
     * Helper method for mapping array into models
     *
     * @param array $data
     * @return ApiLog
     */
    public function mapArrayToModel(array $data){
        $model = new ApiLog();
        $model->created_at = Carbon::make($data[0]);
        $model->method = $data[1];
        $model->url = $data[2];
        $model->payload = $data[3];
        $model->response = $data[4];
        $model->duration = $data[5];
        $model->controller = $data[6];
        $model->action = $data[7];
        $model->models = $data[8];
        $model->ip = $data[9];
        return $model;
    }

    /**
     * Formats the request payload for logging
     *
     * @param $request
     * @return string
     */
    protected function payload($request)
    {
        $allFields = $request->all();

        foreach (config('apilog.dont_log', []) as $key) {
            if (array_key_exists($key, $allFields)) {
                unset($allFields[$key]);
            }
        }

        return json_encode($allFields);
    }
}