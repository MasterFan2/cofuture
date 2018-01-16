<?php
/**
 * 添加或修改
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . "/server/bean/Course.class.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/server/bean/Category.class.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/server/bean/Type.class.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/server/status/Status.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/server/controller/CategoryController.class.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/server/controller/CourseController.class.php";

if (isset($_GET['action'])) {//GET
    $action = $_GET['action'];

    switch ($action) {
        case "detail": //查询course详情
            queryCourseDetail();
            break;

        case "courseList"://查询course列表
            queryCourseList();
            break;

        case "queryPage":
            queryPage($_GET['pageIndex'], $_GET['typeId']);
            break;

        case "category"://分类列表
            getCategoryList();
            break;

        case "type"://获取分类
            getTypeByCategoryId();
            break;

        case "pageSize"://获取指定类型下的数据大小
            coursePageSize();
            break;

        case "categoryAndType"://获取导航菜单数据
            categoryAndType();
            break;

        case "delete"://删除课程
            delete();
            break;

        case "deleteCategory"://删除分类, 子分类
            deleteCategory();
            break;

        case "typeList"://子类型列表
            typeList();
            break;

        case "saveCategory"://分类保存
            saveCategory();
            break;

        case "saveType":
            saveType();
            break;

            ////////////////////////////////////////
        case "indexHotCategory":
            indexHotCategory();
            break;

        default://404

            break;
    }
}

if (isset($_POST['action'])) {//POST
    $action = $_POST['action'];

    switch ($action) {
        case "save"://保存
            saveEssay();
            break;
    }
}

/** ----------------------------------------前端--------------------------------------------- */

/**
 * 课程列表
 */
function queryCourseList() {
    $controller = new CourseController();
    $controller->queryCourseList($_GET['typeId']);
}

/**
 * 首页热门分类
 */
function indexHotCategory() {
    $controller = new CategoryController();
    $controller->queryIndexHotCategory();
}

/** ----------------------------------------前端--------------------------------------------- */

/**
 * 删除类型
 */
function deleteCategory(){
    $controller = new CategoryController();
    $controller->deleteById($_GET['id'], $_GET['type']);
}

/**
 * 保存分类 或子类型
 */
function saveCategory() {
    $controller = new CategoryController();
    $obj = null;
    if ($_GET['type'] == 0) {//category
        $obj = new Category();
        if(isset($_GET['category_id'])) {
            $obj->category_id = $_GET['category_id'];
        }
        $obj->name = $_GET['name'];
        $obj->date = $_GET['date'];
        $obj->note = $_GET['note'];
        $obj->isHot= $_GET['isHot'];
        $obj->isRecommend= $_GET['isRecommend'];

    }else {   //type
        $obj = new Type();
        if(isset($_GET['type_id'])) {
            $obj->type_id = $_GET['type_id'];
        }
        $obj->name = $_GET['name'];
        $obj->date = $_GET['date'];
        $obj->note = $_GET['note'];
        $obj->category_id = $_GET['category_id'];
        $obj->isHot= $_GET['isHot'];
        $obj->isRecommend= $_GET['isRecommend'];
    }
    $controller->addOrUpdate($obj, $_GET['type']);
}

/**
 * 通过分类查子类型
 */
function typeList(){
    $controller = new CategoryController();
    $controller->queryList(1, $_GET['categoryId']);
}

/**
 * 删除数据
 */
function delete() {
    $controller = new CourseController();
    $controller->delete($_GET['course_id']);
}

/**
 * 获取分类数据和子数据
 */
function categoryAndType() {
    $controller = new CourseController();
    $controller->categoryAndType();
}

/**
 * 通过页码查询[分页查询]
 * @param $index 页码
 * @param $typeId int|类型
 */
function queryPage($index, $typeId) {
    setHeader();

    $controller = new CourseController();
    $controller->queryByPage($typeId, $index);
}

/**
 * 获取课程列表大小
 */
function coursePageSize(){
    $controller = new CourseController();
    $controller->queryCount($_GET['typeId']);
}

/**
 * 获取所有分类
 */
function getCategoryList() {
    $controller = new CategoryController();
    $controller->queryList(0);
}

/**
 * 获取通过分类 ID， 获取类型
 */
function getTypeByCategoryId() {
    $controller = new CategoryController();
    $controller->queryList(1, $_GET['categoryId']);
}

/**
 * 保存文章[post]
 */
function saveEssay() {
    // var param = {"content": content, "action": "save", "title": title, "sub_title": sub_title, "views":$("#input-views").attr("value"), "helps":$("#input-help").attr("value"), "typeId":typeId};//参数
    setHeader();
    $controller = new CourseController();
    $course = new Course();
    $course->content = $_POST['content'];
    $course->user_id = 1;
    $course->sub_title = $_POST['sub_title'];
    $course->title   = $_POST['title'];
    $course->date    = date("Y-m-d H:i:s");
    $course->type_id = $_POST['typeId'];
    $course->views = $_POST['views'];
    $course->help_to_me = $_POST['help_to_me'];

    if (isset($_POST['course_id'])) {
        $course->course_id = $_POST['course_id'];
    }

    $controller->addOrUpdate($course);
}

/**
 * 设置Header
 */
function setHeader() {
    //处理跨域
    header("Access-Control-Allow-Origin:*"); //*号表示所有域名都可以访问
    header("Access-Control-Allow-Method:POST,GET");
    header("HTTP/1.0 200 OK");
}

/**
 * 查询单个文章详情
 */
function queryCourseDetail() {
    setHeader();
    $controller = new CourseController();
    $controller->detail($_GET['course_id']);
}





