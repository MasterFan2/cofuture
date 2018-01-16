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

    <link rel="stylesheet" type="text/css" href="css/head.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">

    <!--    slide -->
    <link rel="stylesheet" type="text/css" href="slide/css/font-awesome.css">
    <link rel="stylesheet" href="slide/css/style.css">

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="bootstrap/js/jquery.min.js"></script>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- 可选的Bootstrap主题文件（一般不使用） -->
    <link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet"/>

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <style>
        .menu_slide { margin: 0 auto;width: 1024px; background-color: #00a0e9 }
        h3 {color: white;}
        #course-container {width: 1024px; margin: 20px auto;}
        #teacher-container {width: 1024px; margin: 20px auto;}
        .header-login {width: 100%;height: 45px;}
        .header-login>div {max-width: 100px;align-content: center;position: absolute;right: 20px;top: 10px;}
    </style>

    <script>
        /**
         * 获取分类菜单
         */
        $.get("./server/views/router.php?action=indexHotCategory", function (d) {
            var data = JSON.parse(d);
            if (data['message'] === 'success') {
                var list = data['data'];
                var len = list.length;
                var strBuilder = "";
                for (var i = 0; i < len; i++) {
                    ///name
                    strBuilder += '<li>' +
                        '<div class=tx><a href="#"><i>&nbsp;</i>'+ list[i]['name'] +'</a></div>' +
                        '<dl>' +
                        '<dd>';

                    ///hot
                    var hotList = list[i]['hot'];
                    var hotLen = hotList.length;
                    for (var h = 0; h < hotLen; h++) {
                        strBuilder += '<a href="#">'+ hotList[h]['name'] +'</a>';
                    }
                    strBuilder += '</dd>' +
                        '</dl>' +
                        '<div class=pop>' +
                        '<h3><a href="#">XX</a></h3>' +
                        '<dl>' +
                        '<dl>' +
                        '<dd>';

                    ///recommend
                    var recommendList = list[i]['recommend'];
                    var recommendLen = recommendList.length;
                    for (var r = 0; r < recommendLen; r++) {
                        strBuilder += '<a class="ui-link" href="#">'+ recommendList[r]['name']  +'</a>';
                    }

                    strBuilder += '</dd>' +
                        '</dl>' +
                        '</div>' +
                        '</li>';
                }

                $("#ul-category-type").html(strBuilder);
            }else {
            }
        });

    </script>
</head>
<body>

<!--login sign up-->
<div class="header-login">
    <div>
        <a href="#">登录</a>
        <a href="#">注册</a>
    </div>
</div>
<!-- end login sign up-->


<!-- header-->
<div class="header">
    <div class="headerinner">
        <ul class="headernav">
            <li>
                <i class="fa fa-code"></i>
            </li>
            <li><a href="#">首页</a></li>
            <li><a href="ui/course.html">教程</a></li>
            <li><a href="#">讲师</a></li>
            <li><a href="#">助力</a></li>
            <li><a href="#">为什么</a></li>
        </ul>
    </div>
</div>
<!-- end header-->


<!-- 分类菜单和轮播 -->
<div class="menu_slide">
    <!-- 分类，子分类 -->
    <div class="hc_lnav jslist">
        <div class="allbtn">
            <!--        <h2><a href="#">全部商品分类</a></h2>-->
            <ul id="ul-category-type" style="width:190px" class="jspop box"></ul>
        </div>
    </div>
    <!--end 分类，子分类 -->

    <!--slide-->
    <div id="wrapper">
        <div id="slider-wrap">
            <ul id="slider">
                <li data-color="#1abc9c">
                    <div>
                        <h3>WL-CTO</h3>
                        <span>全新学习教程</span>
                    </div>
                    <i class="fa fa-image"></i>
                </li>

                <li data-color="#3498db">
                    <div>
                        <h3>WL-CTO</h3>
                        <span>学习， 一站就够</span>
                    </div>
                    <i class="fa fa-gears"></i>
                </li>
<!---->
<!--                <li data-color="#9b59b6">-->
<!--                    <div>-->
<!--                        <h3>Slide #3</h3>-->
<!--                        <span>Sub-title #3</span>-->
<!--                    </div>-->
<!--                    <i class="fa fa-sliders"></i>-->
<!--                </li>-->

                <li data-color="#34495e">
                    <div>
                        <h3>WL-CTO</h3>
                        <span>未来，已来 </span>
                    </div>
                    <i class="fa fa-code"></i>
                </li>

<!--                <li data-color="#e74c3c">-->
<!--                    <div>-->
<!--                        <h3>Slide #5</h3>-->
<!--                        <span>Sub-title #5</span>-->
<!--                    </div>-->
<!--                    <i class="fa fa-microphone-slash"></i>-->
<!--                </li>-->
            </ul>

            <!--controls-->
<!--            <div class="btns" id="next"><i class="fa fa-arrow-right"></i></div>-->
<!--            <div class="btns" id="previous"><i class="fa fa-arrow-left"></i></div>-->

            <div id="pagination-wrap">
                <ul>
                </ul>
            </div>
            <!--controls-->

        </div>
    </div>
    <!--end slide-->
</div>
<!-- end分类菜单和轮播 -->


<!--热门课程-->
<div id="course-container">
    <h3>热门推荐</h3>
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Android基础</h3>
                </div>
                <div class="panel-body">
                    <p>Android布局</p>
                    <p>Android组件</p>
                    <p>Android设置</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Android进阶</h3>
                </div>
                <div class="panel-body">
                    <p>Android布局</p>
                    <p>Android组件</p>
                    <p>Android设置</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Android高级</h3>
                </div>
                <div class="panel-body">
                    <p>Android布局</p>
                    <p>Android组件</p>
                    <p>Android设置</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Swift基础</h3>
                </div>
                <div class="panel-body">
                    <p>Swift基础语法</p>
                    <p>Swift组件</p>
                    <p>Swift代理</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">oc基础</h3>
                </div>
                <div class="panel-body">
                    <p>Swift基础语法</p>
                    <p>Swift组件</p>
                    <p>Swift代理</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">OC高级</h3>
                </div>
                <div class="panel-body">
                    <p>Swift基础语法</p>
                    <p>Swift组件</p>
                    <p>Swift代理</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end 热门课程-->


<!--讲师-->
<div id="teacher-container">
    <h3>专家讲师</h3>
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">奥斯特洛夫斯基</h3>
                </div>
                <div class="panel-body">
                    <img class="img-circle" width="150px" height="150px" src="img/head-default.jpg" alt="头像"/>
                    <div style="margin-left: 10px;">
                        主持过视频通话项目，室内定位项目，国内较早一批Android开发者.擅长的语言为熟悉所有主流开发语言... ...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end 讲师-->


<!--footer-->
<div id="footer">
    <div style="width: 1024px;margin: 0 auto;color: #8c8c8c">
        渝ICP备14002476号, Copyright © 2015-2018, cofuture.cn, All Rights Reserved
    </div>
</div>
<!-- end footer-->


<!--    slide script-->
<script type="text/javascript" src="slide/js/slide.js"></script>
<!--   end slide script-->
</body>
</html>

