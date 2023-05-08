<?php

namespace OoBook\LaravelForm\Facades;

use Illuminate\Support\Facades\Facade;

class FilePond extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'FilePond';
    }
}
