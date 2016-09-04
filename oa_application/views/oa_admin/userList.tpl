<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 会员管理<span class="c-gray en">&gt;</span> 会员列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
{if isset($msg)}
<div class="header">
	<div class="Huialert Huialert-danger"><i class="icon-remove"></i>{$msg}</div>
</div>
{/if}
<div class="pd-20">
	<form class="Huiform" action="{formatUrl('user/index')}" method="get">
		<div class="text-c"> 
			<input type="text" value="{if isset($keyword['keyword'])}{$keyword['keyword']}{/if}" class="input-text" style="width:250px" placeholder="输入会员昵称" id="" name="keyword">
			<button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜会员</button>
		</div>
	</form>
	{if checkRight('user_add')}
	<div class="cl pd-5 bg-1 bk-gray mt-20"> 
		<span class="l">
			<a href="javascript:;" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 导入会员</a>
		</span>
	 </div>
	 {/if}
	 {if empty($dataList)}
	 	<div class="cl pd-5 bg-1 bk-gray"><h2 class="text-c">暂无会员</h2></div>
	 {else}
	 	<table class="table table-border table-bg table-bordered table-hover">
			<thead>
        		<tr class="text-c">
        		    <th>头像</th>
          			<th>昵称</th>
          			<th>关注微信</th>
          			<th>账户余额</th>
          			<th width="180">操作</th>
        		</tr>
      		</thead>
      		<tbody>
			{foreach $dataList as $item}
				<tr class="text-c">
				    <td>
					{if $item['user_icon']}
			        <img class="avatar size-s c" src="{$item['user_icon']}" width="98%">
			        {else}
			 		<img class="avatar size-s c" src="/oa_public/oa_admin/images/user.png" width="98%">
			 	    {/if}
				    </td>
        			<td>{$item['user_nickname']}</td>
        			<td>{if $item['focus_status']>0}已关注{else}未关注{/if}</td>
        			<td>{$item['user_account']}</td>
          			<td>
          				{if checkRight('user_edit')&&checkRight('cash_return')}<a class="btn btn-primary radius edit" title="充值" href="{formatUrl('user/add?id=')}{$item['user_id']}" style="text-decoration:none">现金充值</a>{/if}
          				{if checkRight('user_del')&&$item['focus_status']==0}<a class="btn btn-primary radius ml-5 del" did="{$item['user_id']}" title="删除" href="javascript:;" style="text-decoration:none">删除</a>{/if}
          			</td>
          		</tr>
			{/foreach}
			</tbody>
		</table>
	 {/if}
	 {if isset($pageUrl)}{$pageUrl}{/if}
</div>
<script type="text/javascript" src="/oa_public/oa_admin/js/user.js"></script>
<input type="hidden" id="delUrl" value="{formatUrl('user/doDel')}"></input>