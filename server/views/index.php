<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/server/dao/CategoryDao.class.php";
?>
<!DOCTYPE html>
<!--Bootstrap 使用了一些 HTML5 元素和 CSS 属性。为了让这些正常工作，您需要使用 HTML5 文档类型（Doctype）-->
<html lang="en">
<head>

    <meta charset="UTF-8">

    <!-- 为了让 Bootstrap 开发的网站对移动设备友好，确保适当的绘制和触屏缩放，需要在网页的 head 之中添加 viewport meta 标签-->
    <!--    通常情况下，maximum-scale=1.0 与 user-scalable=no 一起使用。这样禁用缩放功能后，用户只能滚动屏幕，就能让您的网站看上去更像原生应用的感觉。-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--    <meta name="viewport" content="width=device-width, -->
    <!--                                     initial-scale=1.0, -->
    <!--                                     maximum-scale=1.0, -->
    <!--                                     user-scalable=no">-->
    <meta http-equiv="Cache-Control" content="max-age=7200"/>

    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- 可选的Bootstrap主题文件（一般不使用） -->
    <link href="../../bootstrap/css/bootstrap-theme.min.css" rel="stylesheet"/>

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="../../bootstrap/js/jquery.min.js"></script>
    <script src="../../bootstrap/js/bootstrap.min.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script>

        var PAGE_SIZE = 5;//默认为5条, 最后根据读取的数据来
        var pages = 0;//能分多少页
        var previousSelectedPage = null;//上一个选中的页

        var pageIndex = 1; //读取第几页数据
        var typeId = -1;    //类型ID,

        getCategoryList(); //获取导航数据[大分类数据]

        /**
         * 添加新课程
         */
        function addCourse() {
            window.location.href = "write.php";
        }

        /**
         * 获取所有分类
         */
        function getCategoryList() {
            $.get("./router.php?action=categoryAndType", function (d) {
                if (d) {
                    var data = JSON.parse(d);
                    updateNavigateUI(data);
                }else {
                    $("#ul-navbar").html("<h4>无法获取到数据</h4>");
                }
            });
        }

        /**
         * 更新导航[大分类UI.]
         */
        function updateNavigateUI(data) {
            if (data['message'] === 'success') {
                var categoryList = data['data'];
                var strBuilder = "";//动态拼接菜单
                $.each(categoryList, function (key, arr) {
                    if (arr.length <= 0) {//没有子菜单
                        strBuilder += "<li class='nav-level-one'><a href='#'>" + key + "</a></li>";

                    } else {//有子菜单
                        strBuilder += "<li class='dropdown nav-level-one'><a href='#' class='dropdown-toggle' data-toggle='dropdown'>" + key + "<b class='caret'></b></a>";
                        strBuilder += "<ul class='dropdown-menu'>";
                        for (var i = 0; i < arr.length; i++) {
                            if (typeId === -1) {
                                typeId = arr[i]['type_id'];
                            }
                            strBuilder += "<li class='subtype' typeid='" + arr[i]['type_id'] + "'><a href='#'>" + arr[i]['name'] + "</a></li>";
                        }
                        strBuilder += "</ul></li>";
                    }
                });

                $("#ul-navbar").html(strBuilder);

                bindNavClickEvent();//主绑定导航点击效果
                getPageData(pageIndex, typeId);//加载第一页内容
                getPageSizeByType(typeId);     //获取当前分类下数据大小

                $(".subtype").bind("click", function () {//子菜单点击, 获取对应数据
                    typeId = $(this).attr("typeid");
                    getPageData(pageIndex, typeId);//加载第一页内容
                    getPageSizeByType(typeId);     //获取当前分类下数据大小
                });
            } else {
                $("#ul-navbar").html("<li>没有数据菜单</li>");
            }
        }

        /**
         * 一级菜单点击效果
         */
        function bindNavClickEvent() {
            $("#ul-navbar li[class='nav-level-one']").bind("click", function () {
                var tmpActiveLabel = $("#ul-navbar li[class$='active']");
                tmpActiveLabel.removeClass("active");

                if ($(this).find("a:first-child").html() !== tmpActiveLabel.find("a:first-child").html()) {
                    $(this).addClass("active");
                }
            })
        }

        /**
         * 通过页码和类型ID加载数据
         * @param index
         * @param typeId
         */
        function getPageData(index, typeId) {
            $.get("./router.php?action=queryPage&pageIndex=" + index + "&typeId=" + typeId, function (d) {
                var data = JSON.parse(d);
                if (data['message'] === 'success') {
                    var list = data['data'];
                    var strBuilder = "";
                    for (var i = 0; i < list.length; i++) {
                        strBuilder += "<tr><td>" + list[i]['title'] + "</td><td>" + list[i]['sub_title'] + "</td><td align='right'>" +
                            "<a class='btn btn-default' href='course.php?course_id=" + list[i]['course_id'] + "'>详情</a>&nbsp;&nbsp;" +
                            "<a class='btn btn-default' href='./write.php?course_id=" + list[i]['course_id'] + "&title=" + list[i]['title'] + "&sub_title=" + list[i]['sub_title'] + "&date=" + list[i]['date'] + "&user_id=" + list[i]['user_id'] + "'>修改</a>&nbsp;&nbsp;" +
                            "<a class='btn btn-danger' href='#' onclick='deleteCourse(" + list[i]['course_id'] + ")'>删除</a>" +
                            "</td></tr>";
                    }
                    $("#table-body").html(strBuilder);
                } else {//no data or error
                    $("#table-body").html("<h3>暂无数据</h3>");
                }
            });
        }

        /**
         * 删除数据
         */
        function deleteCourse(id) {
            $.get("./router.php?action=delete&course_id=" + id, function (d) {
                var data = JSON.parse(d);
                if (data['message'] === 'success') {
                    getPageData(pageIndex, typeId);
                } else {//error
                    alert(data['message']);
                }
            });
        }

        /**
         * 通过类型获取页码大小
         * @param typeId
         */
        function getPageSizeByType(typeId) {
            $.get("./router.php?action=pageSize&typeId=" + typeId, function (d) {
                var data = JSON.parse(d);
                PAGE_SIZE = data['size'];//后台配置大小
                calculatePages(data['data']['count']);
                updatePageUI();
            });
        }

        /**
         * 计算能分多少页
         */
        function calculatePages(count) {
            if (count <= 0) {
                pages = 0;
            } else if (count <= PAGE_SIZE) {
                pages = 1;
            } else {
                pages = (count % PAGE_SIZE === 0) ? count / PAGE_SIZE : parseInt(count / PAGE_SIZE) + 1;
            }
        }

        /**
         * 绑定分页点击事件
         * @param pageSize
         */
        function bindClickEvent(pageSize) {
            if (pageSize > 1) {//首次: 多于1页, 加载分页样式

                $("#ul-page li:eq(0)").addClass("disabled");
                previousSelectedPage = $("#ul-page li:eq(1)");
                previousSelectedPage.addClass("active");

                $("#ul-page li").bind("click", function () {
                    if ($(this).text() === '«') {
                        prevPage($(this));
                    } else if ($(this).text() === '»') {
                        nextPage($(this));
                    } else {
                        jumpPage($(this))
                    }
                });
            }
        }

        /**
         * 生成分页显示数据
         */
        function updatePageUI() {
            if (pages > 0) {
                var strBuilder = "";
                if (pages > 1) {
                    strBuilder += "<li><a href='#' id='page-li-previous'>&laquo;</a></li>";
                    for (var i = 1; i <= pages; i++) {
                        strBuilder += "<li><a href='#'>" + i + "</a></li>";
                    }
                    strBuilder += "<li><a href='#' id='page-li-next'>&raquo;</a></li>";
                } else {//pages === 1
                    strBuilder += "<li><a href='#' class='disabled'>&laquo;</a></li>" +
                        "<li><a href='#' class='active'>1</a></li>" +
                        "<li><a href='#' class='disabled'>&raquo;</a></li>";
                }
                $("#ul-page").html(strBuilder);
            }
            bindClickEvent(pages);
        }

        /**
         * 下一页
         * @param label
         */
        function nextPage(label) {
            if (label.attr("class") !== "disabled") {//不能点击
                var tmpLabel = $(".pagination li[class='active']");
                var next = tmpLabel.next();

                tmpLabel.removeClass("active");
                next.addClass("active");

                getPageData(next.text(), typeId);

                if (parseInt(next.text()) === pages) {//点击下一页的时候 , 已经是最后一页了
                    $(".pagination li:first-child").removeClass("disabled");
                    $(".pagination li:last-child").addClass("disabled");
                }

                if ($(".pagination li:first-child").attr("class") === 'disabled') {//如果是第一页往后面跳转, '上一页'可点击
                    $(".pagination li:first-child").removeClass("disabled");
                }

            }
        }

        //上一页
        function prevPage(label) {
            if (label.attr("class") !== "disabled") {//判断能否不能点击
                var tmpLabel = $(".pagination li[class='active']");
                var prev = tmpLabel.prev();

                tmpLabel.removeClass("active");
                prev.addClass("active");

                getPageData(prev.text(), typeId);

                if (prev.text() === '1') {//第一页
                    $(".pagination li:first-child").addClass("disabled");
                    $(".pagination li:last-child").removeClass("disabled");
                }

                if ($(".pagination li:last-child").attr("class") === 'disabled') {//最后一页往前跳
                    $(".pagination li:last-child").removeClass("disabled");
                }
            }
        }

        //页码跳转
        function jumpPage(label) {
            if (label.text() !== previousSelectedPage.text()) {//这一项没有选中
                $(".pagination li[class='active']").removeClass("active");
                previousSelectedPage = label;
                previousSelectedPage.addClass("active");

                getPageData(label.text(), typeId);

                if (label.text() === '1') { //第一页
                    $(".pagination li:first-child").addClass("disabled");
                    $(".pagination li:last-child").removeClass("disabled");
                } else if (label.text() === pages) { //最后一页
                    $(".pagination li:first-child").removeClass("disabled");
                    $(".pagination li:last-child").addClass("disabled");
                }
            }
        }
    </script>

    <title>后台管理系统</title>
</head>

<body>
<div class="container" style="padding-top: 50px;">
    <!--    container -->

    <!--  nav  -->
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">后台管理</a>
            </div>
            <div>
                <ul id="ul-navbar" class="nav navbar-nav"></ul><!-- 主导航 -->
            </div>
        </div>
    </nav>
    <!--    end nav-->

    <!--    content-->
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">
                未来简介
                <small>这是关于未来简介</small>
            </h3>
            <button class="btn btn-success" onclick="addCourse();" style="float:right;clear: both; margin-top: -25px;">
                添加
            </button>
        </div>
        <div class="panel-body table-responsive">

            <!--   表格， 显示主内容  -->
            <table class="table table-hover table-condensed" id="essay_table">
                <thead>
                <tr>
                    <th>标题</th>
                    <th>副标题</th>
                    <th align="right"></th>
                </tr>
                </thead>
                <tbody id="table-body">
                </tbody>
            </table>

            <!-- 分页-->
            <ul id="ul-page" class="pagination pull-right"></ul>
            <!-- end 分页-->
        </div>
        <!--        <div class="panel-footer">面板脚注</div>-->
    </div>
    <!--    content end -->
</div><!--    container -->
</body>
</html>