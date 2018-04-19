<?php

namespace think\elasticsearch;

use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use think\elasticsearch\Builder;
use think\elasticsearch\Excute;

class ES
{
    const BULK_ADD_ACTION = "add";

    const BULK_DELETE_ACTION = "delete";

    // ES Host
    protected $esHost = null;

    // ES Index
    protected $esIndex = null;

    // ES Type
    protected $esType = null;

    // ES Instance
    protected static $esInstance = null;
    
    /**
     * 创建Elasticsearch客户端
     * @access public
     * @return Client
     */
    public static function esInstance()
    {
        if (!self::$esInstance) {
            self::$esInstance = ClientBuilder::create()->setHosts((new static)->esHost)->build();
        }
        return self::$esInstance;
    }

    /**
     * 条件查询
     *
     * @param array         $filter 查询条件
     * @param integer       $from 开始位置
     * @param integer|null  $size 条数
     * @return array        $result 查询结果（无记录则为空数组）
     */
    public static function esGet(array $filter, $from = 0, $size = null)
    {
        $query = Builder::parseQuery(__FUNCTION__, (new static)->esIndex, (new static)->esType, $filter, $from, $size);
        $result = Excute::search(self::esInstance(), $query);
        return $result;
    }

    /**
     * 根据ID查询
     *
     * @param string        $id 记录ID
     * @return array|null   $result 查询结果（无记录则为null）
     */
    public static function esGetById(string $id)
    {
        $query = Builder::parseQuery(__FUNCTION__, (new static)->esIndex, (new static)->esType, $id);
        $result = Excute::get(self::esInstance(), $query);
        return $result;
    }

    /**
     * 插入一条数据
     *
     * @param array         $data 插入数据（若数据中存在“id”字段，插入后则会作为此数据的ID）
     * @param string|null   $id 设置ID。若数据$data中同时存在“id”字段，数据中“id”不生效。
     * @return integer      $result 返回写入成功条数
     */
    public static function esInsert(array $data, $id=null)
    {
        $query = Builder::parseQuery(__FUNCTION__, (new static)->esIndex, (new static)->esType, $data, $id);
        $result = Excute::index(self::esInstance(), $query);
        return $result;
    }

    /**
     * 批量插入数据
     *
     * @param array         $data_list 插入数据（若每条数据中存在“id”字段，插入后则会作为此数据的ID）
     * @return integer      $result 返回写入成功条数
     */
    public static function esInsertAll(array $data_list)
    {
        $query = Builder::parseQuery(__FUNCTION__, (new static)->esIndex, (new static)->esType, $data_list);
        $result = Excute::bulk(self::esInstance(), $query, self::BULK_ADD_ACTION);
        return $result;
    }

    /**
     * 修改记录
     *
     * @param array         $data 修改字段
     * @param string        $id 该记录ID
     * @return integer      $result 返回修改成功条数
     */
    public static function esUpdateById(array $data, string $id)
    {
        $query = Builder::parseQuery(__FUNCTION__, (new static)->esIndex, (new static)->esType, $data, $id);
        $result = Excute::update(self::esInstance(), $query);
        return $result;
    }

    /**
     * 删除一条记录
     *
     * @param string        $id 记录ID
     * @return integer      $result 返回删除成功条数
     */
    public static function esDeleteById(string $id)
    {
        $query = Builder::parseQuery(__FUNCTION__, (new static)->esIndex, (new static)->esType, $id);
        $result = Excute::delete(self::esInstance(), $query);
        return $result;
    }

    /**
     * 批量删除
     *
     * @param array         $ids 记录ID
     * @return integer      $result 返回删除成功条数
     */
    public static function esDeleteByIds(array $ids)
    {
        $query = Builder::parseQuery(__FUNCTION__, (new static)->esIndex, (new static)->esType, $ids);
        $result = Excute::bulk(self::esInstance(), $query, self::BULK_DELETE_ACTION);
        return $result;
    }
}
