<?php

// 3.5+ media gallery...
add_action( 'wp_enqueue_media', 'mgzc_media_gallery_zero_columns' );
function mgzc_media_gallery_zero_columns(){
	add_action( 'admin_print_footer_scripts', 'mgzc_media_gallery_zero_columns_script', 999);
}
function mgzc_media_gallery_zero_columns_script(){
?>
<script type="text/javascript">
jQuery(function(){
	if(wp.media.view.Settings.Gallery){
		wp.media.view.Settings.Gallery = wp.media.view.Settings.extend({
			className: "collection-settings gallery-settings",
			template: wp.media.template("gallery-settings"),
			render:	function() {
				wp.media.View.prototype.render.apply( this, arguments );
				// Append an option for 0 (zero) columns if not already present...
				var $s = this.$('select.columns');
				if(!$s.find('option[value="0"]').length){
					
					$s.find('option[value="1"]').remove();
					$s.find('option[value="2"]').remove();
					$s.find('option[value="3"]').remove();
					$s.find('option[value="5"]').remove();
					$s.find('option[value="6"]').remove();
					$s.find('option[value="7"]').remove();
					$s.find('option[value="8"]').remove();
					$s.find('option[value="9"]').remove();
				}
				// Select the correct values.
				_( this.model.attributes ).chain().keys().each( this.update, this );
				return this;
			}
		});
	}
});
</script>
<?php
}