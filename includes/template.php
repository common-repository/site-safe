<?php

$wpss_file_id = get_post_meta($post->ID, 'wpss_file_id', true);

$wpss_file_downloads = get_post_meta($post->ID, 'wpss_file_downloads', true);
if(empty($wpss_file_downloads)){
    $wpss_file_downloads = 0;
}

$fileinfo = file_get_contents(WP_CONTENT_DIR.'/wpss-downloads/'.$wpss_file_id.'.json');
$fileinfo = json_decode($fileinfo, true);

//print_r($fileinfo);

//echo $wpss_file_id;

$revs = wp_get_post_revisions($post->ID);

/*
foreach($revs as $rev){
    print_r($rev);
    echo $rev->ID;
    $wpss_file_id_rev = get_post_meta($rev->ID, 'wpss_file_id', true);
    echo $wpss_file_id.'==';
    print_r($wpss_file_id_rev[0]);
}
*/

$rev_id = sanitize_text_field(@$_GET['rev']);
if($revs[$rev_id]){
    $rev = $revs[$rev_id];
    
    $wpss_file_id = get_post_meta($rev->ID, 'wpss_file_id', true);
    $wpss_file_id = $wpss_file_id[0];
    
    $wpss_file_downloads = get_post_meta($rev->ID, 'wpss_file_downloads', true);
    $wpss_file_downloads = $wpss_file_downloads[0];
    if(empty($wpss_file_downloads)){
        $wpss_file_downloads = 0;
    }

    $fileinfo = file_get_contents(WP_CONTENT_DIR.'/wpss-downloads/'.$wpss_file_id.'.json');
    $fileinfo = json_decode($fileinfo, true);
    
    $content = $rev->post_content;
}

?>
<style>
    .tsl-preview .file-preview { border: 1px solid rgba(0,0,0,.125); border-bottom: 0; padding: 20px; border-radius: 4px; text-align: center; }
    .tsl-preview .file-preview img { margin-bottom: 20px; border-radius: 4px; width: 100%; }
    /**width: 150px;
    border: 1px solid #eee;
    padding: 20px;
    margin-bottom: 40px;
    margin-top: 40px;
    **/
    .tsl-preview .file-details>div { border: 1px solid rgba(0,0,0,.125); padding: 10px 20px; border-bottom: 0; display: flex; justify-content: space-between; }
    .tsl-preview .file-details>div>span { font-size: 13px; }
    .tsl-preview .file-details>div:nth-child(7) { border-bottom: 1px solid rgba(0,0,0,.125); border-bottom-left-radius: 4px; border-bottom-right-radius: 4px; }
    .tsl-preview .file-content { margin-top: 40px; }
    .tsl-preview ul { list-style-type: none; margin: 0; }
    .tsl-preview p { margin-bottom: 0 !important; }
    .tsl-preview .file-preview-btn {
        background: #0170b9;
        color: #fff;
        padding: 10px;
        width: 100%;
        display: block;
        text-align: center;
        text-decoration: none !important;
        border-radius: 4px;
    }
    .tsl-preview .tsl-badge { font-weight: 700; }
</style>

<div class="tsl-preview"><!-- TSL Template: Default Template -->
    <div class="file-preview">
        <?php if($fileinfo['extension'] == 'mp4'){ ?>
        
            <video width="100%" height="auto" controls>
                <source src="<?php file_get_contents(WP_CONTENT_DIR.'/wpss-downloads/'.$wpss_file_id); ?>" type="video/mp4"/>
                Your browser does not support the video tag.
            </video>
        
        <?php }else{ ?>

            <img src="<?php echo '/wp-admin/admin-ajax.php?action=wpss&fn=preview&id='.$wpss_file_id; ?>" alt="Preview"/>
            
        <?php } ?>
        
        <a class="file-preview-btn" rel="nofollow" href="/?wpss-download=<?php echo $wpss_file_id; ?>">Download</a>
        
    </div>
    <div class="file-details">
        <div><span>File Name</span><span class="tsl-badge"><?php echo esc_html($fileinfo['name']); ?></span></div>
        <div><span>File Size</span><span class="tsl-badge"><?php echo sitesafe_format_bytes_(esc_html($fileinfo['size'], 2)); ?>B</span></div>
        <div><span>File Type</span><span class="tsl-badge"><?php echo esc_html($fileinfo['type']); ?></span></div>
        <div><span>File Extension</span><span class="tsl-badge"><?php echo esc_html($fileinfo['extension']); ?></span></div>
        <div><span>Download Count</span><span class="tsl-badge"><?php echo esc_html($wpss_file_downloads); ?></span></div>
        <div><span>Create Date</span><span class="tsl-badge"><?php echo date("Y/m/d H:i:s", $fileinfo['created_at']);?></span></div>
        <div><span>Last Updated</span><span class="tsl-badge"><?php echo date("Y/m/d H:i:s", $fileinfo['updated_at']);?></span></div>
    </div>

    <div class="file-content">
        <p><?php echo wp_kses_post($content); ?></p>
    </div>

</div>