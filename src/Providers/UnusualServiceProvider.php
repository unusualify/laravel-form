<?php

namespace OoBook\LaravelForm\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Lang;
use View;
class UnusualServiceProvider extends ServiceProvider
{

    protected $providers = [
        RouteServiceProvider::class,
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        // dd('here');
        $this->loadMigrationsFrom(
            __DIR__ . '/../src/Database/Migrations'
        );
        // $this->loadViewsFrom(__DIR__ . '/views', 'unusual_form');
      
        $this->bootViews();

        $this->extendBlade();
        $validation = json_encode(Lang::get('validation'));
        view()->share('validation', $validation);

        

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->registerHelpers();

        $this->macros();

        $this->registerProviders();

    }

    /**
     * Register views.
     *
     * @return void
     */
    public function bootViews()
    {
        $sourcePathBlade = __DIR__ .  '/../resources/views';
        $sourcePathJS = __DIR__ .  '/../resources/js';
        $this->loadViewsFrom( $sourcePathBlade, 'unusual_form');
        $this->publishes([$sourcePathBlade => resource_path('views/vendor/unusual_form')], 'views');
        $this->publishes([$sourcePathJS => public_path('vendor/unusual_form/js')], 'js');

    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }


    /**
     * {@inheritdoc}
     */
    private function registerHelpers()
    {
        foreach (glob( __DIR__ . '/../Helpers/*.php') as $file) {
            require_once $file;
        }
    }

    /**
     * {@inheritdoc}
     */
    private function macros()
    {

    }

    /**
     * Resolve and include a given view expression in the project, Twill internals or a package.
     *
     * @param string $view
     * @param string $expression
     */
    private function includeView($view, $expression): string
    {
        // dd($view, $expression);
        [$name] = str_getcsv($expression, ',', '\'');


        if (preg_match('/::/', $name)) {
            // if there's a namespace separator, we'll assume it's a package
            [$namespace, $name] = preg_split('/::/', $name);
            $partialNamespace = "$namespace::admin.";
        } else {
            $partialNamespace = view()->exists('admin.' . $view . $name) ? 'admin.' : 'unusual_form::';
        }

        $view = $partialNamespace . $view . $name;

        $expression = explode(',', $expression);
        array_shift($expression);
        $expression = '(' . implode(',', $expression) . ')';
        if ($expression === '()') {
            $expression = '([])';
        }

        // dd(
        //     $expression,
        //     // View::make($view, $expression),
        //     // "\$__env->make('{$view}', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->with{$expression}->render();"
        // );

        return "<?php echo \$__env->make('{$view}', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->with{$expression}->render(); ?>";
    }

    /**
     * Defines the package additional Blade Directives.
     */
    private function extendBlade(): void
    {
        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

        $blade->directive('dumpData', function ($data) {
            return sprintf(
                "<?php (new Symfony\Component\VarDumper\VarDumper)->dump(%s); exit; ?>",
                null != $data ? $data : 'get_defined_vars()'
            );
        });

        $blade->directive('inputField', function ($expression) {
            return $this->includeView('inputs._', $expression);
        });

        $blade->directive('unusualForm', function ($expression) {
            return $this->includeView('layouts._', "'form'," . $expression);
        });

    }


}
