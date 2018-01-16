<?php
/**
 * desc:课程控制器
 * Created by MasterFan on  2017/12/27 0027 14:47.
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/server/service/CourseService.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/server/controller/BaseController.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/server/dao/CourseDao.class.php';
class CourseController extends BaseController implements CourseService
{
    /// 返回JSON
    private $returnJson = array('type'=> 'course');

    /**
     * 读取课程列表
     * @param $typeId
     */
    public function queryCourseList($typeId) {
        $service = new CourseDao();
        $result = $service->queryCourseList($typeId);
        if($result) {
            $this->returnJson['code'] = '0';
            $this->returnJson['message'] = 'success';
            $this->returnJson['data'] = $result;
        }else {
            $this->returnJson['code'] = '-1';
            $this->returnJson['message'] = 'no data';
        }
        $this->exitOutput($this->returnJson);
    }

    /**
     * 添加课程
     * @param $course
     * @return mixed
     */
    public function addOrUpdate($course)
    {
        $service = new CourseDao();
        $hasError = $service->saveOrUpdate($course);
        if (!$hasError) {
            $this->returnJson['code'] = '0';
            $this->returnJson['message'] = 'success';
        }else {
            $this->returnJson['code'] = '-1';
            $this->returnJson['message'] = $hasError;
        }

        $this->exitOutput($this->returnJson);
    }

    /**
     * 查询
     * @return mixed
     */
    public function queryAll()
    {

    }

    /**
     * 查询详情
     * @param $id
     * @return mixed
     */
    public function detail($id)
    {
        $service = new CourseDao();
        $result = $service->queryDetail($id);
        if ($result) {
            $this->returnJson['code'] = '0';
            $this->returnJson['message'] = 'success';
            $this->returnJson['data'] = $result;
        }else {
            $this->returnJson['code'] = '-1';
            $this->returnJson['message'] = "get detail course error [course_id=". $id ."]";
        }
        $this->exitOutput($this->returnJson);
    }

    /**
     * 删除一条数据
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $service = new CourseDao();
        $result = $service->delete($id);
        if ($result) {
            $this->returnJson['code'] = '0';
            $this->returnJson['message'] = 'success';
        }else {
            $this->returnJson['code'] = '-1';
            $this->returnJson['message'] = "delete course error [course_id=". $id ."]";
        }
        $this->exitOutput($this->returnJson);
    }

    /**
     * 分页查询
     * @param $typeId 类型ID
     * @param $index  页码
     * @return mixed
     */
    public function queryByPage($typeId, $index)
    {
        $service = new CourseDao();
        $result = $service->queryByPage($typeId, $index);
        if ($result) {
            $this->returnJson['code'] = '0';
            $this->returnJson['message'] = 'success';
            $this->returnJson['data'] = $result;
        }else {
            $this->returnJson['code'] = '-1';
            $this->returnJson['message'] = 'no data';
        }
        $this->exitOutput($this->returnJson);
    }

    /**
     * 查询数据数量
     * @param $typeId
     */
    public function queryCount($typeId){
        $service = new CourseDao();
        $count  = $service->queryCount($typeId);
        if ($count) {
            $this->returnJson['code'] = '0';
            $this->returnJson['message'] = 'success';
            $this->returnJson['size'] = PAGE_SIZE; //每页显示大小
            $this->returnJson['data'] = $count;
        }else {
            $this->returnJson['code'] = '-1';
            $this->returnJson['message'] = 'no data';
        }
        $this->exitOutput($this->returnJson);
    }

    /**
     * 获取分类 和类型数据
     */
    public function categoryAndType() {
        $service = new CourseDao();
        $result = $service->categoryAndType();
        if ($result) {
            $this->returnJson["code"] = '0';
            $this->returnJson["message"] = 'success';
            $this->returnJson["data"] = $result;
            //error_log("categoryAndType 查询到数据".date('Y/m/d h:i:sa'). "\n", 3, "/tmp/co_future.log");
        } else {
            $this->returnJson["code"] = '-1';
            $this->returnJson["message"] = 'no data';
        }
        $this->exitOutput($this->returnJson);
    }
}