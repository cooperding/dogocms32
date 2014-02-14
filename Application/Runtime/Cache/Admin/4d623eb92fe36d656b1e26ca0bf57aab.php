<?php if (!defined('THINK_PATH')) exit();?><form action="<?php echo U('NavFoot/update');?>" class="form_dogocms" method="post">
    <input type="hidden" name="id" value="<?php echo ($data["id"]); ?>" />
    <div class="division">
        <table>
            <tbody>
                <tr>
                    <th>分类名称：</th>
                    <td><input type="text" name="text" value="<?php echo ($data["text"]); ?>" data-options="required:true" class="easyui-validatebox"/><span class="red">*</span></td>
                </tr>
                <tr>
                    <th>上级分组名：</th>
                    <td><input name="parent_id" id="combotree" class="easyui-combotree combotree" data-options="url:'<?php echo U('NavFoot/jsonTree');?>',required:true," value="<?php echo ($data["parent_id"]); ?>"  style="width:200px;"/></td>
                </tr>
                <tr>
                    <th>状态：</th>
                    <td><html:radio name="status" radios="status" checked="v_status" /></td>
                </tr>
                <tr>
                    <th><?php echo L("keywords");?>：</th>
                    <td><input type="text" name="keywords" value="<?php echo ($data["keywords"]); ?>" /></td>
                </tr>
                <tr>
                    <th><?php echo L("description");?>：</th><td><textarea name="description" rows="3" cols="30"><?php echo ($data["description"]); ?></textarea><span class="red"></span></td>
                </tr>
                <tr>
                    <th><?php echo L("orderby");?>：</th><td><input type="text" name="myorder" value="<?php echo ($data["myorder"]); ?>" /></td>
                </tr>
            </tbody>
        </table></div>
</form>