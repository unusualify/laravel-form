
<?php

use Illuminate\Database\Eloquent\Model;


if (!function_exists('arrayExport')) {

    /**
     *
     * @param $expression
     * @param $return type
     *
     * @return boolean
     */
    function arrayExport($expression, $return=FALSE){
        if (!is_array($expression)) return var_export($expression, $return);

        $export = var_export($expression, TRUE);

        $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);

        $array = preg_split("/\r\n|\n|\r/", $export);
        $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [NULL, ']$1', ' => ['], $array);

        $export = join(PHP_EOL, array_filter(["["] + $array));

        if ((bool)$return) return $export; else echo $export;
    }
}

if (!function_exists('phpArrayFileContent')) {

    function phpArrayFileContent($expression){

        $export = arrayExport($expression, true);

        return "<?php

return {$export};

        ";
    }
}

if (!function_exists('getModelInput')) {

    function getModelInput(Model $model, $name, $type = null)
    {
        // if( isset($model->id)){

        // }

        if(!isset($model->{$name}))
            return '';

        // $model->getType($name);
        if(!$type )
            return $model->{$name};

        switch ($type) {
            case 'date':
                if(!isset($model->{$name})){
                    return '';
                }
                return $model->{$name}->format('Y-m-d');
                break;

            default:
                return '';
                break;
        }
    }
}
