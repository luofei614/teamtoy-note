<?php
/*** 
TeamToy extenstion info block  
##name 工作笔记
##folder_name note
##author luofei614
##email upfy@qq.com
##reversion 1
##desp 在工作中突然有个想法，突然有所收获，想写点笔记，但是又没有足够的时间，不能写成详细的日志，先用[工作日志]做个简单的标记吧。 
***/
add_action('UI_DASHBOARD_TODO_CATE_LAST','note_nav');
function note_nav(){
	echo '<li><a href="?c=plugin&a=note" tabindex="-1">工作笔记</a></li>';
}
add_action('PLUGIN_NOTE','note_page');
function note_page(){
	//初始化数据库
	if(!get_data("SHOW TABLES LIKE 'note'")){
		run_sql('CREATE TABLE IF NOT EXISTS `note` (
  			 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  			 `uid` int(11) unsigned NOT NULL,
 			 `content` varchar(255)  NOT NULL,
  			 PRIMARY KEY (`id`),
  			 KEY `uid` (`uid`)
			 ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;');
	}
	//读取列表数据
	$note_list=send_request('note_list',array(),token());
	$ret=json_decode($note_list,true);
	//显示界面
	$data=array('top_title'=>'工作笔记','token'=>token(),'data'=>$ret['data']);
	$GLOBALS['c']='dashboard';//让TUDO菜单为选中状态
	render($data,'web','plugin','note');
}
//API
add_action('API_NOTE_LIST','note_list');
function note_list(){
	$data=get_data("select * from note where uid='".uid()."' order by id asc");
	return apiController::send_result($data);
}
add_action('API_NOTE_ADD','note_add');
function note_add(){
	$content=s(t(z(v('content'))));
	echo run_sql("insert into note(uid,content) values('".uid()."','{$content}')")?apiController::send_result(array('id'=>last_id())):apiController::send_error(5001,'note add failed');
}
add_action('API_NOTE_DELETE','note_delete');
function note_delete(){
	$id=intval(v('id'));
	 echo run_sql("delete from note where id='{$id}' and uid='".uid()."'")?apiController::send_result(array()):apiController::send_error(5002,'note delete failed');
}
add_action('API_NOTE_DEL_ALL','note_del_all');
function note_del_all(){
	echo run_sql("delete from note where uid='".uid()."'")?apiController::send_result(array()):apiController::send_error(5003,'note delete failed');

}
