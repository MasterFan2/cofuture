<?php
/**
 * desc:
 * Created by MasterFan on  2017/12/26 0026 16:30.
 */

abstract class BaseController
{
    public function exitOutput($output) {
        //JSON_UNESCAPED_UNICODE ; 不对中文进行转义
//        header('Content-type:text/json');这句好坑. 加了过后返回给前端的JSON, key都没有双引号了, 切记!!
        exit(is_array($output) ? json_encode($output, JSON_UNESCAPED_UNICODE) : $output);
    }
}