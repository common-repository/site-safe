
<div class="wpss-file-attachment">

	<div class="attch-info" style="display: <?php echo empty($fileinfo['name'])?'none':'block';?>;">
		<div>
			<i class="fa fa-paperclip"></i> <span class="attch-info-name"><?php echo empty($fileinfo['name'])?'':$fileinfo['name'];?></span>
		</div>
		<input type="hidden" class="attch-file-id" name="wpss_file_id" />
	</div>
	
	<div class="attch-progress" style="display: none;">
		<div style="color: white; background: #fafafa; width: 100%; height: 15px; position: relative; border: 1px solid #ccc;">
			<div class="attch-progress-bar" style="color: white; background: green; width: 0%; position: absolute; height: 100%;"></div>
		</div>
	</div>
	
	
	<div class="attch-none">
		<input type="file" name="file" id="file">
		<!-- Drag and Drop container-->
		<div class="upload-area"  id="uploadfile">
			<span>Drag and Drop file here</span>
			<span>or</span>
			<button onclick="javascript:return false;">Select files</button>
		</div>
	</div>

	
</div>