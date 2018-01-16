<?php
/**
 * desc: 基础服务类， 包括增、删、改、查
 * Created by MasterFan on  2017/12/27 0027 14:51.
 */
interface BaseService
{
    /**
     * 添加课程
     * @param $object
     * @return mixed
     */
    public function addOrUpdate($object);

    /**
     * 查询
     * @return mixed
     */
    public function queryAll();

    /**
     * 查询详情
     * @param $id
     * @return
     */
    public function detail($id);

    /**
     * 删除一条数据
     * @param $id
     * @return mixed
     */
    public function delete($id);
}