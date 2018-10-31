<?php
/**
 * Created by PhpStorm.
 * User: mohsen
 * Date: 10/31/18
 * Time: 4:15 AM
 */

namespace Khaliilii\Raygansms\Facade;


use Illuminate\Support\Facades\Facade;

class RayganSmsFacade extends Facade
{

    protected static function getFacadeAccessor()
        {
            return 'RayganSms';
        }
}