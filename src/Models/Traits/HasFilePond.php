<?php

namespace OoBook\LaravelForm\Models\Traits;

use OoBook\LaravelForm\Facades\FilePond;
use OoBook\LaravelForm\Services\FilePondManager;
use Illuminate\Database\Eloquent\Model;

trait HasFilePond
{

    public function __construct(array $attributes = [])
    {
        $this->setFilePondCacheAttributes();

        parent::__construct($attributes);
    }

    public static function bootHasFilePond(): void
    {
        self::creating(static function (Model $model) {
            $model->beforeSaveFilePondAttributes();
        });
        self::updating(static function (Model $model) {
            $model->beforeSaveFilePondAttributes();
        });
    }

    public function beforeSaveFilePondAttributes()
    {

        $attributes = $this->attributesToArray();

        foreach ($this->getFilePondAttributes() as $notation) {
            $data = data_get($attributes, $notation);

            if(is_array($data)){
                foreach($data as $j => $item){
                    $this->saveFilePond($attributes, $item, $notation, $j);

                    // $segments = explode('/', $item);
                    // $folder = end( $segments );
                    // $notation = str_replace('*', $j, $key);
                    // // data_fill($attributes, $notation, 'sgsa');
                    // $original_value = data_get($original_attributes, $notation ,'');
                    // if(!!$original_attributes[$key] && $original_attributes[$key] != $key){
                    //     $fpm->deleteFile($original_attributes[$key], $this->getTable());
                    // }
                    // data_set($attributes, $notation, $fpm->saveFile($folder, $this->getTable()));
                    // data_set($this->attributes, $notation, $fpm->saveFile($folder, $this->getTable()));

                }
            }else{
                $this->saveFilePond($attributes, $data, $notation);

                // $segments = explode('/', $data);
                // $folder = end( $segments );

                // data_set($attributes, $notation, $fpm->saveFile($folder, $this->getTable()));

                // if(!!$original_attributes[$key] && $original_attributes[$key] != $key){
                //     $fpm->deleteFile($original_attributes[$key], $this->getTable());
                // }
                // data_set($this->attributes, $key, $fpm->saveFile($folder, $this->getTable()));
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

    public function getFilePondAttributes()
    {
        return $this->filePondAttributes ?? [];
    }

    public function saveFilePond(&$attributes, $folder, $notation, $index = null)
    {
        $segments = explode('/', $folder);

        $id = end( $segments );

        if($index !== null)
            $notation = str_replace('*', $index, $notation);

        $original_value = data_get($this->getOriginal(), $notation , '');

        // if( $index !== null){
        //     dd($notation, $original_value, $id);
        // }
        if(!!$original_value && $original_value != $id){
            if(is_array($original_value))
                foreach($original_value as $val){
                    FilePond::deleteFile($val);
                }
            else
                FilePond::deleteFile($original_value);
        }

        data_set($attributes, $notation, FilePond::saveFile($id, $this->getTable()));
    }

    public function setFilePondCacheAttributes()
    {
        $fpm = new FilePondManager();

        foreach ($fpm->getCachedFolders($this->getTable(), $this->getFilePondAttributes()) as $key => $value) {
            if(is_array($value)){
                $this->setAttribute($key, $value);
            }else{
                $this->setAttribute($key, $value);
            }
        }
    }

    public function getEncodedFilePondImage($notation)
    {
        $value = data_get($this->attributesToArray(), $notation);

        return encodeImagePath(FilePond::getEncodedFile($value));
    }
}
