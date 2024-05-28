<?php

namespace Unusualify\LaravelForm\Models\Traits;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Carbon\Carbon;

trait HasSpreadSheet
{
    public static function bootHasSpreadSheet(): void
    {

        self::retrieved(static function(Model $model){
            $model->afterRetrieveSpreadSheetAttributes();
        });
        self::creating(static function (Model $model) {
            $model->beforeSaveSpreadSheetAttributes();
        });
        self::updating(static function (Model $model) {
            $model->beforeSaveSpreadSheetAttributes();
        });
    }

    public function beforeSaveSpreadSheetAttributes()
    {
        $attributes = $this->attributesToArray();

        foreach ($this->getSpreadSheetAttributes() as $notation) {
            $data = data_get($attributes, $notation);

            if($data == null){
                $data = '[]';
            }

            data_set($attributes, $notation, json_decode($data, false));
        }

        foreach($attributes as $key => $value){
            if (! is_null($value) && $this->isJsonCastable($key)) {
                $value = $this->castAttributeAsJson($key, $value);
                $attributes[$key] = $value;
            }
        }

        $this->attributes = $attributes;
        // dd($this->attributes);
    }

    public function afterRetrieveSpreadSheetAttributes()
    {

        $attributes = $this->attributesToArray();

        foreach ($this->getSpreadSheetAttributes() as $notation) {
            $data = data_get($attributes, $notation);

            if($data == null){
                data_set($attributes, $notation, json_decode('[]', false));
            }
        }

        foreach($attributes as $key => $value){
            if (! is_null($value) && $this->isJsonCastable($key)) {
                $value = $this->castAttributeAsJson($key, $value);
                $attributes[$key] = $value;
            }
        }

        $this->attributes = $attributes;
    }

    public function getSpreadSheetAttributes()
    {
        return $this->spreadSheetAttributes ?? [];
    }
}
