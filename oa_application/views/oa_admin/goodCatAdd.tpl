<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i>  商品管理 <span class="c-gray en">&gt;</span> <a href="{formatUrl('good/category')}">商品分类管理</a> <span class="c-gray en">&gt;</span> {$typeMsg}</nav>
<div class="pd-20">
	<form class="Huiform" id="form-role-add" action="{formatUrl('good/doCatAdd')}" method="post">
		{if isset($info)}
		<input name="cat_id" type="hidden" value="{$info['cat_id']}">
		{/if}
		<table class="table table-border table-bordered table-bg">
      		<tbody>
      		    <tr>
          		     <th class="text-r" width="80">*一级分类：</th>
          			 <td>
          			 <input name="cat_name" type="text" class="input-text" id="fcat" value="{if isset($info)}{$info['cat_name']}{/if}" >
          			 </td>
        		</tr>
        		{if isset($info)}
        		<tr>
        		    <th class="text-r" width="80">二级分类：</th>
        		    <td>
        		    <ul id="sta">
        		    {if $nInfo}
        		    {foreach $nInfo as $k=>$item}
        		    <li style="margin-bottom:5px;">{$k + 1}、&nbsp;<input name="scat[]" style="width:40%;" type="text" class="input-text" value="{$item['cat_name']}" ><input type="hidden" name="scatid[]" value="{$item['cat_id']}">
        		    &nbsp;
        		    {if !in_array($item['cat_id'],$ids)}
        		    <span><a href="javascript:void(0)" class="delscat" oid="{$item['cat_id']}" style="width:40%;">&times;</a></span>
        		    {else}
        		    &nbsp;&nbsp;
        		    {/if}
        		    </li>
          		    {/foreach}
          		    {else}
          		    <li style="margin-bottom:5px;"><input name="scat[]" style="width:40%;" type="text" class="input-text" ></li>
          		    {/if}
          		    </ul>
          		    <p><a class="btn btn-primary radius addsta" href="javascript:void(0);"><i class="Hui-iconfont">&#xe600;</i></a><span></p>
          		    </td>
        		</tr>
        		{/if}       		
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
<script type="text/javascript" src="/oa_public/common/js/hogan-2.0.0.min.js"></script>
<script type="text/javascript" src="/oa_public/oa_admin/js/good.js"></script>