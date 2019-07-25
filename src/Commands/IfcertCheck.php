<?php

namespace Sureyee\LaravelIfcert\Commands;


use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Log;
use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Models\RequestLog;
use Sureyee\LaravelIfcert\Requests\CheckRequest;

class IfcertCheck extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifcert:check
    {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '应急中心数据上报';

    /**
     * @var Client
     */
    protected $client;

    protected $request;

    protected $data;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->client = app()->make(Client::class);
    }


    public function handle()
    {
        RequestLog::unChecked()->get()->each(function (RequestLog $log) {
            $request = new CheckRequest($log, CheckRequest::REQUEST_TYPE_BATCH_MESSAGE);

            $response = $this->client->send($request);

            $result = $response->getResult();

            if (empty($result)) {
                Log::critical($response->getMessage());
            } else {
                $log->checked_message = $response->getResult()[0]->errorMsg;
                $log->checked_message === 'success' ? $log->setChecked() : null;
                $log->save();
            }
        });
    }
}