<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>分类管理</title>
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

    <script type="text/javascript">

        var categoryId = -1;//当前操作的分类 ID;
        var submitType = -1;//提交类型， OptType

        if (typeof OptType === "undefined") {
            var OptType = {};
            OptType.AddCategory = 0;
            OptType.AddType = 1;
            OptType.EditCategory = 2;
            OptType.EditType = 3;
        }

        $(function () {
            queryCategoryList();
            $("#modal-progressbar-layout").hide();
        });

        /**
         * 获取分类列表
         */
        function queryCategoryList() {
            $.get("./router.php?action=category", function (d) {
                var data = JSON.parse(d);
                if (data['message'] === 'success') {
                    var strBuilder = "";
                    var list = data['data'];
                    var len = list.length;
                    for (var i = 0; i < len; i++) {
                        strBuilder += "<tr categoryid='" + list[i]['category_id'] + "'><td>" + list[i]['name'] + "</td><td>" + list[i]['date'].split(" ")[0] + (list[i]['is_hot'] === 1 ? "<span style='color: #b94a48'>[热]</span>":"") + (list[i]['is_recommend'] === 1 ? "<span style='color: #b94a48'>[推]</span>":"")+ "</td><td align='right'>" +
                            "<a class='btn btn-default' href='#' onclick='edit(OptType.EditCategory, " + list[i]['category_id'] + ",\"" + list[i]['name'] + "\",\"" + list[i]['date'] + "\",\"" + list[i]['note'] + "\",\""+ list[i]['is_hot'] +"\", \""+ list[i]['is_recommend']  +"\")'>修改</a>&nbsp;&nbsp;" +
                            "<a class='btn btn-warning' href='#' onclick='del("+  list[i]['category_id'] +", 0)'>删除</a>" +
                            "</td></tr>";
                    }
                    $("#tboty-category").html(strBuilder);

                    $("#tboty-category tr:eq(0)").addClass("success");

                    //首次获取第一个的子类型
                    categoryId = list[0]['category_id'];
                    getTypeByCategoryId(list[0]['category_id']);

                    //分类item点击, 获取对应的子类型
                    $("#tboty-category tr").bind("click", function () {
                        categoryId = $(this).attr("categoryid");
                        $("#tboty-category tr[class='success']").removeClass("success");
                        $(this).addClass("success");

                        getTypeByCategoryId(categoryId);//获取子分类数据
                    });
                } else {//无数据
                    $("#tboty-category").html("<h1>加载分类失败</h1>");
                }
            });
        }

        /**
         * 编辑
         */
        function edit(type, id, name, date, note, is_hot, is_recommend) {
            submitType = type;

            $('#editModal').modal({keyboard: false});

            $("#input-name").val(name);
            $("#input-date").val(date);
            $("#input-note").val(note);
            $("#input-id").val(id);

            if (is_hot === "1"){
                $("input[name='check-hot']").prop("checked", true);//高版本用， 低版本用attr
            }else {
                $("input[name='check-hot']").prop("checked", false);
            }

            if (is_recommend === "1"){
                $("input[name='check-recommend']").prop("checked", true);
            }else {
                $("input[name='check-recommend']").prop("checked", false);
            }
        }

        /**
         * 删除分类
         * @param id
         * @param type 0:分类 category,     1:类型type
         */
        function del(id, type) {
            $.get("router.php?action=deleteCategory&id=" + id + "&type=" + type, function (d) {
                var data = JSON.parse(d);
                if (data['message'] === 'success') {
                    if (type === 0) {//
                        queryCategoryList();
                    }else {
                        getTypeByCategoryId(categoryId);
                    }
                }else {
                    if (data['code'] === '1') {//该分类下有子分类

                        var strBuilder = "<h3>当前分类有以下子分类,不能删除<br/>";
                        var list = data['data'];
                        var len = list.length;
                        for (var i = 0; i < len; i++) {
                            strBuilder += "<small>"+ list[i]['name'] +"</small><br/>";
                        }
                        strBuilder += "</h3>";

                        $("#hasSubTypeModal-body").html(strBuilder);
                        $('#hasSubTypeModal').modal({backdrop: 'static', keyboard: false});
                    }
                }
            });
        }

        /**
         * 获取类型列表
         * @param $categoryId
         */
        function getTypeByCategoryId(categoryId) {
            $.get("router.php?action=typeList&categoryId=" + categoryId, function (d) {
                var data = JSON.parse(d);
                if (data['message'] === 'success') {
                    var strBuilder = "";
                    var list = data['data'];
                    var len = list.length;
                    for (var i = 0; i < len; i++) {
                        strBuilder += "<tr class='success' typeid='" + list[i]['type_id'] + "'><td>" + list[i]['name'] + "</td><td>" + list[i]['date'].split(" ")[0] + (list[i]['is_hot'] === 1 ? "<span style='color: #b94a48'>[热]</span>":"") + (list[i]['is_recommend'] === 1 ? "<span style='color: #b94a48'>[推]</span>":"") +"</td><td align='right'>" +
                            "<a class='btn btn-default' href='#' onclick='edit(OptType.EditType, " + list[i]['type_id'] + ",\"" + list[i]['name'] + "\",\"" + list[i]['date'] + "\",\"" + list[i]['note'] + "\", \""+ list[i]['is_hot'] +"\", \""+ list[i]['is_recommend'] +"\")' >修改</a>&nbsp;&nbsp;" +
                            "<a class='btn btn-warning' href='#' onclick='del("+  list[i]['type_id'] +", 1)'>删除</a>" +
                            "</td></tr>";
                    }
                    $("#tboty-type").html(strBuilder);
                } else {//无数据
                    $("#tboty-type").html("<h1>无数据</h1>");
                }
            });
        }

        /**
         * 提交保存
         */
        function submit(type) {
            var name = $("#input-name").val();
            var date = $("#input-date").val();
            var note = $("#input-note").val();
            var id   = $("#input-id").val();

            var isHot = $("#input-check-hot")[0].checked;
            var isRecommend = $("#input-check-recommend")[0].checked;

            if (name === null || name === "") {
                alert("名称必填");
                return;
            }

            if (date === null || date === "") {
                alert("日期必填");
                return;
            }

            if (id === null || id === "") {
                doSubmit(type, name, date, note, (isHot ? 1 : 0), (isRecommend ? 1: 0));
            }else {
                doSubmit(type, name, date, note, (isHot ? 1 : 0), (isRecommend ? 1: 0), id);
            }
        }

        /**
         * 提交到数据库
         * @param type 操作类型
         * @param name 名称
         * @param date 日期
         * @param note 备注
         * @param isHot 是否热门
         * @param isRecommend 是否推荐
         * @param id   操作id 唯一编号, 如果是0， 表示是新增操作， 其它为修改
         * OptType.AddCategory = 0;
         OptType.AddType     = 1;
         OptType.EditCategory= 2;
         OptType.EditType    = 3;
         */
        function doSubmit(type, name, date, note, isHot, isRecommend, id = 0) {
            var TYPE_ID = "";
            var finalType = -1;
            var cId = -1;

            if (type === 2 || type === 0) {
                TYPE_ID = "category_id";
                finalType = 0;
            } else {
                TYPE_ID = "type_id";
                finalType = 1;
                cId = categoryId;
            }

            var url = "./router.php?action=saveCategory&name=" + name + "&date=" + date + "&note=" + note + "&type=" + finalType + "&isHot=" + isHot + "&isRecommend=" + isRecommend;
            if (id !== 0) {
                url += "&" + TYPE_ID + "=" + id;
            }

            if (cId !== -1) {
                url += "&category_id=" + cId;
            }

            $("#modal-progressbar-layout").show();
            $("#modal-btn-layout").hide();
            $.get(url, function (d) {
                var data = JSON.parse(d);
                if (data['message'] === 'success') {
                    $('#editModal').modal('hide');
                    $("#modal-progressbar-layout").hide();
                    $("#modal-btn-layout").show();

                    //操作成功， 刷新数据
                    if (type === 1 || type === 3) {//类型操作
                        getTypeByCategoryId(categoryId);
                    }else {                       //分类操作
                        queryCategoryList();
                    }
                }else {//error
                    $("#modal-progressbar-layout").hide();
                    $("#modal-btn-layout").show();
                }
            });
        }

        /**
         * 添加操作
         * @type 0:分类 ， 1：子分类
         */
        function add(type) {
            ///显示等待框, 点击窗口外或esc不关闭
            submitType = type;
            if (type === 0) {
                $("#myModalLabel").text("添加分类");
            } else {
                $("#myModalLabel").text("添加子分类");
            }

            clearInputModel();
            $('#editModal').modal({backdrop: 'static', keyboard: false});
        }

        /**
         * 清除输入框内容
         */
        function clearInputModel() {
            $("#input-name").val("");
            $("#input-date").val("");
            $("#input-note").val("");
            $("#input-id").val("");
            $("#input-check-recommend").prop("checked", false);
            $("#input-check-hot").prop("checked", false);
        }
    </script>
