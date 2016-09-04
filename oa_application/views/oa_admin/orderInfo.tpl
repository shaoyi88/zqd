<link href="/oa_public/oa_admin/css/good.css" type="text/css" rel="stylesheet">
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i>  订单管理 <span class="c-gray en">&gt;</span> 订单详情</nav>
<div class="pd-20">
	<table class="table table-border table-bordered table-bg">
      		<tbody>
      			<tr>
          		     <th class="text-r" width="80">商品名：</th>
          			 <td>{if isset($info)}{$info['good_name']}{/if}</td>
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
          		     <th class="text-r" width="80">属性：</th>
          			 <td>{if isset($info)}{$info['sku_info']}{/if}</td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="80">购买数量：</th>
          			 <td>{if isset($info)}{$info['good_num']}{/if}</td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="80">订单金额：</th>
          			 <td>{if isset($info)}{$info['pay_fee']}{/if}</td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="80">订单邮费：</th>
          			 <td>{if isset($info)}{$info['shipping_fee']}{/if}</td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="80">订单成本：</th>
          			 <td>{if isset($info)}{$info['good_num']*$info['sku_cost']}{/if}</td>
        		</tr>
          		<tr>
          		     <th class="text-r" width="80">用户：</th>
          			 <td>{if isset($info)}{$info['user_name']}{/if}</td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="80">收货地址：</th>
          			 <td>{$area[$info['province']]}&nbsp;{$area[$info['city']]}&nbsp;{$area[$info['area']]}&nbsp;{$info['address']}，{$info['username']}，{$info['userphone']}</td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="80">订单状态：</th>
          			 <td>{$status[$info['order_status']]}</td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="80">订单时间：</th>
          			 <td>{date('Y-m-d H:i:s',$info['order_time'])}</td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="80">支付时间：</th>
          			 <td>{if isset($info)&&$info['pay_time']}{date('Y-m-d H:i:s',$info['pay_time'])}{/if}</td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="80">物流公司：</th>
          			 <td>{if isset($info)&&$info['shipping']}{$ship[$info['shipping']][0]}{/if}</td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="80">物流单号：</th>
          			 <td>{if isset($info)&&$info['shipping_no']}{$info['shipping_no']}{/if}</td>
        		</tr>
      		</tbody>
      	</table>
</div>
<script type="text/javascript">

</script>
