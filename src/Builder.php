<?php

namespace think\elasticsearch;

class Builder
{
    public static function parseQuery(...$data)
    {
        $function_name = $data[0];
        $index = $data[1];
        $type = $data[2];

        switch ($function_name) {
            case "esGet":
                $query = [
                    'index' =>  $index,
                    'type'  =>  $type,
                    'from'  =>  $data[4],
                    'body'  =>  $data[3] 
                ];
                if ($data[5]) {
                    $query['size'] = $data[5];
                }
            break;

            case "esGetById":
                $query = [
                    'index' =>  $index,
                    'type'  =>  $type,
                    'id'    =>  $data[3]
                ];
            break;

            case "esInsert":
                $query = [
                    'index' =>  $index,
                    'type'  =>  $type,
                    'body'  =>  $data[3]
                ];

                if (isset($query['body']['id'])) {
                    $query['id'] = $query['body']['id'];
                    unset($query['body']['id']);
                }
                
                if ($data[4]) {
                    $query['id'] = $data[4];
                }
            break;

            case "esInsertAll":
                $add_query_arr = [];
                foreach ($data[3] as $var) {
                    $add_query_arr[] = ['index' => ["_id" => isset($var['id']) ? $var['id'] : null]];
                    unset($var['id']);
                    $add_query_arr[] = $var;
                }

                $query = [
                    'index' =>  $index,
                    'type'  =>  $type,
                    'body'  =>  $add_query_arr
                ];
            break;

            case "esUpdateById":
                $query = [
                    'index' =>  $index,
                    'type'  =>  $type,
                    'id'    =>  $data[4],
                    'body'  =>  [
                        'doc'   =>  $data[3]
                    ]
                ];
            break;

            case "esDeleteById":
                $query = [
                    'index' =>  $index,
                    'type'  =>  $type,
                    'id'    =>  $data[3]
                ];
            break;

            case "esDeleteByIds":
                $del_query_arr = [];
                foreach ($data[3] as $var) {
                    $del_query_arr[] = ['delete' => ["_id" => $var]];
                }

                $query = [
                    'index' =>  $index,
                    'type'  =>  $type,
                    'body'  =>  $del_query_arr
                ];
            break;

            default:
                return ;
            break;
        }

        return $query;
    }
}
