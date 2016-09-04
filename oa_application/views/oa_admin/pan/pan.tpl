<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="/oa_public/common/js/html5.js"></script>
<script type="text/javascript" src="/oa_public/common/js/respond.min.js"></script>
<script type="text/javascript" src="/oa_public/common/js/PIE_IE678.js"></script>
<![endif]-->
<link href="/oa_public/common/css/lib.css" rel="stylesheet" type="text/css" />
<link href="/oa_public/oa_admin/css/admin.css" rel="stylesheet" type="text/css" />
<link href="/oa_public/common/css/iconfont/iconfont.css" rel="stylesheet" type="text/css" />
<link href="/oa_public/skin/default/skin.css" rel="stylesheet" type="text/css" />
<title>球</title>
</head>
<body>
<script type="text/javascript" src="/oa_public/common/js/jquery.min.js"></script>
<script type="text/javascript" src="/oa_public/common/js/date/WdatePicker.js"></script>
{if isset($msg)}
<div class="header">
	<div class="Huialert Huialert-danger"><i class="icon-remove"></i>{$msg}</div>
</div>
{/if}
<div class="pd-20">
  <p>亚盘数据收集</p>
	<form name="form1" class="Huiform" action="{formatUrl('pan/asia')}" method="get">	    
		<table class="table table-border table-bordered table-bg">
			<tbody>
				<tr>
          		     <th class="text-r" width="180">日期：</th>
          			 <td><input name="d" type="text" class="input-text dd" value="" readonly onfocus="WdatePicker()"></td>
        		</tr>       		
        		<tr>
          			<th></th>
          			<td>
            			<button type="submit" class="btn btn-success radius"><i class="icon-ok"></i> 确定</button>
          			</td>
        		</tr>
        	</tbody>
		</table>
	</form>
</div>
<div class="pd-20">
  <p>欧指数据收集</p>
	<form name="form2" class="Huiform" action="{formatUrl('pan/euro')}" method="get">	    
		<table class="table table-border table-bordered table-bg">
			<tbody>
				<tr>
          		     <th class="text-r" width="180">日期：</th>
          			 <td><input name="d" type="text" class="input-text dd" value="" readonly onfocus="WdatePicker()"></td>
        		</tr>       		
        		<tr>
          			<th></th>
          			<td>
            			<button type="submit" class="btn btn-success radius"><i class="icon-ok"></i> 确定</button>
          			</td>
        		</tr>
        	</tbody>
		</table>
	</form>
</div>
<div class="pd-20">
  <p>球赛预测</p>
	<form name="form3" class="Huiform" action="{formatUrl('pan/gameCalculate')}" method="get">	    
		<table class="table table-border table-bordered table-bg">
			<tbody>
				<tr>
          		     <th class="text-r" width="180">球赛：</th>
          			 <td><input name="g" type="text" class="input-text" value=""></td>
        		</tr>       		
        		<tr>
          			<th></th>
          			<td>
            			<button type="submit" class="btn btn-success radius"><i class="icon-ok"></i> 确定</button>
          			</td>
        		</tr>
        	</tbody>
		</table>
	</form>
</div>
</body>
</html>
