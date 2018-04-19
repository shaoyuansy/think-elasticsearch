<?php

namespace think\elasticsearch;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;

class Excute
{
    public static function search($esInstance, $query)
    {
        try {
            $resources = $esInstance->search($query);
            $hits = $resources['hits']['hits'];

            $result = array_map(function ($hit) {
                return array_merge($hit['_source'], ['id' => $hit['_id']]);
            }, $hits);

            return $result;
        } catch (\Exception $e) {
            if ($e instanceof Missing404Exception) {
                return [];
            }

            if ($e instanceof BadRequest400Exception) {
                return [];
            }
            throw $e;
        }
    }

    public static function get($esInstance, $query)
    {
        try {
            $resources = $esInstance->get($query);

            return $resources['_source'];
        } catch (\Exception $e) {
            if ($e instanceof Missing404Exception) {
                return null;
            }

            throw $e;
        }
    }

    public static function index($esInstance, $query)
    {
        try {
            $resources = $esInstance->index($query);

            return $resources['result'] === "created" ? 1 : 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function bulk($esInstance, $query, $action)
    {
        try {
            $resources = $esInstance->bulk($query);

            if($action === "add"){
                $success_total = array_filter($resources['items'], function ($item) {
                    return $item['index']['result'] === "created";
                });
                $count_success = count($success_total);
            }elseif($action === "delete"){
                $success_total = array_filter($resources['items'], function ($item) {
                    return $item['delete']['result'] === "deleted";
                });
                $count_success = count($success_total);
            }else{
                $count_success = 0;
            }
            
            return $count_success;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function update($esInstance, $query)
    {
        try {
            $resources = $esInstance->update($query);

            return $resources['result'] === "updated" ? 1 : 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function delete($esInstance, $query)
    {
        try {
            $resources = $esInstance->delete($query);

            return $resources['result'] === "deleted" ? 1 : 0;
        } catch (\Exception $e) {
            if ($e instanceof Missing404Exception) {
                return 0;
            }

            throw $e;
        }
    }
}
