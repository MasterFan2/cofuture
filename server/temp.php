<?php
/**
 * desc:
 * Created by MasterFan on  2017/12/26 0026 14:54.
 */
require "./controller/CategoryController.class.php";

$controller = new CategoryController();

echo $controller->queryList(0);