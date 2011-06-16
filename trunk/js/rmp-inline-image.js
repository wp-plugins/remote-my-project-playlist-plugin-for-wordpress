jQuery(function () {
	var full_div = jQuery('#rmp-main-image-**player-div**');
	var thumb_div = jQuery('#rmp-image-thumbs-**player-div**');
    var thumb_ul = jQuery('#rmp-image-thumbs-**player-div** ul');
    var rmp_image = jQuery('#rmp-big-**player-div**');
	
	thumb_div.css('width', '**player-width**');
	full_div.css('width', '**player-width**');
	full_div.css('height', '**player-height**');
	
	var divWidth = thumb_div.width();
 
	thumb_div.css('overflow', 'hidden');
	
	thumb_ul.find('button').click(function (e) {
		var _url = jQuery(this).attr('href');
		var _desc = jQuery(this).attr('title');
		rmp_image.hide();
		rmp_image.attr('src', _url);
		rmp_image.attr('href', _url);
		rmp_image.attr('id', 'rmp-big-image-**player-div**');
		rmp_image.bind('load', function() {
			// A good chunck of this is borrowed from MyImgScale 0.2 http://plugins.jquery.com/files/jquery.myimgscale-0.2.js_.txt
		
			// Create a dummy image here so that we get new values
			// without this we would just get the height and width of the first image loaded
			var t = new Image();
			t.src = rmp_image.attr('src');
			var imgW = t.width,
                imgH = t.height,
                destW = **player-width**,
                destH = **player-height**,
                ratioX, ratioY, ratio, newWidth, newHeight;
            
            // calculate scale ratios
            ratioX = destW / imgW;
            ratioY = destH / imgH;

            ratio = ratioX < ratioY ? ratioX : ratioY;

            // calculate our new image dimensions
            newWidth = parseInt(imgW * ratio, 10);
            newHeight = parseInt(imgH * ratio, 10);
            
            // Set new dimensions to both css and img's attributes
            // and center the image
            rmp_image.css({
                'width': newWidth,
                'height': newHeight,
                'margin-left': Math.floor((destW - newWidth) / 2),
                'margin-top': Math.floor((destH - newHeight) / 2),
                'cursor': 'pointer'
            }).attr({
                'width': newWidth,
                'height': newHeight
            });
            rmp_image.fadeIn();
            jQuery("#rmp-big-image-**player-div**").fancybox();
		});
	});
	
	thumb_ul.find('button:first').trigger('click');
 
	thumb_div.mousemove(function (e) {
		if (thumb_ul.find('li:last-child').length) {
			var ulWidth = thumb_ul.find('li:last-child')[0].offsetLeft + thumb_ul.find('li:last-child').outerWidth();
 
			var left = (e.pageX - thumb_div.offset().left) * (ulWidth - divWidth) / divWidth;
			thumb_div.scrollLeft(left);
		}
	});
});