<?php
$tab = sanitize_text_field(@$_GET['plugin-page']);

// Fill these out with the values you got from Google
$googleClientID = '1086641662099-7v52fp9f8f2jt3g6gtvramj1b7bdmugh.apps.googleusercontent.com';
$googleClientSecret = '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';
 
// This is the URL we'll send the user to first
// to get their authorization
$authorizeURL = 'https://accounts.google.com/o/oauth2/v2/auth';
 
// This is Google's OpenID Connect token endpoint
$tokenURL = 'https://www.googleapis.com/oauth2/v4/token';
 
// The URL for this script, used as the redirect URL
$baseURL = 'https://jepwptest.luell.net';
	
$_SESSION['state'] = bin2hex(random_bytes(16));
 
$params = array(
'response_type' => 'code',
'client_id' => $googleClientID,
'redirect_uri' => $baseURL,
'scope' => 'https://www.googleapis.com/auth/drive.file',
'state' => 'devlang'
);


$the__link = $authorizeURL.'?'.http_build_query($params);
// Redirect the user to Google's authorization page
header('Location: '.$the__link);

?>

<div class="wrap">
	<h1 class="wp-heading-inline">Settings</h1>
	<?php include(__DIR__.'/includes/settings-tabs.php');?>
	
	<br/>
	<br/>
	
	<div style="text-align: justify;">
		<p style="width: 300px;">
			Dropbox is a cloud storage service that lets you save files online and sync them to your devices. You can use Dropbox links to share files and folders with other people without sending large attachments. Dropbox offers a free plan that includes 2 GB of storage.
			<br/>
			<br/>
			<!--
			<button class="button" onclick="location.href='https://www.dropbox.com/oauth2/authorize?client_id=cw9mqlfd25e32pq&token_access_type=offline&redirect_uri=<?php echo rawurlencode('https://jepwptest.luell.net');?>&response_type=code&state=devlang'">Connect DropBox</button>
			-->
			<button class="button" onclick="location.href='https://jepwptest.luell.net/connect-storage.php?site=<?php echo home_url();?>&key=test&service=DropBox'">Connect DropBox</button>
		</p>
		
		<br/>
		<hr/>
		<br/>
		
		<p style="width: 300px;">
			Google Drive is a free cloud-based storage service that enables users to store and access files online. The service syncs stored documents, photos and more across all of the user's devices, including mobile devices, tablets and PCs.
			<br/>
			<br/>
			<!--
			<button class="button" onclick="location.href='<?php echo $the__link;?>'">Connect Google Drive</button>
			-->
			<button class="button" onclick="location.href='https://jepwptest.luell.net/connect-storage.php?site=<?php echo home_url();?>&key=test&service=GoogleDrive'">Connect Google Drive</button>
		</p>
		
	</div>
</div>