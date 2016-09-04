<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 商品管理<span class="c-gray en">&gt;</span> 商品分类列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
{if isset($msg)}
<div class="header">
	<div class="Huialert Huialert-danger"><i class="icon-remove"></i>{$msg}</div>
</div>
{/if}
<div class="pd-20">
	{if checkRight('good_cat_add')}
	<div class="cl pd-5 bg-1 bk-gray mt-20"> 
		<span class="l">
			<a href="{formatUrl('good/addCat')}" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加商品分类</a>
		</span>
	 </div>
	 {/if}
	 {if empty($dataList)}
	 	<div class="cl pd-5 bg-1 bk-gray"><h2 class="text-c">暂无商品分类</h2></div>
	 {else}
	 	<table class="table table-border table-bg table-bordered table-hover">
			<thead>
        		<tr class="text-c">
        		    <th>序号</th>
        		    <th>一级分类</th>
          			<th>二级分类</th>
          			<th width=200>操作</th>
        		</tr>
      		</thead>
      		<tbody>
			{foreach $dataList as $k=>$item}
				<tr class="text-c">
				    <td>{$k}</td>
				    <td>{$category[$k]}</td>
        			<td>{foreach $item as $n=>$h}{if isset($item[$n]['cat_name'])}{$item[$n]['cat_name']}，{/if}{/foreach}</td>
          			<td >{if checkRight('good_cat_edit')}<a title="编辑" href="{formatUrl('good/addCat?cid=')}{$k}" class="btn btn-primary radius" style="text-decoration:none">编辑</a>{/if}{if checkRight('good_cat_del')}&nbsp;&nbsp;<a did="{$k}" title="删除" href="javascript:;" class="btn btn-primary radius del" style="text-decoration:none">删除</a>{/if}</td>
          		</tr>
			{/foreach}
			</tbody>
		</table>
	 {/if}
	 {if isset($pageUrl)}{$pageUrl}{/if}
</div>
<script type="text/javascript" src="/oa_public/oa_admin/js/good.js"></script>
<input type="hidden" id="checkUrl" value="{formatUrl('good/checkCat')}"></input>
<input type="hidden" id="delUrl" value="{formatUrl('good/doCatDel')}"></input>