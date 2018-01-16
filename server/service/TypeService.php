<?php

/**
 * desc:子类型服务接口
 * Created by MasterFan on  2017/12/26 0026 14:12.
 */
interface TypeService {

    /**
     * 返回所有一级分类
     * @param $type  类型：0-分类， 1-类型
     * @return CategoryList
     */
    public function queryList($type);

    /**
     * 添加或更新分类/类型
     * @param $obj   分类/类型
     * @param $type  类型：0-分类， 1-类型
     */
    public function addOrUpdate($obj, $type);

    /**
     * 删除分类、类型
     * @param $id   删除的唯一ID
     * @param $type 类型：0-分类， 1-类型
     */
    public function deleteById($id, $type);
}