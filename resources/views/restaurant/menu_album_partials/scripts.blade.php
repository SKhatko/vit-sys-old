function loadCategoryPhotos($btn, category) {

	$("#photos-holder").html("");
	$("#photos-loader").show();
	$(".photo-categories-nav li a").removeClass('active');
	$btn.addClass('active');
	
	$.ajax({
		type: "GET",
		url: "{{ url('/restaurant/menu/photo-categories') }}/"+category+"/photos"
	}).done(function(data) {
		
		if (category == 'uploads') {
			var html = '<div class="col-md-12" style="margin-top:10px;">';
			html += '<input type="file" name="menu-bg-upload" style="display:inline-block;" /> ';
			html += '<a href="javascript:;" onclick="uploadBackgroundPhoto();" id="upload-photo-btn" class="btn btn-warning">Upload photo</a>';
			html += '<div id="photo-upload-loader" style="display:none;" class="loader"><img src="{{ asset('img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}..." /></div>';
			html += '</div>';
			
			$("#photos-holder").append(html);
		}
		
		if (data && data.length) {
			for (var i=0; i < data.length; i++) {
				var imgName = data[i];
				var slashPos = imgName.lastIndexOf("/");
				var thumb = imgName.substring(0, slashPos) + "/thumb" + imgName.substring(slashPos);
				
				var html = '<div class="col-md-4 col-sm-4 col-xs-6 photo-col">';
				html += '<div class="photo-holder">';
				html += '<div class="photo-overlay">';
				html += '<a href="javascript:;" onclick="chooseBackground($(this), \''+data[i]+'\');" class="overlay-btn choose-photo-btn">Choose Photo</a>';
				if (category == 'uploads') {
					html += '<a href="javascript:;" onclick="deleteBackground($(this), \''+data[i]+'\');" class="overlay-btn delete-photo-btn">Delete Photo</a>';
				}
				html += '</div>';
				html += '<img src="{{ url('/') }}/'+thumb+'" />';
				html += '</div>';
				html += '</div>';
				
				$("#photos-holder").append(html);
			}
		}
		else {
			$("#photos-holder").append('<div class="col-md-12" style="padding-top:10px;">{{ trans('menu.no_photos_found') }}</div>');
		}
		
		$("#photos-loader").hide();
	});
		
	
}

function chooseBackground($btn, background) {
	$("#menu-image-input").val(background);
	$("#menu-image-input").trigger('change');
	$("#photo-album-dialog").modal('hide');
}

function deleteBackground($btn, background) {
	$("#delete-photo-input").val(background);
	$("#delete-photo-dialog").modal('show');
}

$("#delete-photo-form").submit(function(e) {
	e.preventDefault();
	var $submitBtn = $("#delete-photo-confirm-btn");
	$submitBtn.addClass('disabled');
	$submitBtn.prop('disabled', true);
	
	var data = $(this).serialize();
	
	$.ajax({
		url:	'{{ action('OnlineMenuController@deleteUploadedPhoto') }}',
		type:	'POST',
		data:	data,
	}).done(function(data) {
		
		$("#delete-photo-dialog").modal('hide');
		$submitBtn.removeClass('disabled');
		$submitBtn.prop('disabled', false);
		//hide and show photo album dialog to prevent scroll bug
		$("#photo-album-dialog").modal('hide');
		$("#photo-album-dialog").modal('show');
		$("a[data-category='uploads']").click();
	});
	
	return false;
});

function uploadBackgroundPhoto() {

	var formData = new FormData();
	formData.append('_token', '{{ csrf_token() }}');
	formData.append('menu-bg-upload', $("input[name='menu-bg-upload']").prop('files')[0]);
	
	$("#upload-photo-btn").addClass('disabled');
	$("#upload-photo-btn").prop('disabled', true);
	
	$("#photo-upload-loader").show();
	
	$.ajax({
		url:	'{{ action('OnlineMenuController@uploadBackgroundPhoto') }}',
		type:	'POST',
		processData: false,
		contentType: false,
		dataType: 'json',
		data: formData
	}).progress(function(evt) {
		
	}).success(function(data, status, headers, config) {
		alert('Photo uploaded successfully');
		$("a[data-category='uploads']").click();
	}).error(function(result) {
		alert('Error uploading file');
		$("a[data-category='uploads']").click();
	});
}

$("#photo-album-btn").click(function() {
	$("#photo-album-dialog").modal('show');
});
