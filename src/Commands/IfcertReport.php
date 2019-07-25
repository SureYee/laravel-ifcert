<?php

namespace Sureyee\LaravelIfcert\Commands;


use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;
use Sureyee\LaravelIfcert\Exceptions\CertException;
use Sureyee\LaravelIfcert\Models\Creditor;
use Sureyee\LaravelIfcert\Models\Particular;
use Sureyee\LaravelIfcert\Models\Product;
use Sureyee\LaravelIfcert\Models\ProductConfig;
use Sureyee\LaravelIfcert\Models\RepayPlan;
use Sureyee\LaravelIfcert\Models\ScatterInvest;
use Sureyee\LaravelIfcert\Models\Status;
use Sureyee\LaravelIfcert\Models\Transact;
use Sureyee\LaravelIfcert\Models\TransferProject;
use Sureyee\LaravelIfcert\Models\TransferStatus;
use Sureyee\LaravelIfcert\Models\Undertake;
use Sureyee\LaravelIfcert\Models\UserInfo;
use Sureyee\LaravelIfcert\Requests\BatchRequest;

class IfcertReport extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifcert:report
    {--force}
    {--inf=*}';

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

    /**
     * @return int
     * @throws CertException
     */
    public function handle()
    {
        if (!$this->confirmToProceed()) return 1;

        foreach ($this->createModelFromInf() as $model) {
            $this->reportModelData($model);
        }
    }

    /**
     * @param IfcertModel $model
     * @throws CertException
     */
    protected function reportModelData(IfcertModel $model)
    {
        $count = $model::unReport()->count();

        $excepts = config('ifcert.excepts', []);

        if ($count === 0 || in_array($model::getInfType(), $excepts)) return;

        $model::unReport()->get()->chunk(config('ifcert.batch_count', 1000))->each(function ($data) use ($model) {

            $request = new BatchRequest($data, $model::getInfType(), $model::getTransformer());

            $this->client->send($request);
        });
    }

    protected function createModelFromInf()
    {
        $inf = $this->option('inf');

        if (empty($inf)) {
            return [
                app(ProductConfig::class),
                app(Product::class),
                app(UserInfo::class),
                app(Transact::class),
                app(TransferStatus::class),
                app(RepayPlan::class),
                app(Undertake::class),
                app(TransferProject::class),
                app(Creditor::class),
                app(ScatterInvest::class),
                app(Particular::class),
                app(Status::class),
            ];
        }

        return array_map(function ($inf) {
            switch ($inf) {
                case Request::INF_TYPE_LEND_PRODUCT_CONFIG:
                    return app(ProductConfig::class);
                case Request::INF_TYPE_LEND_PRODUCT:
                    return app(Product::class);
                case Request::INF_TYPE_USER_INFO:
                    return app(UserInfo::class);
                case Request::INF_TYPE_TRANSACT:
                    return app(Transact::class);
                case Request::INF_TYPE_TRANSFER_STATUS:
                    return app(TransferStatus::class);
                case Request::INF_TYPE_REPAY_PLAN:
                    return app(RepayPlan::class);
                case Request::INF_TYPE_UNDER_TAKE:
                    return app(Undertake::class);
                case Request::INF_TYPE_TRANSFER_PROJECT:
                    return app(TransferProject::class);
                case Request::INF_TYPE_CREDITOR:
                    return app(Creditor::class);
                case  Request::INF_TYPE_SCATTER_INVEST:
                    return app(ScatterInvest::class);
                case Request::INF_TYPE_LEND_PARTICULARS:
                    return app(Particular::class);
                case Request::INF_TYPE_STATUS:
                    return app(Status::class);
                default:
                    throw new CertException('undefind inftype');
            }
        }, $inf);
    }
}