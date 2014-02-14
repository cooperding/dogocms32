<?php if (!defined('THINK_PATH')) exit();?><table id="treegrid_navfoot">

</table>
<script>
    $(function() {
        var classId = 'navfoot';
        var urljson = './NavFoot/json';
        var hrefadd = './NavFoot/add';
        var hrefedit = './NavFoot/edit';
        var hrefcancel = './NavFoot/delete';
        openTreeGrid(classId, urljson, hrefadd, hrefedit, hrefcancel);
        $('#treegrid_' + classId).treegrid({
            columns: [[
                    {field: 'id', title: 'ID', width: 50, align: 'center'},
                    {field: 'text', title: '导航名称', width: 200},
                    {field: 'status', title: '状态', width: 50},
                    //{field:'parent_id',title:'parent_id',width:200},
                    {
                        field: 'action',
                        title: '动作',
                        width: 50,
                        formatter: function(value, row, index) {
                            return '<img class="btn_do" src="/qiuyun/thinkphp32/Public/Easyui/themes/icons/pencil.png" onclick="ding_edit(\'' + hrefedit + '?id=' + row.id + '\',\'' + classId + '\')"  title="编辑"/>&nbsp;\n\
<img class="btn_do" src="/qiuyun/thinkphp32/Public/Easyui/themes/icons/cancel.png" onclick="ding_cancel(\'' + row.id + '\',\'' + hrefcancel + '\',\'' + classId + '\')" title=" 删除"/>&nbsp;';
                        }
                    }
                ]]
        });
    });
</script>