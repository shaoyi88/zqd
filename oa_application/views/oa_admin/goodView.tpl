<link href="/oa_public/oa_admin/css/good.css" type="text/css" rel="stylesheet">
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i>  商品管理 <span class="c-gray en">&gt;</span> 商品详情</nav>
<div class="pd-20">
	<table class="table table-border table-bordered table-bg">
      		<tbody>
      			<tr>
          		     <th class="text-r" width="80">商品名：</th>
          			 <td>{if isset($info)}{$info['good_name']}{/if}</td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="80">重量(克)：</th>
          			 <td>{if isset($info)}{$info['good_weight']}{/if}</td>
        		</tr>
        		<tr>
          			<th class="text-r">类型：</th>
          			<td>{if isset($info)}{$good_type[$info['good_type']]}{/if}</td>
          		</tr>
          		<tr>
          			<th class="text-r">分类：</th>
          			<td>{if isset($info)}{$category[$info['good_category']]}{/if}</td>
          		</tr>
          		<tr>
          			<th class="text-r">商品主图：</th>
          			<td>
          			{if isset($info)&&!empty($mainImg)}
          			<div class="files">
          			{foreach $mainImg as $v}
          			<div class="images_zone"><span><img src="{$v}" /></span></div>
          			{/foreach}
          			</div>
					<div style="clear:both;"></div>
					{/if}
          			</td>
          		</tr>
          		<tr>
          			<th class="text-r">邮费模板：</th>
          			<td>{if isset($info)}{$shipping[$info['good_delivery_info']]['shipping_name']}{/if}</td>
          		</tr>
          		<tr>
          			<th class="text-r va-t">商品详情：</th>
          			<td>{if isset($info)}{$info['good_detail']}{/if}</td>
          		</tr>
          		<tr>
          			<th class="text-r">商品属性：</th>
          			<td>
          			{if isset($info)&&!empty($sku)}
          			<table class="table table-border table-bordered table-bg">
          			<tbody>
          			<tr>
          			    <th>SKU号</th>
          			    <th>描述</th>
          			    <th>数量</th>
          			    <th>售价</th>
          			    <th>成本</th>
          			    <th>状态</th>
          			</tr>        			
          			{foreach $sku[$info['good_id']] as $v}
          			<tr>
          			    <td>{$v['sku_no']}</td>
          			    <td>{$v['sku_info']}</td>
          			    <td>{$v['sku_num']}</td>
          			    <td>{$v['sku_price']}</td>
          			    <td>{$v['sku_cost']}</td>
          			    <td>{$shelfStatus[$v['sku_shelf_status']]}</td>
          			</tr>
        		    {/foreach}       		           		    
          			</ul>
          			{/if}
          			</td>
          		</tr>
      		</tbody>
      	</table>
</div>
<script type="text/javascript">

</script>
