<?php
/**
 * Created by PhpStorm.
 * User: mohsen
 * Date: 10/28/18
 * Time: 3:41 AM
 */
namespace Khaliilii\Raygansms\Providers;
use Illuminate\Support\ServiceProvider;
use Mohkhmk\Raygansms\Facade\RayganSms;

class RaygansmsServiceProvider extends ServiceProvider{
    public function boot()
    {
        //binding
        $this->app->bind('RayganSms',function(){
            return new RayganSms();
        });

    }

    public function register()
    {

    }
}