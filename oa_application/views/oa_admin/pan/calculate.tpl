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
<title>预测</title>
</head>
<body>
<script type="text/javascript" src="/oa_public/common/js/jquery.min.js"></script>
<div class="pd-20 text-c">
  <h3>{$homeTeam}&nbsp;VS&nbsp;{$awayTeam}</h3>  
</div>
<div class="pd-5">
	<p>亚盘预测</p>	    
	<table class="table table-border table-bordered table-bg">
		<tbody>
		    <tr class="text-c">
		        <th rowspan="2">公司</th>
		        <th colspan="3">精确匹配</th>
		        <th colspan="3">初盘末盘水位</th>
		        <th colspan="3">初盘末盘</th>
		        <th colspan="3">末盘和水位</th>
		        <th colspan="3">末盘水位</th>
		        <th colspan="3">末盘</th>
		        <th colspan="3">冷热门</th>
		    </tr>
		    <tr class="text-c">
		        {for $a=0;$a<=6;$a++}
		        <th>赢</th>
		        <th>走</th>
		        <th>输</th>
		        {/for}
		    </tr>
		    {foreach $acal as $k=>$v}
			<tr class="text-c">
	  		     <td rowspan="2">{$ac[$k]}</td>
	  		     {foreach $v as $i=>$n}
	  		     <td>{$n['win']}</td>
	  		     <td>{$n['draw']}</td>
	  		     <td>{$n['lose']}</td>
	  		     {/foreach}
			</tr>
			<tr class="text-c apan">
	  		     {foreach $v as $i=>$n}
	  		     <td>{if $n['total']>0}{round($n['win']/$n['total'],4)*100}%{else}-{/if}</td>
	  		     <td>{if $n['total']>0}{round($n['draw']/$n['total'],4)*100}%{else}-{/if}</td>
	  		     <td>{if $n['total']>0}{round($n['lose']/$n['total'],4)*100}%{else}-{/if}</td>
	  		     {/foreach}
			</tr>
			{/foreach}       		
		</tbody>
	</table>
</div>
<div class="pd-5" style="padding-bottom:30px;">
	<p>欧指预测</p>	    
	<table class="table table-border table-bordered table-bg">
		<tbody>
		    <tr class="text-c">
		        <th rowspan="2">公司</th>
		        <th colspan="3">精确匹配</th>
		        <th colspan="3">末盘指数</th>
		        <th colspan="3">冷热门</th>
		    </tr>
		    <tr class="text-c">
		        {for $a=0;$a<=2;$a++}
		        <th>胜</th>
		        <th>平</th>
		        <th>负</th>
		        {/for}
		    </tr>
		    {foreach $ecal as $k=>$v}
			<tr class="text-c">
	  		     <td rowspan="2">{$ec[$k]}</td>
	  		     {foreach $v as $i=>$n}
	  		     <td>{$n['win']}</td>
	  		     <td>{$n['draw']}</td>
	  		     <td>{$n['lose']}</td>
	  		     {/foreach}
			</tr>
			<tr class="text-c ouzhi">
	  		     {foreach $v as $i=>$n}
	  		     <td>{if $n['total']>0}{round($n['win']/$n['total'],4)*100}%{else}-{/if}</td>
	  		     <td>{if $n['total']>0}{round($n['draw']/$n['total'],4)*100}%{else}-{/if}</td>
	  		     <td>{if $n['total']>0}{round($n['lose']/$n['total'],4)*100}%{else}-{/if}</td>
	  		     {/foreach}
			</tr>
			{/foreach}       		
		</tbody>
	</table>
</div>
<script type="text/javascript">
$(function(){
    var afpt=0,aept=0,efpt=0,eept=0;
    $(".apan>td").each(function(){
        var pct = $(this).html();
        if(parseFloat(pct)>parseFloat("50%")){
            $(this).css({
              "color":"#fff",
			  "background-color":"#FD6767",
            });
            afpt++;
        }
        if(parseFloat(pct)>parseFloat("80%")){
            $(this).css({
              "color":"#fff",
			  "background-color":"#920202",
            });
            aept++;
        }
    });
    $(".ouzhi>td").each(function(){
        var pct = $(this).html();
        if(parseFloat(pct)>parseFloat("50%")){
            $(this).css({
              "color":"#fff",
			  "background-color":"#FD6767",
            });
            efpt++;
        }
        if(parseFloat(pct)>parseFloat("80%")){
            $(this).css({
              "color":"#fff",
			  "background-color":"#920202",
            });
            eept++;
        }
    });
})
</script>
</body>
</html>
