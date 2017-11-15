<?php

declare(strict_types=1);

namespace MMoney\Plugins;

use Interop\Container\ContainerInterface;
use MMoney\Model\BillPay;
use MMoney\Model\BillReceive;
use MMoney\Model\CategoryCost;
use MMoney\Model\User;
use MMoney\Repository\CategoryCostRepository;
use MMoney\Repository\RepositoryFactory;
use MMoney\Repository\StatementRepository;
use MMoney\ServiceContainerInterface;

use Illuminate\Database\Capsule\Manager as Capsule;

class DataBasePlugin implements PluginInterface
{

    public function register(ServiceContainerInterface $container)
    {
        $capsule = new Capsule();
        $config = include __DIR__ . "/../../config/db.php";
        $capsule->addConnection($config["development"]);
        $capsule->bootEloquent();

        $container->add("repository.factory", new RepositoryFactory());

        $container->addLazy("category-costs.repository", function(){
            return new CategoryCostRepository();
        });

        $container->addLazy("users.repository", function(ContainerInterface $container){
            return $container->get("repository.factory")->factory(User::class);
        });

        $container->addLazy("bill-receives.repository", function(ContainerInterface $container){
            return $container->get("repository.factory")->factory(BillReceive::class);
        });

        $container->addLazy("bill-pays.repository", function(ContainerInterface $container){
            return $container->get("repository.factory")->factory(BillPay::class);
        });

        $container->addLazy("statements.repository", function(){
            return new StatementRepository();
        });

    }

}