<?php

namespace OoBook\LaravelForm\Models\Traits;

use Illuminate\Support\Str;

use Cartalyst\Tags\TaggableTrait;

trait HasTagKey
{
    use TaggableTrait;

    protected static function bootHasTagKey()
    {
        static::retrieved(function ($model) {
            $model->getCustomTags();
        });

        static::created(function ($model) {
            $model->afterSaveTagKeyAttribute();
        });

        static::updating(function ($model) {
            $model->beforeSaveTagKeyAttribute();
        });
    }

    protected function syncTagAttribute()
    {
        $values = $this->getFormInputValue($this->tagKey);

        $attributes = $this->attributesToArray();

        $this->setTags($values);

        $ids = $this->tags->map(function($item){
            return $item->id;
        })->implode(',');

        data_set($attributes, $this->tagKey, $ids);

        $attributes = $this->castRevert($attributes);

        $this->attributes = $attributes;

    }

    protected function beforeSaveTagKeyAttribute()
    {
        if(!$this->id)
            return;

        $this->syncTagAttribute();

    }

    protected function afterSaveTagKeyAttribute()
    {
        if(!$this->id)
            return;

        $this->syncTagAttribute();

        $attr = explode('.', $this->tagKey)[0];
        // dd($this);
        $ins = static::find($this->id);

        $ins->{$attr} = $this->{$attr};

        $ins->saveQuietly(['timestamps' => false]);
        // static::where('id', $this->id)->first()->updateQuietly([
        //     $attr => $this->isJsonCastable($attr) ? json_encode($this->{$attr}) : $this->{$attr}
        // ], ['timestamps' => false]);

    }

    protected function getCustomTags()
    {
        if(!$this->id)
            return;

        $attributes = $this->attributesToArray();

        $ids = $this->tags->map(function($item){
            return $item->name;
        })->implode(',');

        data_set($attributes, $this->tagKey, $ids);

        $attributes = $this->castRevert($attributes);

        $this->attributes = $attributes;
    }

    public function castRevert($attributes)
    {
        foreach($attributes as $key => $value){
            if (! is_null($value) && $this->isJsonCastable($key)) {
                $value = $this->castAttributeAsJson($key, $value);
                $attributes[$key] = $value;
            }
        }

        return $attributes;
    }

    public function jsonNotation($notation)
    {
        return Str::replace('.', '->', $notation);
    }

    public function hasTrait( $traitName, $autoloader = true )
    {
        $ret = class_uses( $this, $autoloader ) ;

        if( is_array( $ret ) )
        {
            $ret = array_search( $traitName, $ret ) !== false ;
        }
        return $ret ;
    }
}
