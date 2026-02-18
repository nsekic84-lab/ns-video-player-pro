jQuery(document).ready(function($){
    
    function media_upload(button_id, field_id){
        $('#' + button_id).on('click', function(e){
            e.preventDefault(); // sprečava refresh forme

            var custom_uploader = wp.media({
                title: 'Select Media',
                button: { text: 'Select' },
                multiple: false
            }).on('select', function(){
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $('#' + field_id).val(attachment.url);
            }).open();
        });
    }

    media_upload('ns_video_upload', 'ns_video_src');
    media_upload('ns_poster_upload', 'ns_video_poster');

    $('#ns_generate_shortcode').click(function(e){
        e.preventDefault(); // sprečava refresh
        var src = $('#ns_video_src').val();
        if(!src){ alert('Please select a video'); return; }
        var poster = $('#ns_video_poster').val();
        var autoplay = $('#ns_video_autoplay').is(':checked') ? '1' : '0';
        var watermark = $('#ns_video_watermark').is(':checked') ? '1' : '0';
        var color = $('#ns_video_color').val();

        var shortcode = '[ns_video src="'+src+'" poster="'+poster+'" autoplay="'+autoplay+'" watermark="'+watermark+'" color="'+color+'"]';
        $('#ns_generated_shortcode').val(shortcode);
    });
});
