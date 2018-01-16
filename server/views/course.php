<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>正文</title>
    <!-- 为了让 Bootstrap 开发的网站对移动设备友好，确保适当的绘制和触屏缩放，需要在网页的 head 之中添加 viewport meta 标签-->
    <!--    通常情况下，maximum-scale=1.0 与 user-scalable=no 一起使用。这样禁用缩放功能后，用户只能滚动屏幕，就能让您的网站看上去更像原生应用的感觉。-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--    <meta name="viewport" content="width=device-width, -->
    <!--                                     initial-scale=1.0, -->
    <!--                                     maximum-scale=1.0, -->
    <!--                                     user-scalable=no">-->

    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- 可选的Bootstrap主题文件（一般不使用） -->
    <link href="../../bootstrap/css/bootstrap-theme.min.css" rel="stylesheet"/>

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="../../bootstrap/js/jquery.min.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="../../bootstrap/js/bootstrap.min.js"></script>

</head>
<body>
<div class="container">

<script>
    $(function () {
        $.get("./router.php?action=detail&course_id=<?php echo $_GET['course_id']; ?>", function (d) {
            var data = JSON.parse(d);
            if (data['message'] === 'success') {
                $(".container").html(data['data']['content']);
            }else {
                $(".container").html("<h1>无法获取详情</h1>");
            }
        });
    });
</script>
</div>
</body>
</html>