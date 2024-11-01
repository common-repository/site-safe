<?php

$wpss_file_id = sanitize_text_field($_GET['id']);

if(!file_exists(Site_Safe::get_content_dir().'/wpss-downloads/'.$wpss_file_id.'.json')){
	die('404');
}

$fileinfo = file_get_contents(Site_Safe::get_content_dir().'/wpss-downloads/'.$wpss_file_id.'.json');
$fileinfo = json_decode($fileinfo, true);

//print_r($fileinfo);die;
//die(realpath(plugin_dir_path(__FILE__).'../..'));

$plugin_dir = realpath(plugin_dir_path(__FILE__).'../..');

if(in_array($fileinfo['extension'], ['jpg', 'jpeg', 'gif', 'webp', 'png'])){
	header('content-type: '.$fleinfo['type']);
	echo file_get_contents(Site_Safe::get_content_dir().'/wpss-downloads/'.$wpss_file_id);
}else if(file_exists($plugin_dir.'/includes/icons/ext/'.$fileinfo['extension'].'.png')){
	header('content-type: image/png');
	echo file_get_contents($plugin_dir.'/includes/icons/ext/'.$fileinfo['extension'].'.png');
}else{
	echo file_get_contents($plugin_dir.'/includes/icons/ext/no-preview.png');
}