<?php
/**
 * desc:
 * Created by MasterFan on  2017/12/26 0026 15:08.
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/server/service/TypeService.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/server/controller/BaseController.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/server/dao/CategoryDao.class.php';

class CategoryController extends BaseController implements TypeService
{

    private $returnJson = array('type'=> 'category');

    /**
     * 首页的热门分类
     */
    public function queryIndexHotCategory() {
        $service = new CategoryDao();
        $result = $service->queryIndexHotCategory();
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


    /** ============================================以下为管理平台============================================   */

    /**
     * 返回所有一级分类
     * @param $type  类型：0-分类， 1-类型
     * @param int|分类id $categoryId 分类id, 获取类型时传参
     * @return CategoryList
     */
    public function queryList($type, $categoryId = 0)
    {
        $service = new CategoryDao();

        if ($type == 1 && $categoryId <= 0) {
            $this->returnJson['code'] = '1';
            $this->returnJson['message'] = 'params error! categoryId illegality';
            $this->exitOutput($this->returnJson);
        }

        $result = $service->queryList($type, $categoryId);

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
     * 添加或更新分类/类型
     * @param $obj   分类/类型
     * @param $type  类型：0-分类， 1-类型
     */
    public function addOrUpdate($obj, $type)
    {
        $service = new CategoryDao();
        $hasError = $service->addOrUpdate($obj, $type);
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
     * 删除分类、类型
     * @param $id   删除的唯一ID
     * @param $type 类型：0-分类， 1-类型
     */
    public function deleteById($id, $type)
    {
        $service = new CategoryDao();
        $result = $service->deleteById($id, $type);
        if ($result) {
            if ($result == 1) {
                $this->returnJson['code']    = '0';
                $this->returnJson['message'] = 'success';
            }else {
                $this->returnJson['code']    = '1';
                $this->returnJson['message'] = 'has sub type';
                $this->returnJson['data']    = $result;
            }
        }else {
            $this->returnJson['code'] = '-1';
            $this->returnJson['message'] = 'error';
        }
        $this->exitOutput($this->returnJson);
    }
}