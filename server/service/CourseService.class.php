<?php
/**
 * desc:
 * Created by MasterFan on  2017/12/27 0027 16:00.
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/server/service/BaseService.php';
interface CourseService extends BaseService
{
    /**
     * 分页查询
     * @param $typeId 类型ID
     * @param $index  页码
     * @return mixed
     */
    public function queryByPage($typeId, $index);
}