</head>
<body>
<div class="container">
    <h1>分类管理</h1>

    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">
                分类
                <small>这是分类管理</small>
            </h3>
        </div>
        <div class="panel-body table-responsive row">
            <div class="col-sm-6">
                <!--   表格， 显示主内容  -->
                <table class="table table-hover table-condensed">
                    <thead>
                    <tr>
                        <th>分类名称</th>
                        <th></th>
                        <th>
                            <button class="btn btn-info" onclick="add(OptType.AddCategory);">添加</button>
                        </th>
                    </tr>
                    </thead>
                    <tbody id="tboty-category">
                    </tbody>
                </table>
            </div>

            <div class="col-sm-6">
                <!--   表格， 显示主内容  -->
                <table class="table table-hover table-condensed">
                    <thead>
                    <tr>
                        <th>子分类</th>
                        <th></th>
                        <th>
                            <button class="btn btn-info" onclick="add(OptType.AddType);">添加</button>
                        </th>
                    </tr>
                    </thead>
                    <tbody id="tboty-type">
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- 模态框[添加, 修改] -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">模态框（Modal）标题</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon">名称</span>
                        <input id="input-name" type="text" class="form-control"/>
                        <input id="input-id" type="hidden" class="form-control"/>
                    </div>
                    <div class="input-group" style="margin-top: 15px;">
                        <span class="input-group-addon">备注</span>
                        <input id="input-note" type="text" class="form-control"/>
                    </div>
                    <div class="input-group" style="margin-top: 15px;">
                        <span class="input-group-addon">日期</span>
                        <input id="input-date" type="text" class="form-control"/>
                    </div>
                    <div>
                        <input id="input-check-hot" name="check-hot" test="masterFan" type="checkbox" />热门？     <input id="input-check-recommend" name="check-recommend" type="checkbox" />推荐？
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="modal-btn-layout">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-success" onclick="submit(submitType)">提交更改</button>
                    </div>

                    <div id="modal-progressbar-layout" class="progress progress-striped active">
                        <div class="progress-bar progress-bar-success" role="progressbar"
                             aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"
                             style="width: 100%;">
                            <span class="sr-only">正在处理...</span><!-- 残疾人屏幕阅读器使用 -->
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
    <!-- end模态框（Modal） -->


    <!-- 模态框[ 有子分类] -->
    <div class="modal fade" id="hasSubTypeModal" tabindex="-2" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">提示您</h4>
                </div>
                <div id="hasSubTypeModal-body" class="modal-body">

                </div>
                <div class="modal-footer">
                    <div id="modal-btn-layout">
                        <button type="button" class="btn btn-success" data-dismiss="modal">知道了</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
    <!-- end  模态框[ 有子分类] -->

</div><!-- end container -->
</body>
</html>