<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 商品管理<span class="c-gray en">&gt;</span> 商品列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
{if isset($msg)}
<div class="header">
	<div class="Huialert Huialert-danger"><i class="icon-remove"></i>{$msg}</div>
</div>
{/if}
<div class="pd-20">
	<form class="Huiform" action="{formatUrl('good/index')}" method="get">
		<div class="text-c"> 
			<select class="select-box" id="user_type" name="good_type" style="width:200px">
      			<option value="">请选择类型</option>
      			{foreach $good_type as $key=>$item}
      				<option value="{$key}" {if isset($keyword['good_type']) && $keyword['good_type'] != '' && $keyword['good_type'] == $key}selected{/if}>
      				{$item}
      				</option>
      			{/foreach}
    		</select>
    		&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="text" value="{if isset($keyword['good_name'])}{$keyword['good_name']}{/if}" class="input-text" style="width:250px" placeholder="输入商品名" id="" name="good_name">
			<button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜商品</button>
		</div>
	</form>
	{if checkRight('good_add')}
	<div class="cl pd-5 bg-1 bk-gray mt-20"> 
		<span class="l">
			<a href="{formatUrl('good/add')}" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加商品</a>
		</span>
	 </div>
	 {/if}
	 {if empty($dataList)}
	 	<div class="cl pd-5 bg-1 bk-gray"><h2 class="text-c">暂无商品</h2></div>
	 {else}
	 	<table class="table table-border table-bg table-bordered table-hover">
			<thead>
        		<tr class="text-c">
        		    <th rowspan="2">序号</th>
        		    <th rowspan="2">商品类型</th>
        		    <th rowspan="2">商品分类</th>
          			<th rowspan="2">商品名</th>
          			<th colspan="6">商品属性</th>         			
          			<th rowspan="2" width=200>操作</th>
        		</tr>
        		<tr class="text-c">
        		    <th>sku号</th>
        		    <th>描述</th>
        		    <th>数量</th>
          			<th>售价</th>
          			<th>成本价</th>
          			<th>状态</th>
        		</tr>
      		</thead>
      		<tbody>
			{foreach $dataList as $k=>$item}
				<tr class="text-c">
				    <td rowspan="{count($sku[$item['good_id']])}">{$k+1}</td>
				    <td rowspan="{count($sku[$item['good_id']])}">{$good_type[$item['good_type']]}</td>
        			<td rowspan="{count($sku[$item['good_id']])}">{$cat[$item['good_category']]}</td>
        			<td rowspan="{count($sku[$item['good_id']])}">{$item['good_name']}</td>
        			{foreach $sku[$item['good_id']] as $k=>$s}
          		    {if $k==0}
        			<td>{$s['sku_no']}</td>
	                <td>{$s['sku_info']}</td>
	                <td>{$s['sku_num']}</td>
	                <td>{$s['sku_price']}</td>
	                <td>{$s['sku_cost']}</td>
	                <td>{$shelfStatus[$s['sku_shelf_status']]}</td>
	                {/if}
        		    {/foreach}
          			<td rowspan="{count($sku[$item['good_id']])}">
          			    {if checkRight('good_list')}<a class="btn btn-primary radius ml-5" title="详情" href="{formatUrl('good/view?gid=')}{$item['good_id']}" style="text-decoration:none">详情</a>{/if}
          				{if checkRight('good_edit')}<a class="btn btn-primary radius ml-5 edit" title="编辑" href="{formatUrl('good/add?gid=')}{$item['good_id']}" style="text-decoration:none">编辑</a>{/if}
          				{if checkRight('good_del')}<a class="btn btn-primary radius ml-5 del" did="{$item['good_id']}" title="删除" href="javascript:;" style="text-decoration:none">删除</a>{/if}
          			</td>
          		</tr>
          		{foreach $sku[$item['good_id']] as $k=>$s}
          		{if $k>0}
	            <tr class="text-c">
	                <td>{$s['sku_no']}</td>
	                <td>{$s['sku_info']}</td>
	                <td>{$s['sku_num']}</td>
	                <td>{$s['sku_price']}</td>
	                <td>{$s['sku_cost']}</td>
	                <td>{$shelfStatus[$s['sku_shelf_status']]}</td>
	            <tr>
	            {/if}
        		{/foreach}
			{/foreach}
			</tbody>
		</table>
	 {/if}
	 {if isset($pageUrl)}{$pageUrl}{/if}
</div>
<script type="text/javascript" src="/oa_public/oa_admin/js/good.js?v=102"></script>
<input type="hidden" id="delUrl" value="{formatUrl('good/doDel')}"></input>
<input type="hidden" id="checkUrl" value="{formatUrl('good/checkGood')}"></input>