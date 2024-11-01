<?php




if(is_uploaded_file($_FILES['file']['tmp_name'])){
	
	$filename = sitesafe_gen_uuid_v4();
	
	if(!file_exists(WP_CONTENT_DIR.'/wpss-downloads')){
		mkdir(WP_CONTENT_DIR.'/wpss-downloads');
		flush_rewrite_rules();
	}
	
	if(move_uploaded_file($_FILES['file']['tmp_name'], WP_CONTENT_DIR.'/wpss-downloads/'.$filename)){
		//print_r($_FILES);
		
		$fileinfo = [];
		$fileinfo['name'] = sanitize_text_field($_FILES['file']['name']);
		$fileinfo['size'] = sanitize_text_field($_FILES['file']['size']);
		$fileinfo['type'] = sanitize_text_field($_FILES['file']['type']);
		$fileinfo['created_at'] = time();
		$fileinfo['updated_at'] = time();
		
		$extension = explode('.', $_FILES['file']['name']);
		if(count($extension)<2){
			$extension = '';
		}else{
			$extension = $extension[count($extension)-1];
		}
		
		$fileinfo['extension'] = $extension;
		$fileinfo['id'] = $filename;
		
		file_put_contents(WP_CONTENT_DIR.'/wpss-downloads/'.$filename.'.json', json_encode($fileinfo));
		
		//print_r($fileinfo);
		die(json_encode(['success'=>1, 'fileinfo'=>$fileinfo]));
		
	}
	
	
	
}



die(json_encode(['success'=>1]));