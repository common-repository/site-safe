<?php
$tab = sanitize_text_field(@$_GET['plugin-page']);
?>

<div class="wrap">
	<h1 class="wp-heading-inline">Settings</h1>
	<?php include(__DIR__.'/includes/settings-tabs.php');?>
	
	<br/>
	<br/>
	
	<table class="form-table" role="presentation">
		<tbody>
			<tr>
				<th scope="row"><label for="blogname">Send Email Notifications To</label></th>
				<td><input name="blogname" type="text" id="blogname" value="luell9000@gmail.com" class="regular-text"></td>
			</tr>
		</tbody>
	</table>
	
</div>