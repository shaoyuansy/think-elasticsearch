# 根据Elasticsearch-PHP [6.0] API 进行二次封装

> 目前根据开发时的实际业务，封装了部分方法，简化查询，会持续更新

> 以下类库都在`\\think\\elasticsearch`命名空间下

## 说明
> 需要继承 \\think\\elasticsearch\\ES 类，覆盖es配置

```
如:

use think\elasticsearch\ES;

class Esdb extends ES {

    protected $esHost = ['192.168.2.11'];

    protected $esIndex = 'think';

    protected $esType = 'user';

}
```

## esGet
> 条件查询

```
$filter = [
    'query' =>  [
        "bool"  =>  [
            'must'  =>  [
                'term'=>['email.keyword'=>'test@think.com']
            ]
        ]
    ]
];

$result = Esdb::esGet($filter);
```
## esGetById
> 根据ID查询

```
$id = "VdOa3GIBv0F8YUCs1PVZ";

$result = Esdb::esGetById($id);
```
## esInsert
> 插入一条数据

```
$id = null;

$data = [
    "id"       =>  "VdOa3GIBv0F8YUCs1PVZ"
    "content"  =>  "test",
];

$result = Esdb::esInsert($data, $id);
```
## esInsertAll
> 批量插入数据

```
$data_list = [
    [
        "id"       =>  "VdOa3GIBv0F8YUCs1PVZ",
        "content"  =>  "test",
    ],
    [
        "id"       =>  "VdOa3GIBv0F8YUCs1PVX",
        "content"  =>  "test",
    ]
];

$result = Esdb::esInsertAll($data_list);
```
## esUpdateById
> 修改记录

```
$data = [
    "content"=>"think"
];

$id = 'VdOa3GIBv0F8YUCs1PVZ';

$result = Esdb::esUpdateById($data, $id);
```
## esDeleteById
> 删除一条记录

```
$id = 'VNOa3GIBv0F8YUCs1PVZ';

$result = Esdb::esDeleteById($id);
```
## esDeleteByIds
> 批量删除

```
$ids = ['gNPW3GIBv0F8YUCsbPUZ',"VdOa3GIBv0F8YUCs1PVX"];

$result = Esdb::esDeleteByIds($id);
```
