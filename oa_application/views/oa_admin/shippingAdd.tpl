<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i>  邮费模板管理 <span class="c-gray en">&gt;</span> {$typeMsg}</nav>
<div class="pd-20">
	<form class="Huiform" id="form-shipping-add" action="{formatUrl('good/doAddShipping')}" method="post">
		{if isset($info)}
		<input name="shipping_id" type="hidden" value="{$info['shipping_id']}">
		{/if}
		<table class="table table-border table-bordered table-bg">
      		<tbody>
      			<tr>
          		     <th class="text-r" width="80"><span class="c-red">*</span>模板名：</th>
          			 <td><input name="shipping_name" type="text" class="input-text" id="shipping_name" value="{if isset($info)}{$info['shipping_name']}{/if}" nullmsg="模板名不能为空！" datatype="*"></td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="80"><span class="c-red">*</span>快递公司：</th>
          			 <td>
          			    <select class="select-box" id="shipping_postage" name="shipping_postage" nullmsg="快递公司不能为空！" datatype="*">
      						<option value="">请选择</option>
      						{foreach $shipping_postage as $k=>$item}
      						<option value="{$k}" {if isset($info) && $info['shipping_postage'] == $k}selected{/if}>
      						{$item[0]}
      						</option>
      						{/foreach}
    					</select>
          			</td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="80"><span class="c-red">*</span>首重重量：</th>
          			 <td><input name="shipping_first_weight" type="text" class="input-text" style="width:80%" id="shipping_first_weight" value="{if isset($info)}{$info['shipping_first_weight']}{else}1{/if}" nullmsg="首重重量不能为空！" datatype="n">&nbsp;KG</td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="80"><span class="c-red">*</span>续重重量：</th>
          			 <td><input name="shipping_additional_weight" type="text" class="input-text" style="width:80%" id="shipping_additional_weight" value="{if isset($info)}{$info['shipping_additional_weight']}{else}1{/if}" nullmsg="续重重量不能为空！" datatype="n">&nbsp;KG</td>
        		</tr>
        		<tr>
          			<th class="text-r va-t"><span class="c-red">*</span>类型：</th>
          			<td>
          			    <select class="select-box" id="shipping_type" name="shipping_type" nullmsg="类型不能为空！" datatype="*">
      						<option value="">请选择类型</option>
      						{foreach $shipping_type as $k=>$item}
      						<option value="{$k}" {if isset($info) && $info['shipping_type'] == $k}selected{/if}>
      						{$item}
      						</option>
      						{/foreach}
    					</select>
          			</td>
          		</tr>
          		<tr class="otype" style="display:none;">
          		     <th class="text-r" width="80"><span class="c-red"></span>包邮额度：</th>
          			 <td><input name="shipping_condition" type="text" class="input-text" id="shipping_condition" value="{if isset($info)}{$info['shipping_condition']}{/if}"></td>
        		</tr>
        		<tr  class="ntype" style="display:none;">
          		     <th class="text-r" width="80"><span class="c-red"></span>邮费设置：</th>
          			 <td>
          			 <table class="table table-border table-bordered table-bg" width="80%">
          			 <tbody>
          			 {foreach $province as $k=>$pr}
          			 <tr>
          			     <td class="text-c" width="80">{$pr}<input type="hidden" name="pr[]" value="{$k}"></td><td>首重：<input type="text" name="fgp[]">&nbsp;元；续重：<input type="text" name="agp[]">&nbsp;元</td>
          			 </tr>
          			 {/foreach}
          			 </tbody>
          			 </table>
          			 </td>
        		</tr>        		
          		<tr>
          			<th>&nbsp;</th>
          			<td>
            			<button type="submit" class="btn btn-success radius"><i class="icon-ok"></i> {$typeMsg}</button>
          			</td>
        		</tr>
      		</tbody>
      	</table>
	</form>
</div>
<script type="text/javascript" src="/oa_public/oa_admin/js/good.js"></script>