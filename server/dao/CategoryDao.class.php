<?php
/**
 * desc:
 * Created by MasterFan on  2017/12/26 0026 14:12.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/server/db/DB.class.php";

class CategoryDao
{

    /**
     * 首页的热门分类
     */
    public function queryIndexHotCategory()
    {
        $db = DB::getInstance();
        $data = array();

        $sql = "SELECT `category_id`, `name` FROM `co_future`.`course_category` WHERE `is_hot` = 1 LIMIT 0, 4;";
        $categoryList = $db->queryAll($sql);
        foreach ($categoryList as $category) {
            $hotRecommend = array();

            $hotRecommend['name'] = $category['name'];

            $type_hot_sql = "SELECT `type_id`,`name` FROM `co_future`.`course_type` WHERE `category_id` = " . $category['category_id'] . " AND `is_hot`=1 LIMIT 0, 100;";
            $typeHotList = $db->queryAll($type_hot_sql);
            $hotRecommend['hot'] = $typeHotList;

            $type_recommend_sql = "SELECT `type_id`,`name` FROM `co_future`.`course_type` WHERE `category_id` = " . $category['category_id'] . " AND `is_recommend`=1 LIMIT 0, 100;";
            $typeRecommendList = $db->queryAll($type_recommend_sql);
            $hotRecommend['recommend'] = $typeRecommendList;

            array_push($data, $hotRecommend);
        }

        return $data;
    }

    /** ============================================以下为管理平台============================================   */

    /**
     * @param $type
     * @param $categoryId
     * @return 返回所有一级分类
     */
    function queryList($type, $categoryId)
    {
        $db = DB::getInstance();

        $sql = NULL;
        if ($type) {//1   type
            $sql = "SELECT `type_id`,`name`,`note`,`date`,`category_id`,`is_hot`, `is_recommend` FROM `co_future`.`course_type` WHERE `category_id`=" . $categoryId . ";";
        } else {   //     category
            $sql = "SELECT `category_id`,`name`,`note`,`date`,`is_hot`, `is_recommend` FROM `co_future`.`course_category` LIMIT 0, 1000;";
        }

        $result = $db->queryAll($sql);
        return $result;
    }

    /**
     * @param $categoryId
     * @return 返回分类下的类型
     */
    function queryTypeByCategoryId($categoryId)
    {
        $db = DB::getInstance();
        $sql = "SELECT `type_id`, `name`,`note`,`date`,`category_id`,`is_hot`, `is_recommend` FROM `co_future`.`course_type` WHERE category_id =" . $categoryId . ";";
        $data = $db->queryAll($sql);
        $db->close();
        return $data;
    }

    /**
     * 添加或更新分类/类型
     * @param $obj   分类/类型
     * @param $type  类型：0-分类， 1-类型
     * @return mixed
     */
    public function addOrUpdate($obj, $type)
    {
        $db = DB::getInstance();
        try {
            $db->beginTransaction();
            if ($type == 0) { //分类添加、修改
                if (!is_null($obj->category_id)) {//修改
                    $sql = "UPDATE `co_future`.`course_category` SET `name` = ?,`note` = ?,`date` = ? ,`is_hot` = ?, `is_recommend` = ? WHERE `category_id` = ?;";
                    $db->prepareExecute($sql, array($obj->name, $obj->note, $obj->date, $obj->isHot, $obj->isRecommend, $obj->category_id));
                    if ($db->getAffectRow() < 1) {
                        throw new PDOException("edit category error");
                    }
                } else {                         //添加
                    $sql = "INSERT INTO `co_future`.`course_category` (`name`,`note`,`date`,`is_hot`,`is_recommend`)VALUES(?,?,?,?,?);";
                    $db->prepareExecute($sql, array($obj->name, $obj->note, $obj->date, $obj->isHot, $obj->isRecommend));
                    if ($db->getAffectRow() < 1) {
                        throw new PDOException("add category error");
                    }
                }
            } else {          //类型添加、修改
                if (!is_null($obj->type_id)) {//修改
                    $sql = "UPDATE `co_future`.`course_type` SET `name` = ?, `note` = ?,`date` = ?,`category_id` = ?, `is_hot` = ?,`is_recommend` = ? WHERE `type_id` = ?;";
                    $db->prepareExecute($sql, array($obj->name, $obj->note, $obj->date, $obj->category_id, $obj->isHot, $obj->isRecommend, $obj->type_id));
                    if ($db->getAffectRow() < 1) {
                        throw new PDOException("edit type error");
                    }
                } else {                         //添加
                    $sql = "INSERT INTO `co_future`.`course_type` (`name`,`note`,`date`,`category_id`,`is_hot`,`is_recommend`) VALUES (?,?,?,?,?,?);";
                    $db->prepareExecute($sql, array($obj->name, $obj->note, $obj->date, $obj->category_id, $obj->isHot, $obj->isRecommend));
                    if ($db->getAffectRow() < 1) {
                        throw new PDOException("add type error");
                    }
                }
            }

            $db->commit();
            return false;//no error
        } catch (PDOException $e) {
            $db->rollback();
            return $e->getMessage();
        }
    }

    /**
     * 删除分类、类型
     * @param $id   删除的唯一ID
     * @param $type 类型：0-分类， 1-类型
     * @return mixed
     */
    public function deleteById($id, $type)
    {
        $db = DB::getInstance();
        if ($type == 0) { //分类

            $sql_query_sub = "SELECT COUNT(`type_id`) AS `count` FROM `co_future`.`course_type` WHERE `category_id`=" . $id . " LIMIT 0, 1000;";

            $result = $db->prepareExecute($sql_query_sub);
            if ($result['count'] == 0) {
                $sql = "DELETE FROM `co_future`.`course_category` WHERE `category_id` =" . $id . ";";
                return $db->execute($sql);
            } else {
                $sql_query_sub_about = "SELECT `type_id`,`name`,`note`,`date`,`category_id` FROM `co_future`.`course_type` WHERE `category_id`=" . $id . " LIMIT 0, 1000;";
                return $db->prepareExecuteAll($sql_query_sub_about);
            }

        } else {          //类型
            $sql = "DELETE FROM `co_future`.`course_type` WHERE `type_id` = " . $id . ";";
            return $db->execute($sql);
        }
    }
}