<?php

namespace Unusualify\LaravelForm\Models\Traits;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Carbon\Carbon;

trait HasForm
{

    public static function bootHasForm(): void
    {
        self::creating(static function (Model $model) {
            $model->beforeSaveFormRepeaterArrays();
        });
        self::updating(static function (Model $model) {
            $model->beforeSaveFormRepeaterArrays();
        });
    }

    public function getFormInputValue($name)
    {
        $dot_notation = $this->getDotNotation($name);
        // dd($this->getAttributes(), $dot_notation);
        return data_get($this->attributesToArray(), $dot_notation);
    }


    public function beforeSaveFormRepeaterArrays()
    {
        $formRepeaterAttributes = $this->getFormRepeaterAttributes();

        if( count($formRepeaterAttributes) > 0){

            $attributes = $this->attributesToArray();

            foreach ($formRepeaterAttributes as $notation) {
                $data = data_get($attributes, $notation);

                data_set($attributes, $notation, $data);
            }

            foreach($attributes as $key => $value){
                if (! is_null($value) && $this->isJsonCastable($key)) {
                    $value = $this->castAttributeAsJson($key, $value);
                    $attributes[$key] = $value;
                }
            }

            $this->attributes = $attributes;
        }
    }

    protected function getDotNotation($name)
    {
        $name = str_replace('[', '.', $name); // Replace [ with .
        $name = str_replace(']', '', $name); // Remove ]

        return $name;
    }

    public function getFormRepeaterAttributes()
    {
        return $this->formRepeaterAttributes ?? [];
    }
}
