<?php


###############################################################################
#  ugm_div(標題,內容，圓角，寬度)
#  圓角
#
#
###############################################################################
if(!function_exists("ugm_div")){
function ugm_div($title="",$data="",$corners="",$width=""){
  $title=empty($title)?"":"<h2> {$title}</h2>";
  if($corners=="shadow"){
    #留白
    $main="
      <div class='Block1Border'><div class='Block1BL'><div></div></div><div class='Block1BR'><div></div></div><div class='Block1TL'></div><div class='Block1TR'><div></div></div><div class='Block1T'></div><div class='Block1R'><div></div></div><div class='Block1B'><div></div></div><div class='Block1L'></div><div class='Block1C'></div><div class='Block1'>{$title}
            <div class='Block1ContentBorder'>{$data}
            </div>
        </div></div>
    ";
  }elseif($corners=="shadow1"){
    $main="
      <div class='Block1Border'><div class='Block1BL'><div></div></div><div class='Block1BR'><div></div></div><div class='Block1TL'></div><div class='Block1TR'><div></div></div><div class='Block1T'></div><div class='Block1R'><div></div></div><div class='Block1B'><div></div></div><div class='Block1L'></div><div class='Block1C'></div><div class='Block1' style='padding:3px 3px 3px 3px;'>{$title}
            <div class='Block1ContentBorder'>{$data}
            </div>
        </div></div>
    ";
  }else{
    $main="<div class='BlockBorder'><div class='BlockBL'><div></div></div><div class='BlockBR'><div></div></div><div class='BlockTL'></div><div class='BlockTR'><div></div></div><div class='BlockT'></div><div class='BlockR'><div></div></div><div class='BlockB'><div></div></div><div class='BlockL'></div><div class='BlockC'></div><div class='Block'>\n
    {$data}\n
    </div></div>\n";
   //$main=empty($title)? $main:"<span class='title'>{$title}</span>".$main; style='background-color: #fff131;color:#0003dc;height:180px;'
    $main=$title.$main;
  }
  if(!empty($width)){
    $main="<div style='width:{$width}px;'>{$main}</div>";
  }
  return $main;
}
}

if(!function_exists("ugm_javascript")){
function ugm_javascript($op=0,$tag=".ugm_tb"){
	//$op=0  $op=1 隔行變色
  $change_line=($op==0)?"":"
     $('{$tag} tr:odd').addClass('oddalt'); //給class為ugm_tb的表格的奇數行添加class值為oddalt
     $('{$tag} tr:even').addClass('alt'); //給class為ugm_tb的表格的偶數行添加class值為alt
	";
	if($tag!=".ugm_tb"){
    $style="
    <style>
    /************************** ugm 表格*******************************************/
    {$tag}{margin: 0;width: 100%;border: none;border-collapse: separate /*collapse*/;border-spacing: 1px;border-image: initial;}
    {$tag} img{vertical-align:middle;}
    {$tag} input{font-size:16px;}
    /*.ugm_tb div{float : left}
    .ugm_tb th{background-image:none;background-color: #B5CBE6;padding:5px;font-size:16px;text-align:center;color: #039;height:26px;} */
    {$tag} th{padding: 5px;	border-bottom:1px solid rgb(192,192,192);	background-color: rgb(64,64,64);	text-align:center; 	vertical-align: middle;	color:#ffffff;}
    {$tag} td{padding:5px;font-size:12px;vertical-align: middle;}
    {$tag} span{color: Red;}
    {$tag} td a{text-decoration: none;}
    {$tag} td a:hove{text-decoration: text-decoration: underline;;}
    {$tag} textarea{}
    {$tag} th.bar{text-align:center;clear: both;}
    {$tag} td.align_c{text-align:center;}
    {$tag} .bar{width: 100%;text-align:center;background: #fff;margin-top: 6px;}
    {$tag} span.title{margin:0;padding:5px;width:98%;font-size:16px;text-align:center;color: #039;}
    {$tag} tr.level_0{background-color: #eed2c9;font-size:12px;}
    {$tag} tr.level_1,tr.level_3,tr.level_5{background-color: #eeeeee;font-size:12px;}
    {$tag} tr.level_2,tr.level_4,tr.level_6{background-color: #e0ffb2;font-size:12px;}
    {$tag} td.align_c{text-align:center;}
    {$tag} td.align_r{text-align:right;}
    {$tag} tr.oddalt{background-color: #eee;}
    {$tag} tr.alt{background-color: #ddd;}
    {$tag} tr.over{background-color: #BDF5BF;font-size:12px;} /*over*/
    {$tag} tr.level_1 td.ugm_indent{text-indent:16pt;}
    {$tag} tr.level_2 td.ugm_indent{text-indent:32pt;}
    {$tag} tr.level_3 td.ugm_indent{text-indent:48pt;}
    {$tag} tr.level_4 td.ugm_indent{text-indent:64pt;}
    {$tag} tr.level_5 td.ugm_indent{text-indent:80pt;}
    {$tag} tr.level_6 td.ugm_indent{text-indent:96pt;}
    </style>";
  }
  $main="
    <script language='javascript'>
      $(function(){
      	//--------------table隔行變色--------------------------------
      	$('{$tag} tr').mouseover(function(){ //如果鼠標移到class為ugm_tb的表格的tr上時，執行函數
        $(this).addClass('over');}).mouseout(function(){ //給這行添加class值為over，並且當鼠標一出該行時執行函數
        $(this).removeClass('over');}) //移除該行的class
        {$change_line}
      	//-----------------------------------------------------------
      });
    </script>
    {$style}
  ";
  return $main;
}
}
?>
