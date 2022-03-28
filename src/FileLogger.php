<?php

namespace AWT;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use AWT\Contracts\ApiLoggerInterface;

class FileLogger extends AbstractLogger implements ApiLoggerInterface
{

    /**
     * file path to save the logs
     */
    protected $path;

    public function __construct()
    {
        parent::__construct();
        $this->path = storage_path('logs/apilogs');
    }

    /**
     * read files from log directory
     *
     * @return array
     */
    public function getLogs()
    {
        //check if the directory exists
        if (File::isDirectory($this->path)) {
            //scann the directory
            $files = glob($this->path."/*.*");

            $contentCollection = collect();

            //loop each files
            foreach ($files as $file) {
                if (!File::isDirectory($file)) {
                    $contentCollection->add((object) unserialize(file_get_contents($file)));
                }
            }
            return collect($contentCollection)->sortByDesc('created_at');
        } else {
            return [];
        }
    }

    /**
     * write logs to file
     *
     * @param Request                                $request
     * @param Response|JsonResponse|RedirectResponse $response
     *
     * @return void
     */
    public function saveLogs(Request $request, Response|JsonResponse|RedirectResponse $response)
    {
        $data = $this->logData($request, $response);

        $filename = $this->getLogFilename();

        $contents = serialize($data);

        if(!File::isDirectory($this->path))
            File::makeDirectory($this->path, 0777, true, true);

        File::append(($this->path.DIRECTORY_SEPARATOR.$filename), $contents.PHP_EOL);

    }

    /**
     * get log file if defined in constants
     *
     * @return string
     */
    public function getLogFilename()
    {
        // original default filename
        $filename = 'apilogger-'.date('d-m-Y') . '.log';

        $configFilename = config('apilog.filename');
        preg_match('/{(.*?)}/', $configFilename, $matches, PREG_OFFSET_CAPTURE);
        if (sizeof($matches) > 0) {
            $filename = str_replace($matches[0][0], date("{$matches[1][0]}"), $configFilename);
        }

        if(strpos($filename, '[uuid]') !== false) {
            $filename = str_replace('[uuid]', uniqid(), $filename);
        } else {
            $extension = File::extension($filename);
            $filename = substr($filename, 0, -strlen($extension)).uniqid().".$extension";
        }
        return $filename;
    }

    /**
     * delete all api log  files
     *
     * @return void
     */
    public function deleteLogs()
    {
        if (is_dir($this->path)) {
            File::deleteDirectory($this->path);
        }

    }

}
