function nextcateshow(id) {
        $("xblock > button").attr("class","layui-btn ");
        var a =$("#cate"+id);
        a.attr("class","layui-btn layui-btn-danger");
        $.ajax({
            //几个参数需要注意一下
            type: "POST",//方法类型
            dataType: "json",//预期服务器返回的数据类型
            url: "json/ajax.php",//url
            data: {act: "nextshow", id: id},
            success: function (result) {
                var html ="<tr>";
                for(var i=0 ; i<result.length;i++)
                {
                    var src = result[i]['cate_img'];
                    var time1 = parseInt(result[i]['addtime']);
                    var did = result[i]['id'];
                    html +="<td>"+result[i]['id']+"<td>"+result[i]['cate_name']+"</td>"+"<td>"+result[i]['cate_desc']+"</td>";
                    html +="<td><img style='width: 40px;height:40px;' src="+src+"></td>";
                    html +="<td>"+formatDateTime(time1)+"</td>";
                    html +="<td></td>";
                    html +="<td class='td-manage'><a title='编辑' href='javascript:;' onclick=\"question_edit('编辑','question-add.php?id="+result[i]['id']+"','4','1000','600')\"><i class='layui-icon'>&#xe642;</i></a><a title='删除' href='javascript:;' onclick=\"question_del('删除',"+result[i]['id']+")\"><i class='layui-icon'>&#xe640;</i></a></td>";
                    html +="</tr>";
                    num = i;

                }
                $("#count").html("共有数据："+(num+1)+"");
                $("#showcontent").html(html);
            },
            error: function () {
                layer.msg("异常");
                       return ;
            }
        });
    }
