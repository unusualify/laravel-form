<?php

namespace OoBook\LaravelForm\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;

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

        $this->loadMigrationsFrom(
            __DIR__ . '/../src/Database/Migrations'
        );

        $this->bootViews();

        $this->extendBlade();

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
        $sourcePath = __DIR__ .  '/../Resources/views';

        $this->loadViewsFrom( [$sourcePath], 'unusual_form');

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
        [$name] = str_getcsv($expression, ',', "'");

        if (preg_match('#::#', $name)) {
            // if there's a namespace separator, we'll assume it's a package
            [$namespace, $name] = preg_split('#::#', $name);
            $partialNamespace = "$namespace::admin.";
        } else {
            $partialNamespace = view()->exists('unusual_form.' . $view . $name) ? 'unusual_form.' : 'unusual_form::';
        }

        $view = $partialNamespace . $view . $name;

        $expression = explode(',', $expression);
        array_shift($expression);
        // dd(
        //     $view,
        //     $expression,
        //     $name,
        //     $partialNamespace,
        //     Blade::getClassComponentNamespaces()
        // );
        if (class_exists(Blade::getClassComponentNamespaces()['unusual_form'] . '\\' . Str::studly($name))) {
            $expression = implode(',', $expression);
            if ($expression === '') {
                $expression = '[]';
            }

            $expression = str_replace("'", "\\'", $expression);

            // Fix dash variables that we know.
            $expression = str_replace('toolbar-options', 'toolbarOptions', $expression);

            $php = '<?php' . PHP_EOL;
            $php .= "\$data = eval('return $expression;');";
            $php .= '$fieldAttributes = "";';
            $php .= 'foreach(array_keys($data) as $attribute) {';
            $php .= '  $fieldAttributes .= " :$attribute=\'$" . $attribute . "\'";';
            $php .= '}' . PHP_EOL;
            $php .= 'if ($renderForBlocks ?? false) {';
            $php .= '  $fieldAttributes .= " :render-for-blocks=\'true\'";';
            $php .= '}';
            $php .= 'if ($renderForModal ?? false) {';
            $php .= '  $fieldAttributes .= " :render-for-modal=\'true\'";';
            $php .= '}';
            $php .= '$name = "' . $name . '";';
            $php .= 'echo Blade::render("<x-twill::$name $fieldAttributes />", $data); ?>';

            return $php;
        }

        // Legacy behaviour.
        // @TODO: Not sure if we should keep this.
        $expression = '(' . implode(',', $expression) . ')';
        if ($expression === '()') {
            $expression = '([])';
        }

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

        $blade->directive('formField', function ($expression) {
            return $this->includeView('inputs.', $expression);
        });

        // $blade->component('twill::partials.form.utils._fieldset', 'formFieldset');
        // $blade->component('twill::partials.form.utils._columns', 'formColumns');
        // $blade->component('twill::partials.form.utils._collapsed_fields', 'formCollapsedFields');
        // $blade->component('twill::partials.form.utils._connected_fields', 'formConnectedFields');
        // $blade->component('twill::partials.form.utils._inline_checkboxes', 'formInlineCheckboxes');

        // $blade->component('twill::partials.form.utils._fieldset', 'twill::formFieldset');
        // $blade->component('twill::partials.form.utils._columns', 'twill::formColumns');
        // $blade->component('twill::partials.form.utils._collapsed_fields', 'twill::formCollapsedFields');
        // $blade->component('twill::partials.form.utils._connected_fields', 'twill::formConnectedFields');
        // $blade->component('twill::partials.form.utils._inline_checkboxes', 'twill::formInlineCheckboxes');

        // $blade->component('twill::partials.form.utils._field_rows', 'twill::fieldRows');

        // if (method_exists($blade, 'aliasComponent')) {
        //     $blade->aliasComponent('twill::partials.form.utils._fieldset', 'formFieldset');
        //     $blade->aliasComponent('twill::partials.form.utils._columns', 'formColumns');
        //     $blade->aliasComponent('twill::partials.form.utils._collapsed_fields', 'formCollapsedFields');
        //     $blade->aliasComponent('twill::partials.form.utils._connected_fields', 'formConnectedFields');
        //     $blade->aliasComponent('twill::partials.form.utils._inline_checkboxes', 'formInlineCheckboxes');
        // }
    }


}
