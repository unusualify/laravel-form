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

```
    @unusualForm([
        'inputs' => [
            [
                'type' => 'text',
                'input_name' => 'name',
                'label' => 'Social Media Name',
                'model' => $item,
                'placeholder' => __('Name'),
                'class' => '',
                'props' => 'required autofocus',
            ],
            [
                'type' => 'filepond',
                'input_name' => 'logo',
                'label' => 'Social Media Logo',
                'model' => $item,
                'placeholder' => __('Logo'),
                'class' => '',
                'props' => 'required autofocus',
            ]
        ]
    ])
```
