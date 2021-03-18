<?php namespace EvolutionCMS\EvocmsCommerceCoupons;

use EvolutionCMS\EvocmsCommerceCoupons\Router\Router;
use EvolutionCMS\ServiceProvider;

class EvocmsCommerceCouponsServiceProvider extends ServiceProvider
{
    /**
     * Если указать пустую строку, то сниппеты и чанки будут иметь привычное нам именование
     * Допустим, файл test создаст чанк/сниппет с именем test
     * Если же указан namespace то файл test создаст чанк/сниппет с именем EvocmsCommerceCoupons#test
     * При этом поддерживаются файлы в подпапках. Т.е. файл test из папки subdir создаст элемент с именем subdir/test
     */
    protected $namespace = 'EvocmsCommerceCoupons';
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        evo()->singleton(Assets::class);
        evo()->singleton(Router::class);

        include __DIR__.'/../routes.php';


        $this->loadPluginsFrom(
            dirname(__DIR__) . '/assets/plugins/'
        );

        $this->app->registerModule(
            'EvocmsCommerceCoupons',
            dirname(__DIR__).'/assets/modules/module.php'
        );

        $this->publishes([__DIR__ . '/../public' => public_path('assets/modules/evocms-commerce-coupons/')]);
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');


        $this->loadViewsFrom(__DIR__.'/../views','CommerceCoupons');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'CommerceCoupons');



    }
}