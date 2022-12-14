( function(){

    // Dialog Object
    // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.dialogDefinition.html
    var channelimages_dialog = function(editor){

        DialogElements = [];
        DialogSizes = [];


        //********************************************************************************* //

        var SelectImage = function(Event){

            if (typeof(Event.target) == 'undefined') return;

            var Target = jQuery(Event.target);

            // Remove all other
            Target.closest('table').find('.CImage').removeClass('Selected');

            Target.closest('.CImage').addClass('Selected');
        };


        //********************************************************************************* //

        return {

            // The dialog title, displayed in the dialog's header. Required.
            title: 'Channel Images',

            // The minimum width of the dialog, in pixels.
            minWidth: '600',

            // The minimum height of the dialog, in pixels.
            minHeight: '400',

            // Buttons
            buttons: [CKEDITOR.dialog.okButton, CKEDITOR.dialog.cancelButton] /*array of button definitions*/,

            // On OK event
            onOk: function(Event){
                var Wrapper = jQuery(CKEDITOR.dialog.getCurrent().definition.dialog.parts.dialog.$).find('.tab-open');

                if ( Wrapper.find('.Selected').length === 0) return;

                var Selected = Wrapper.find('.Selected img');

                var IMGSRC = Selected.attr('src');

                var image_id = Selected.data('image_id');
                var filename = Selected.data('filename');
                var OLDFILENAME = Selected.data('filename');
                var field_id = Selected.data('field_id');
                var image_index = Selected.data('index');

                var dot = filename.lastIndexOf('.');
                var extension = filename.substr(dot,filename.length);

                var Size = Wrapper.find('.sizeholder input[type=radio]:checked').val();

                var output_type = 'image_url';
                if (typeof(ChannelImages.Fields['Field_'+field_id]) != 'undefined') {
                    var settings = ChannelImages.Fields['Field_'+field_id].settings;
                    if (typeof(settings.wysiwyg_output) != 'undefined') {
                        output_type = settings.wysiwyg_output;
                    }
                }

                // Existing Image?
                if (image_id > 0) {
                    var currentFilename = IMGSRC.split('/').pop().split('#').shift().split('?').shift();

                    if (Size != 'original'){
                        var NewName = filename.replace(extension, '__'+Size+extension);
                        IMGSRC = IMGSRC.replace(currentFilename, NewName);
                    }
                    else {
                        IMGSRC = IMGSRC.replace(currentFilename, filename);
                    }
                } else {
                    if (Size != 'original'){
                        var NewName = filename.replace(extension, '__'+Size+extension);
                        IMGSRC = IMGSRC.replace(/f\=(.*?)\&/, 'f='+NewName+'&');
                    }
                    else {
                        IMGSRC = IMGSRC.replace(/f\=(.*?)\&/, 'f='+filename+'&');
                    }
                }



                var imageElement = editor.document.createElement('img');
                imageElement.setAttribute('src', IMGSRC);
                //imageElement.setAttribute('width', ChannelImages.Sizes[Size].width);
                //imageElement.setAttribute('height', ChannelImages.Sizes[Size].height);
                imageElement.setAttribute('alt', Selected.attr('alt'));
                imageElement.setAttribute('class', 'ci-image ci-'+Size);

                if (output_type == 'static_image') {
                    if (Size == 'original') {
                        img = '{image:'+image_index+'}';
                    } else {
                        img = '{image:'+image_index+':'+Size+'}';
                    }

                    editor.insertText(img);

                } else {
                    editor.insertElement( imageElement );
                }

                Selected.parent().removeClass('Selected');
            },

            // On Cancel Event
            onCancel: function(){

                var Wrapper = jQuery(CKEDITOR.dialog.getCurrent().definition.dialog.parts.dialog.$);

                if ( Wrapper.find('.Selected').length === 0) return;
                Wrapper.find('.Selected').removeClass('Selected');

            },

            // On Load Event
            onLoad: function(){},

            // On Show Event
            onShow: function(){

                var HTML = [];
                HTML.push('<div class="has-tabs">');
                HTML.push('<div class="tab-wrap">');

                // Tabs
                HTML.push('<ul class="tabs">');
                for (var FIELD in ChannelImages.Fields){
                    HTML.push('<li><a href="#" rel="t-'+FIELD+'">'+ChannelImages.Fields[FIELD].field_label+'</a></li>');
                }
                HTML.push('</ul>');

                var count = 0;

                for (FIELD in ChannelImages.Fields){
                    count++;

                    HTML.push('<div class="tab t-' + FIELD +'">');


                    if (typeof(ChannelImages.Fields[FIELD].wimages) == 'undefined' || ChannelImages.Fields[FIELD].wimages.length === 0){
                        HTML.push('<p>No images have yet been uploaded.</p>');
                    } else {

                        HTML.push('<div class="imageholder">');
                        for (var i = 0; i < ChannelImages.Fields[FIELD].wimages.length; i++) {
                            var IMG = ChannelImages.Fields[FIELD].wimages[i];
                            HTML.push('<div class="CImage"><img src="'+IMG.big_img_url+'" title="'+IMG.title+'" alt="'+IMG.description+'" data-filename="'+IMG.filename+'" data-field_id="'+IMG.field_id+'" data-image_id="'+IMG.image_id+'" data-index="'+(i+1)+'"></div>');
                        }

                        HTML.push('</div>');

                        HTML.push('<br clear="all">');

                        HTML.push('<div class="sizeholder">');
                        HTML.push('<ul>');

                        var Checked = false;

                        if (ChannelImages.Fields[FIELD].settings.wysiwyg_original == 'yes') {
                            Checked = true;
                            HTML.push('<li><input name="size_'+FIELD+'" type="radio" value="original" checked> ORIGINAL</li>');
                        }

                        if (typeof(ChannelImages.Fields[FIELD].settings.action_groups) != 'undefined'){

                            for (i in ChannelImages.Fields[FIELD].settings.action_groups) {
                                if (ChannelImages.Fields[FIELD].settings.action_groups[i].wysiwyg != 'yes') continue;
                                var CheckText = (Checked === false) ? 'checked' : '';
                                HTML.push('<li><input name="size_'+FIELD+'" type="radio" value="'+ChannelImages.Fields[FIELD].settings.action_groups[i].group_name+'"  '+CheckText+'> '+ChannelImages.Fields[FIELD].settings.action_groups[i].group_name+'</li>');
                                Checked = true;
                            }

                        }

                        HTML.push('</ul>');
                        HTML.push('<br clear="all">');
                        HTML.push('</div>');

                    }

                    HTML.push('</div>');
                }

                HTML.push('</div>');
                HTML.push('</div>');

                HTML.push('<br>');
                jQuery(this.getElement().$).find('.WCI_Images').html(HTML.join(''))
                .find('.CImage').click(SelectImage)
                .closest('.WCI_Images').find('.tabs').find('a:first').click();
            },

            // On Hide Event
            onHide: function(){
                jQuery(this.getElement().$).find('.WCI_Images').attr('class', 'WCI_Images').empty().removeData('tabs');
            },

            // Can dialog be resized?
            resizable: CKEDITOR.DIALOG_RESIZE_NONE,

            // Content definition, basically the UI of the dialog
            contents:
            [
                {
                    id: 'ci_images',  /* not CSS ID attribute! */
                    label: 'Images',
                    className : 'weeeej',
                    elements: [
                    {
                            type : 'html',
                            html : '<p>Please select an image and then your desired image size.</p>'
                        },
                        {
                            type : 'html',
                            html : '<div class="WCI_Images"></div>'
                        },
                        {
                            type : 'html',
                            html : '<br>'
                        }
                    ]
                }
            ]
        };

        //********************************************************************************* //


    };

    // Add the Dialog
    CKEDITOR.dialog.add('channelimages', function(editor) {
        return channelimages_dialog(editor);
    });

})();
