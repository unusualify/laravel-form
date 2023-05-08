<?php

namespace OoBook\LaravelForm\Models\Traits;

use Illuminate\Support\Str;

trait HasGridTable
{
    protected static $gridDefaultActionColumn = [
        'type' => 'control',
        'deleteButton' => true,
        'editButton' => true,
    ];

    protected static $gridActionColumn = [];

    public static function bootHasGridTable()
    {
        if( auth()->user() && !auth()->user()->hasRole('user') ){
            static::$gridActionColumn = [
                'deleteButton' => false
            ];
        }
    }

    public static function getGridTableName()
    {
        return Str::plural(
            Str::snake(
                (new \ReflectionClass(static::class))->getShortName()
            )
        );
    }

    public static function getGridTableHeadlineName()
    {
        return Str::headline(
            (new \ReflectionClass(static::class))->getShortName()
        );
    }

    public static function getGridTablePluralHeadlineName()
    {
        return Str::headline(
            static::getGridTableName()
        );
    }

    public static function getGridTableData($columns = ['*'], $orderBy='desc', $take = -1)
    {
        $table_name = static::getGridTableName();
        $columns = array_merge(
            ['id', 'created_at', 'updated_at'],
            (isset(static::$gridTableColumns) ? array_keys(static::$gridTableColumns) : [])
        );

        $collection = static::query()->orderBy('id',$orderBy)->take($take)->get(
            is_array($columns) ? $columns : func_get_args()
        );

        $data = [
            'headline' => static::getGridTableHeadlineName(),
            'plural_headline' => static::getGridTablePluralHeadlineName(),
            'route_name' => Str::slug(static::getGridTablePluralHeadlineName()),
            'main' => $table_name,
            'data' => [
                $table_name => array(),
            ],
            'keys' => array_merge(
                [
                    'id' => [
                        'type' => 'text',
                        'css' => 'd-none',
                        'filter' => false,
                        'editing' => false,
                    ],
                ],
                (static::$gridTableColumns ?? []),
                [
                    'created_at' => [
                        'type' => 'text',
                        'title' => 'Created At',
                        'filter' => true,
                        'editing' => false,

                    ],
                    'updated_at' => [
                        'type' => 'text',
                        'title' => 'Updated At',
                        'filter' => true,
                        'editing' => false,

                    ],
                    'control' => isset(static::$gridActionColumn) ? array_merge(static::$gridDefaultActionColumn, static::$gridActionColumn) : static::$gridDefaultActionColumn,
                ]
            )
        ];
        foreach($collection as $item){
            $newItem = $item->toArray();
            $newItem = array_combine(
                array_keys($newItem),
                array_map(function($key) use($item){
                    // dd($item);
                    if(preg_match('/created_at|updated_at/', $key)){
                        return static::timezoneFormat($item->{$key});
                    }elseif(preg_match('/price/', $key)){
                        return $item->price." {$item->currency->symbol}";
                    }
                    return $item->{$key};
                }, array_keys($newItem))
            );

            array_push(
                $data['data'][$table_name],
                self::mergeGridTableData($item, $newItem)
            );
        }

        // dd($data, $collection);
        return $data;
    }

    public function mergeGridTableData($item, $newItem)
    {
        return $newItem;

        array_merge(
            // $payment->toArray(),
            array_filter($newItem,function($key) use ($newItem, $item){
                // dd($arr, $key);
                return $key !== 'currency_id';
            }, ARRAY_FILTER_USE_KEY),
            ['currency' => $item->currency->name]
        );
    }

}
