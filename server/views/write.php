<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>添加文章</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--    <meta name="viewport" content="width=device-width, -->
    <!--                                     initial-scale=1.0, -->
    <!--                                     maximum-scale=1.0, -->
    <!--                                     user-scalable=no">-->

    <!--    bootstrap -->
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet"><!-- 新 Bootstrap 核心 CSS 文件 -->
    <link href="../../bootstrap/css/bootstrap-theme.min.css" rel="stylesheet"/><!-- 可选的Bootstrap主题文件（一般不使用） -->
    <script src="../../bootstrap/js/jquery.min.js"></script><!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="../../bootstrap/js/bootstrap.min.js"></script><!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <!--   end bootstrap -->


    <!--    ueditor    -->
    <script type="text/javascript" src="../../ueditor/ueditor.config.js"></script><!-- 配置文件 -->
    <script type="text/javascript" src="../../ueditor/ueditor.all.js"></script><!-- 编辑器源码文件 -->
    <!--    end ueditor    -->

    <!--    <script type="text/javascript" charset="utf-8" src="lang/zh-cn/zh-cn.js"></script>-->

    <style type="text/css">

        div {
            width: 100%;
        }

        .container {
            margin-top: 20px;
        }

        .input-group {
            margin-top: 20px;
        }

        #div-alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="input-group">
        <span class="input-group-addon">主标题</span>
        <input id="title" class="form-control" placeholder="标题"
               value="<?php echo isset($_GET['title']) ? $_GET['title'] : ""; ?>">
    </div>

    <div class="input-group">
        <span class="input-group-addon">逼标题</span>
        <input id="sub_title" class="form-control" placeholder="这是一个副标题"
               value="<?php echo isset($_GET['sub_title']) ? $_GET['sub_title'] : ""; ?>">
    </div>

    <div style="margin-top: 20px;">
        <h3>
            <small id="date"><?php echo isset($_GET['date']) ? $_GET['date'] : ""; ?></small>
        </h3>


        <!--    分类管理 -->
        <div>
            <button class="btn btn-info" onclick="typeMgr()" style="margin-top: 20px;">分类管理</button>
        </div>
        <!--   end 分类管理 -->
        <!-- dropDown -->
        <div class="row">
            <div class="col-sm-6">
                <div class="input-group">
                    <input id="input-category" type="text" class="form-control" placeholder="正在加载"/>
                    <div class="input-group-btn">
                        <button id="input-category-btn" type="button" class="btn btn-default dropdown-toggle disabled"
                                data-toggle="dropdown">主分类
                            <span class="caret"></span>
                        </button>
                        <ul id="ul-category" class="dropdown-menu pull-right"></ul>
                    </div><!-- /btn-group -->
                </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->

            <div class="col-sm-6">
                <div class="input-group">
                    <input id="input-type" type="text" class="form-control" placeholder="请选择">
                    <div class="input-group-btn">
                        <button id="input-type-btn" type="button" class="btn btn-default dropdown-toggle disabled"
                                data-toggle="dropdown">小分类
                            <span class="caret"></span>
                        </button>
                        <ul id="ul-type" class="dropdown-menu pull-right"></ul>
                    </div><!-- /btn-group -->
                </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->
        </div>
        <!-- end dropDown -->
    </div>


    <!--    查看数， 点赞-->
    <div class="row">
        <div class="col-sm-6">
            <div class="input-group">
                <span class="input-group-addon">查看数</span>
                <input id="input-views" type="text" class="form-control" placeholder="初始查看数"/>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="input-group">
                <span class="input-group-addon">点赞数</span>
                <input id="input-help" type="text" class="form-control" placeholder="初始帮助数"/>
            </div>
        </div>

    </div>
    <!--    end查看数， 点赞-->


    <!--    错误消息提示-->
    <div id="div-alert" class="alert">
        <strong>成功！</strong><span style="margin-left: 50px;">结果是成功的。</span>
    </div><!--    end错误消息提示-->


    <!-- 模态框（Modal） -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="padding: 20px;">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">正在保存,请稍后...</h4>
                </div>

                <div style="padding: 20px;">
                    <div class="progress progress-striped active">
                        <div class="progress-bar progress-bar-success" role="progressbar"
                             aria-valuenow="50" aria-valuemin="0" aria-valuemax="50"
                             style="width: 100%;">
                        </div>
                    </div>
                </div>


            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
    <!-- end 模态框（Modal） -->

    <div id="submit-result">

    </div>

    <!-- 编辑器的容器 -->
    <div id="editor" name="content" style="margin-top: 20px;"></div>
    <!-- end编辑器的容器 -->

    <button class="btn btn-info pull-right" onclick="save()" style="margin-top: 20px;">保存</button>

    <script type="text/javascript">
        //var ue = UE.getEditor('editor');
        <!-- 实例化编辑器 -->

        var categoryId = 0;//分类 ID,
        var typeId = 0;//类型ID， 提交新数据使用

        var ue = null;

        $(function () {
            $("#div-alert").hide();//hide输入提示框

            $("#input-views").attr("value", random(100, 10000));
            $("#input-help").attr("value", random(50, 1000));

            ue = UE.getEditor('editor');
            ue.addListener("ready", function () {//重要, 需要等UE加载完成后, 才能设置数据
                <?php
                if (isset($_GET['title'])) {//有数据， 修改操作
                ?>
                ///如果是编辑操作， 获取数据， 把数据放入编辑器中
                $.get("./router.php?action=detail&course_id=<?php echo $_GET['course_id']; ?>", function (d) {
                    var data = JSON.parse(d)
                    if (data['message'] === 'success') {
                        ue.setContent(data['data']['content'], true);
                    } else {
                        ue.setContent("[没有获取到相关信息]", true);
                    }
                });
                <?php
                }
                ?>
            });
        });

        /**
         * 分类管理：增、删、改、查
         */
        function typeMgr() {
            location.href = "./typemgr.php";
        }

        /**
         * 保存数据
         */
        function save() {

            ///验证标题
            var title = $("#title").val();
            if (title === "") {
                $("#div-alert").addClass("alert-danger");
                $("#div-alert").show();
                $("#div-alert strong").text("注意");
                $("#div-alert span").text("标题不能为空哦");
                return;
            } else {
                $("#div-alert").hide();
            }

            ///验证分类选择
            if (categoryId == 0) {
                $("#div-alert").addClass("alert-danger");
                $("#div-alert").show();
                $("#div-alert strong").text("注意");
                $("#div-alert span").text("请选择所属分类");
                return;
            } else {
                $("#div-alert").hide();
            }

            ///验证类型选择
            if (typeId == 0) {
                $("#div-alert").addClass("alert-danger");
                $("#div-alert").show();
                $("#div-alert strong").text("注意");
                $("#div-alert span").text("请选择类型");
                return;
            } else {
                $("#div-alert").hide();
            }

            ///验证主内容输入
            var content = ue.getContent();
            if (content === "") {
                $("#div-alert").addClass("alert-danger");
                $("#div-alert").show();
                $("#div-alert strong").text("注意");
                $("#div-alert span").text("正文内容不能为空哦");
                return;
            } else {
                $("#div-alert").hide();
            }

            ///组装参数
            var sub_title = $("#sub_title").val();
            var param = {
                "content": content,
                "action": "save",
                "title": title,
                "sub_title": sub_title,
                "views": $("#input-views").attr("value"),
                "help_to_me": $("#input-help").attr("value"),
                "typeId": typeId
            };//参数

            <?php
            if(isset($_GET['title'])){
            ?>
            param.course_id = <?php echo $_GET['course_id'] ?>;
            <?php
            }
            ?>

            ///显示等待框, 点击窗口外或esc不关闭
            $('#myModal').modal({backdrop: 'static', keyboard: false});

            ///提交数据
            postSubmit("./router.php", param);
        }

        /**
         * 提交数据
         */
        function postSubmit(url, param) {
            $.post(url, param, function (d) {
                $('#myModal').modal('hide');
                var data = JSON.parse(d);
                if (data['message'] === 'success') {
                    self.location = "./index.php";
                } else {
                    $("#div-alert").addClass("alert-danger");
                    $("#div-alert").show();
                    $("#div-alert strong").text("注意");
                    $("#div-alert span").text("添加课程失败,请检查");
                }
            });
        }

        ///获取所有分类
        $.get("./router.php?action=category", function (d) {
            var data = JSON.parse(d);
            if (data['message'] === 'success') {

                $("#input-category").attr("placeholder", "请选择");
                $("#input-category-btn").removeClass("disabled");

                var str = "";
                var list = data['data'];
                var len = list.length;
                for (var i = 0; i < len; i++) {
                    str += "<li categoryid='" + list[i]['category_id'] + "' categoryname='" + list[i]['name'] + "'><a href='#'>" + list[i]['name'] + "</a></li>";
                }
                $("#ul-category").html(str);

                //分类item点击
                $("#ul-category li").bind("click", function () {
                    categoryId = $(this).attr("categoryid");
                    var categoryName = $(this).attr("categoryname");
                    $("#input-category").attr("value", categoryName);
                    getTypeByCategoryId(categoryId);
                });
            } else {//无数据
                alert("加载分类失败！");
            }
        });

        ///通过分类id获取类型列表
        function getTypeByCategoryId(id) {
            $.get("./router.php?action=type&categoryId=" + id, function (d) {
                var data = JSON.parse(d);
                if (data['message'] === 'success') {
                    $("#input-type-btn").removeClass("disabled");
                    var str = "";
                    var list = data['data'];
                    var len = list.length;

                    for (var i = 0; i < len; i++) {
                        str += "<li typeid='" + list[i]['type_id'] + "' typename='" + list[i]['name'] + "'><a href='#'>" + list[i]['name'] + "</a></li>";//注意：新加的属性只能为小写
                    }

                    $("#ul-type").html(str);//添加下拉列表数据

                    $("#input-type").attr("value", list[0]['name']);

                    $("#ul-type li").bind("click", function () {
                        typeId = $(this).attr("typeid");
                        $("#input-type").attr("value", $(this).attr("typename"));
                    });
                } else {//无数据
                    $("#input-type").attr("value", '暂无数据');
                    $("#input-type").attr("placeholder", '暂无数据');
                    $("#input-type").addClass("disabled");
                    $("#input-type-btn").addClass("disabled");
                }
            });
        }

        ///生成随机数
        function random(min, max) {
            return parseInt(Math.random() * (max - min + 1) + min);
        }
    </script>
</div>
</body>
</html>