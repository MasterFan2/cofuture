<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>教程详情</title>

    <link rel="stylesheet" type="text/css" href="../css/head.css">
    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="../bootstrap/js/jquery.min.js"></script>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>

    <style>
        ul { width: 140px; margin-top: 20px; border-radius: 4px; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.067); }
        ul li {display: block;overflow: hidden; text-overflow: ellipsis;white-space: nowrap;}
        .header-login {width: 100%;height: 45px;}
        .header-login>div {max-width: 100px;align-content: center;position: absolute;right: 20px;top: 10px;}
        #ul-nav-title li a {text-decoration: none; width: 100%;}
        #ul-nav-title li:hover {background-color: #8c8c8c;}
        .mActive {background-color: #2b669a;color: ghostwhite;}
    </style>
    <script>

        var currentIndex = -1;//当前是第几个
        var len          = 0;//总大小
        var list = null;

        var typeId = <?php echo $_GET['typeId']; ?>;
        $.get("../server/views/router.php?action=courseList&typeId=" + typeId, function (d) {
            if (d) {
                var data = JSON.parse(d);
                if (data['message'] === 'success') {
                    var strBuilder    = "";
                    list = data['data'];
                    len = list.length;

                    if (len > 0) {
                        for (var i = 0; i < len; i++) {
                            strBuilder += "<li index='"+ i +"'><a href=\"#\" onclick='menuClick(" + i +", "+ list[i]['course_id'] +")'>"+ list[i]['title'] +"</a></li>";
                        }
                        $("#ul-nav-title").html(strBuilder);
                        $("#ul-nav-title li:first-child").addClass("mActive");//第一个选中效果
                        currentIndex = 0;
                        courseDetail(list[currentIndex]['course_id']);//第一次获取第一个
                    }
                }
            }else {
            }
        });

        ///上一个
        function prev() {
            currentIndex = currentIndex - 1;
            courseDetail(list[currentIndex]['course_id']);
        }

        ///下一个
        function next() {
            currentIndex = currentIndex + 1;
            courseDetail(list[currentIndex]['course_id']);
        }

        /**
         * 左侧菜单点击
         */
        function menuClick(current_index, course_id) {
            currentIndex = current_index;
            courseDetail(course_id);//获取详情
        }

        /**
         * 上一篇，下一篇展示处理
         */
        function prevNext(current_index) {
            if (current_index === 0) {//第一个
                if ($(".head-next").is(":hidden")) {
                    $(".head-next").show();
                }
                $(".head-prev").hide();
                $(".head-next span").text(list[0]['title']);
            }else if (current_index === len - 1) {//最后一个
                if ($(".head-prev").is(":hidden")) {
                    $(".head-prev").show();
                }
                $(".head-prev span").text(list[len - 2]['title']);//最后一个的前一个
                $(".head-next").hide();
            }else {//中间
                if ($(".head-prev").is(":hidden")) {
                    $(".head-prev").show();
                }
                if ($(".head-next").is(":hidden")) {
                    $(".head-next").show();
                }
                $(".head-prev span").text(list[currentIndex - 1]['title']);
                $(".head-next span").text(list[currentIndex + 1]['title']);
            }
        }

        /**
         * 选中效果
         */
        function selectedEffect() {
            var tmpLi = $("#ul-nav-title li[class='mActive']");//获取选中
            var selectedLi = $("#ul-nav-title li[index='"+ currentIndex +"']");

            if (tmpLi.attr("index") !== selectedLi.attr("index")) {
                tmpLi.removeClass("mActive");
                selectedLi.addClass("mActive");
            }
        }

        /**
         * 获取详情
         * @param courseId
         */
        function courseDetail(courseId) {
            selectedEffect();
            $.get("../server/views/router.php?action=detail&course_id=" + courseId, function (d) {
                var data = JSON.parse(d);
                if (data['message'] === 'success') {
                    $(".content").html(data['data']['content']);
                    prevNext(currentIndex);//上一篇，下一篇展示处理
                }else {
                    $(".content").html("<h1>无法获取详情</h1>");
                }
            });
        }
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
            <li><a href="../index.php">首页</a></li>
            <li><a href="course.html">教程</a></li>
            <li><a href="#">讲师</a></li>
            <li><a href="#">助力</a></li>
            <li><a href="#">为什么</a></li>
        </ul>

    </div>
</div>
<!-- end header-->


<!--主内容-->
<div id="content-container" class="container">
    <div class="row">
        <div class="col-md-2" >
            <ul id="ul-nav-title">
            </ul>
        </div>
        <div class="col-md-10" >
            <div class="content-head">
                <a class="head-prev" href="#" onclick="prev()"><i class="glyphicon glyphicon-arrow-left"></i><span>-</span></a>
                <a class="head-next pull-right" href="#" onclick="next()"><span>-</span><i class="glyphicon glyphicon-arrow-right"></i></a>
            </div>

            <div class="content">
            </div>

            <div class="content-foot">
                <a class="head-prev" href="#" onclick="prev()"><i class="glyphicon glyphicon-arrow-left"></i><span>基础</span></a>
                <a class="head-next pull-right" href="#" onclick="next()"><span>-</span><i class="glyphicon glyphicon-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>
<!--end 主内容-->


<!--footer-->
<div id="footer">
    <div style="width: 1024px;margin: 0 auto;color: #8c8c8c">
        渝ICP备14002476号, Copyright © 2015-2018, cofuture.cn, All Rights Reserved
    </div>
</div>
<!-- end footer-->

</body>
</html>