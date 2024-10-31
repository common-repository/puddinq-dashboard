(function ($, root, undefined) {

    'use strict';





    /*
     *      DOCUMENT READY
     *
     */

    jQuery(document).ready(function() {

        jQuery('.puddinq-color-picker').each(function() {
            jQuery(this).wpColorPicker({
                // a callback to fire whenever the color changes to a valid color
                change: function (event, ui) {
                    var theColor = ui.color.toString();
                    switch (jQuery(this).attr('id')) {
                        case 'color_background':
                            jQuery('body, #wpwrap').attr('style', 'background: ' + theColor + '!important;');
                            break;
                        case 'color_block':
                            jQuery('body.puddinq-dashboard div.postbox, .puddinq-inputs').attr('style', 'background: ' + theColor + '!important');
                            break;
                        case 'color_heads':
                            jQuery('body.puddinq-dashboard div.postbox > h2, .puddinq-label').attr('style', 'background: ' + theColor + '!important');
                            break;
                        case 'color_text':
                            var ostyle = jQuery('#puddinq_color_background div a').attr('style');
                            jQuery('body, #wpwrap').attr('style', ostyle + 'color: ' + theColor + '!important');
                            break;

                    }
                },
                // a callback to fire when the input is emptied or an invalid color
                clear: function () {
                },
                // hide the color picker controls on load
                hide: true,
                // set  total width
                width: 200,
                // show a group of common colors beneath the square
                // or, supply an array of colors to customize further
                palettes: ['#ffd700', '#FFA500', '#559999', '#99CCFF', '#00c1e8', '#F9DE0E', '#111111', '#EEEEDD']
            });
        });

        jQuery('#puddinq-color-picker-reset').click(function() {
            jQuery('.puddinq-color-picker').each(function () {
                jQuery(this).val(jQuery(this).attr('data-default-color'));
                jQuery(this).parent().prev().attr('style', 'background-color: ' + jQuery(this).attr('data-default-color'));
                console.log(jQuery(this).attr('id'));
                switch (jQuery(this).attr('id')) {
                    case 'color_background':
                        jQuery('body, #wpwrap').attr('style', 'background: ' + jQuery(this).attr('data-default-color') + '!important;');
                        break;
                    case 'color_block':
                        jQuery('body.puddinq-dashboard div.postbox, .puddinq-inputs').attr('style', 'background: ' + jQuery(this).attr('data-default-color') + '!important');
                        break;
                    case 'color_heads':
                        jQuery('body.puddinq-dashboard div.postbox > h2, .puddinq-label').attr('style', 'background: ' + jQuery(this).attr('data-default-color') + '!important');
                        break;
                    case 'color_text':
                        var style = jQuery('body, #wpwrap').attr('style');
                        jQuery('body, #wpwrap').attr('style', style + 'color: ' + jQuery(this).attr('data-default-color') + '!important');
                        break;

                }

            });
        });

        /* select all*/
        $("[id^=select-all-]").change(function() {
            var checkboxes = $(this).closest('.puddinq-right').find(':checkbox');
            if($(this).is(':checked')) {
                checkboxes.prop('checked', true);
            } else {
                checkboxes.prop('checked', false);
            }
        });

        /* hide upload fields*/
        $("#extras_logo, #dashboard_picture").change(function() {
            var field = this.id.split("_").pop().replace("picture", "dash");
            $('#upload-images_' + field).toggle(this.checked)
        }).change();

        /* hide color fields*/
        $("#extras_color").change(function() {
            $('.puddinq-change-colors').toggle(this.checked)
        }).change();



        /* call media oploader */
        var orig_send_to_editor = window.send_to_editor;

        jQuery('.image_button').click(function() {
            var formfield = jQuery(this).prev('input');
            var previewId = jQuery(this).attr('id');
            tb_show('Logo', 'media-upload.php?type=image&amp;tab=library&amp;TB_iframe=true');

            window.send_to_editor = function(html) {
                var regex = /src="(.+?)"/;
                var rslt =html.match(regex);
                var imgurl = rslt[1];
                console.log(previewId);
                jQuery('#show_' + previewId).html('<img src="'+imgurl+'" width="25" />');
                formfield.val(imgurl);
                tb_remove();

                window.send_to_editor = orig_send_to_editor;
            }

            return false;
        });

    });


})(jQuery, this);