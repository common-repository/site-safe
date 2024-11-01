(function( $ ) {
	'use strict';

	$(document).on('change', '#wpss-file', function(){
		var $wrap = $(this).closest('.wpss-file-attachment');
		var $progress = $wrap.find('.attch-progress');
		var $progress_bar = $wrap.find('.attch-progress-bar');
		
		var formData = new FormData();
		
		var ajax = new XMLHttpRequest();
		
		ajax.onreadystatechange = function () {

			if (ajax.status) {

				if (ajax.status == 200 && (ajax.readyState == 4)){
					//To do tasks if any, when upload is completed
				}
			}
		}
		
		ajax.upload.addEventListener("progress", function (event) {
			var percent = (event.loaded / event.total) * 100;
			
			$progress_bar.css({width: percent+'%'});
		});

		ajax.open("POST", 'your file upload link', true);
		ajax.send(formData);
		
	});
	
	
	
	$(function() {

    // preventing page from redirecting
    $(".upload-area").on("dragover", function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).find("h1").text("Drag here");
    });

    $(".upload-area").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });

    // Drag enter
    $('.upload-area').on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).find("h1").text("Drop");
    });

    // Drag over
    $('.upload-area').on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).find("h1").text("Drop");
    });

    // Drop
    $('.upload-area').on('drop', function (e) {
        e.stopPropagation();
        e.preventDefault();

        $(this).find("h1").text("Upload");

        var file = e.originalEvent.dataTransfer.files;
        var fd = new FormData();

        fd.append('file', file[0]);

        uploadData(fd, file[0]);
    });

    // Open file selector on div click
    $("#uploadfile").click(function(){
        $("#file").click();
    });

    // file selected
    $("#file").change(function(){
        var fd = new FormData();

        var file = $('#file')[0].files[0];

        fd.append('file',file);

        uploadData(fd, file);
    });
});



// Sending AJAX request and upload file
function uploadData(formData, file){
	var $wrap = $('.wpss-file-attachment');
	var $upload_wrap = $wrap.find('.attch-none');
	var $progress_wrap = $wrap.find('.attch-progress');
	var $progress_bar = $wrap.find('.attch-progress-bar');
	
	formData.append('action', 'wpss');
	
	console.log(file);
	
	var ajax = new XMLHttpRequest();
	
	ajax.onreadystatechange = function(){
		if (ajax.status) {
			if (ajax.status == 200 && (ajax.readyState == 4)){
				//alert(file.name);
				
				
				var response = JSON.parse(ajax.response);
				
				console.log(response);
				
				$wrap.find('.attch-info-name').text(response.fileinfo.name);
				$wrap.find('.attch-info-size').text(response.fileinfo.size);
				$wrap.find('.attch-file-id').val(response.fileinfo.id);
				
				$wrap.find('.attch-info').show();
				$wrap.find('.attch-progress').hide();
				
			}
		}
	};
	
	ajax.upload.addEventListener("progress", function (event) {
		$progress_wrap.show();
		$wrap.find('.attch-info').hide();
		//$upload_wrap.hide();
		
		var percent = (event.loaded / event.total) * 100;
		
		$progress_bar.css({width: percent+'%'});
	});

	ajax.open("POST", '/wp-admin/admin-ajax.php?fn=upload', true);
	ajax.send(formData);

	/*
    $.ajax({
        url: 'upload.php',
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response){
            addThumbnail(response);
        }
    });
	*/
}



// Added thumbnail
function addThumbnail(data){
    $("#uploadfile h1").remove(); 
    var len = $("#uploadfile div.thumbnail").length;

    var num = Number(len);
    num = num + 1;

    var name = data.name;
    var size = convertSize(data.size);
    var src = data.src;

    // Creating an thumbnail
    $("#uploadfile").append('<div id="thumbnail_'+num+'" class="thumbnail"></div>');
    $("#thumbnail_"+num).append('<img src="'+src+'" width="100%" height="78%">');
    $("#thumbnail_"+num).append('<span class="size">'+size+'<span>');

}

// Bytes conversion
function convertSize(size) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (size == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(size) / Math.log(1024)));
    return Math.round(size / Math.pow(1024, i), 2) + ' ' + sizes[i];
}
	
})( jQuery );





