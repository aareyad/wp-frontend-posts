(function($) {
    'use strict';
	
	$("#wfp-category").select2({
		placeholder: "Select Category",
		multiple: true
	});
	
	$("#wfp-feature-image").on('click',function(e){
		 e.preventDefault();
		 
		var self = $(this);

		var file_frame = wp.media.frames.file_frame = wp.media({
			library: {
				type: ['image']
			}
		});

		file_frame.on('select', function () {
			var images = file_frame.state().get('selection').first().toJSON();
			self.siblings('#wfp-feature-image-id').val(images.id);
			self.parent().prev('.wfp-image-preview').find('img').attr('src', images.sizes.full.url);
		});
		file_frame.open();
	});
	
})(jQuery);