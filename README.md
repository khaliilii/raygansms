# raygansms
## for laravel 5.6, 5.7 install
### composer require mohkhmk/raygansms
-----------------------------------------------
## for laravel 5.3 to 5.5 install
### step 1
### composer require mohkhmk/raygansms
### step 2
### service provider add to config/app.php (For Laravel: v5.3, v5.4)
##### Khaliilii\Raygansms\Providers\RaygansmsServiceProvider::class,
### step 3
### alias add to config/app.php (For Laravel: v5.3, v5.4)
##### 'RayganSmsFacade' => Khaliilii\Raygansms\Facade\RayganSmsFacade::class,
