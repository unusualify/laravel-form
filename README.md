# laravel-form

php artisan vendor:publish --provider="OoBook\LaravelForm\LaravelServiceProvider" --tag="migrations"

```
    @inputField('text', [
        'input_name' => 'name',
        'label' => 'Report Name',
        'model' => $item,
        'placeholder' => __('Name'),
        'class' => '',
        'props' => "
            required
            autofocus
            minlength=5
        ",
    ])
```
