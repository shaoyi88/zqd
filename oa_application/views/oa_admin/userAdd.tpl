<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i>  会员管理 <span class="c-gray en">&gt;</span> <span class="c-gray en">&gt;</span> {$typeMsg}</nav>
<div class="pd-20">
	<form class="Huiform" id="form-user-add" action="{formatUrl('user/doAdd')}" method="post">
		{if isset($info)}
		<input name="user_id" type="hidden" value="{$info['user_id']}">
		{/if}
		<table class="table table-border table-bordered table-bg">
			<tbody>
			    <tr>
          		     <th class="text-r" width="180">姓名：</th>
          			 <td>{if isset($info)}{$info['user_name']}{/if}</td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="180">手机号：</th>
          			 <td>{if isset($info)}{$info['user_phone']}{/if}</td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="180">账户余额：</th>
          			 <td>{if isset($info)}{$info['user_account']}{/if}</td>
        		</tr>
				<tr>
          		     <th class="text-r" width="180">{if !isset($info)}<span class="c-red">*</span>{/if}充值金额：</th>
          			 <td><input name="add_account" type="text" class="input-text" id="add_account" value="" nullmsg="账户不能为空！" datatype="n"></td>
        		</tr>
        		<tr>
          			<th></th>
          			<td>
            			<button type="submit" class="btn btn-success radius"><i class="icon-ok"></i> {$typeMsg}</button>
          			</td>
        		</tr>
        	</tbody>
		</table>
	</form>
</div>
<script type="text/javascript" src="/oa_public/oa_admin/js/user.js"></script>