<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 会员管理<span class="c-gray en">&gt;</span> 提现管理 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
{if isset($msg)}
<div class="header">
	<div class="Huialert Huialert-danger"><i class="icon-remove"></i>{$msg}</div>
</div>
{/if}
<div class="pd-20">
	<form class="Huiform" action="{formatUrl('user/cash')}" method="get">
		<div class="text-c"> 
			<input type="text" value="" class="input-text" style="width:250px" placeholder="输入名字/昵称" id="" name="keyword">
			<button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜提现</button>
		</div>
	</form>
	 {if empty($dataList)}
	 	<div class="cl pd-5 bg-1 bk-gray"><h2 class="text-c">暂无提现</h2></div>
	 {else}
	 	<table class="table table-border table-bg table-bordered table-hover">
			
		</table>
	 {/if}
	 {if isset($pageUrl)}{$pageUrl}{/if}
</div>