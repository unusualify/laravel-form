<?php

namespace Unusualify\LaravelForm\Models\Traits;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Carbon\Carbon;

trait HasTZFormat
{
    public function getCreatedAttribute($value)
    {
        return $this->timezoneFormat($this->created_at);
    }

    public function getUpdatedAttribute($value)
    {
        return $this->timezoneFormat($this->updated_at);
    }

    public static function timezoneFormat(Carbon $carbon, $timezone = 'Europe/Istanbul')
    {
        return $carbon->setTimezone($timezone)->format(self::$tzFormat ?? 'd M D, Y H:i');
    }

}
