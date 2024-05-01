jQuery(document).ready(function($) {
    $('#stfq-select-featured-image').on('click', function(e) {
        e.preventDefault();
        var frame = wp.media({
            title: 'Select Default Featured Image',
            multiple: false,
            library: {
                type: 'image'
            },
            button: {
                text: 'Use this image'
            }
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#stfq-featured-image-id').val(attachment.id);
            $('#stfq-featured-image-preview').html('<img src="' + attachment.url + '" style="max-width:100px;height:auto;" /> <br/>');
            $('#stfq-remove-default-image').show();
        });

        frame.open();
    });

    $('#stfq-remove-default-image').on('click', function(e) {
        e.preventDefault();
        $('#stfq-featured-image-id').val('');
        $('#stfq-featured-image-preview').html('No default image selected.');
        $(this).hide();
    });
});
