<?php
/**
 * desc:
 * Created by MasterFan on  2017/12/27 0027 14:59.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/server/db/DB.class.php";

class CourseDao
{

    /**
     * 【前端用】
     * 查询课程列表
     * @param $typeId 类型ID
     * @return mixed
     */
    public function queryCourseList($typeId) {
        $db = DB::getInstance();
        $sql = "SELECT `course_id`, `title` FROM `co_future`.`course` WHERE `type_id`=". $typeId ." LIMIT 0, 1000;";
        return $db->queryAll($sql);
    }

    /**
     * 添加文章
     * @param $course 文章内容
     * @return mixed
     */
    public function saveOrUpdate($course)
    {
        $db = DB::getInstance();
        try {
            $db->beginTransaction();
            if (is_null($course->course_id)) {//add
                $sql = "INSERT INTO `co_future`.`course` (`title`,`sub_title`,`date`,`content`,`user_id`,`type_id`,`views`,`help_to_me`)VALUES(?,?,?,?,?,?,?,?);";
                $db->prepareExecute("INSERT INTO `co_future`.`course` (`title`,`sub_title`,`date`,`content`,`user_id`,`type_id`,`views`,`help_to_me`)VALUES(?,?,?,?,?,?,?,?);", array($course->title, $course->sub_title, $course->date, $course->content, $course->user_id, $course->type_id, $course->views, $course->help_to_me));
                if ($db->getAffectRow() < 1) {
                    throw new PDOException("add course error");
                }
            } else {                        //update
                $sql = "UPDATE `co_future`.`course` SET `title` =?,`sub_title` = ?,`date` = ?,`content` = ?,`user_id` = ?, `type_id`= ?, `views`=?, `help_to_me`= ? WHERE `course_id` = ?;";
                $db->prepareExecute("UPDATE `co_future`.`course` SET `title` =?,`sub_title` = ?,`date` = ?,`content` = ?,`user_id` = ?, `type_id`= ?, `views`=?, `help_to_me`= ? WHERE `course_id` = ?;", array($course->title, $course->sub_title, $course->date, $course->content, $course->user_id, $course->type_id, $course->views, $course->help_to_me, $course->course_id));
                if ($db->getAffectRow() < 1) {
                    throw new PDOException("edit course error");
                }
            }

            $db->commit();
            return false;
        } catch (PDOException $e) {
            $db->rollback();
            return $e->getMessage();
        }
    }

    /**
     * 删除信息
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM `co_future`.`course` WHERE `course_id` = " . $id . ";";
        return $db->execute($sql);
    }

    /**
     * 查询详情
     * @param $id
     * @return mixed
     */
    public function queryDetail($id)
    {
        $db = DB::getInstance();
        $sql = "SELECT `content` FROM `co_future`.`course` WHERE `course_id`=" . $id . ";"; //查询总条数
        return $db->query($sql);;
    }

    /**
     * 分页查询
     * @param $typeId 类型ID
     * @param $index  页码
     * @return mixed
     */
    public function queryByPage($typeId, $index)
    {
        $db = DB::getInstance();

        //分页查询SQL;
        $sql = "SELECT `course_id`, `title`, `sub_title`, `date`,`user_id`,`type_id`,`views`,`help_to_me` FROM `co_future`.`course` WHERE `type_id` = " . $typeId . " LIMIT " . (($index - 1) * PAGE_SIZE) . ", " . PAGE_SIZE . ";";
        $result = $db->queryAll($sql);
        return $result;
    }

    /**
     * 通过类型ID查询，当前类型下的数量
     * @param $typeId
     */
    public function queryCount($typeId)
    {
        $db = DB::getInstance();
        $sql = "SELECT count(`course_id`) AS `count` FROM `co_future`.`course` WHERE `type_id`=" . $typeId . ";"; //查询总条数
        return $db->query($sql);
    }

    /**
     * 分类数据和子类型一起查询
     */
    public function categoryAndType()
    {
        $db = DB::getInstance();
        $data = array();

        $sql = "SELECT `category_id`,`name` FROM `co_future`.`course_category` LIMIT 0, 1000;";//查询所有的分类
        $categoryList = $db->queryAll($sql);

        foreach ($categoryList as $category) {
            $type_sql = "SELECT `type_id`,`name`,`note`,`date`,`category_id` FROM `co_future`.`course_type` WHERE `category_id` = " . $category['category_id'] . " LIMIT 0, 1000;";
            $typeList = $db->queryAll($type_sql);
            $data[$category['name']] = $typeList;
        }
        return $data;
    }
}