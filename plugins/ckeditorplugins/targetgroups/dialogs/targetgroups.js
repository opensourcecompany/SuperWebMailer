
CKEDITOR.dialog.add( 'targetgroupsDialog', function( editor ) {
    return {
        title:          editor.lang.targetgroups.tgroupAssignToTagDialogTitle,
        resizable:      CKEDITOR.DIALOG_RESIZE_NONE,
        minWidth:       400,
        minHeight:      300,
        width:       400,
        height:      300,

        contents: [
            {
                id:         'tab1',
                label:      '',
                title:      '',
                accessKey:  '',


                elements: [
                    {
                        type:  'html',
                        html:  '' +
                               '<p>' + editor.lang.targetgroups.tgroupAssignToTagDialogLabel + '</p><br /><iframe id="__targetgroupsIframe" style="width: 100%; height: 250px" border="0" marginheight="0" marginwidth="0" frameborder="0" src="./ajax_showtargetgroups.php?IsFCKEditor=true&ckCsrfToken=' + CKEDITOR.tools.getCsrfToken() + '"></iframe><br />' +
                               '<a title="' + editor.lang.targetgroups.tgroupCheckAll + '" class="cke_dialog_ui_fileButton cke_dialog_ui_button" id="tg_uiElement1" role="button" aria-labelledby="tg_label1" hidefocus="true" href="javascript:void(0)" unselectable="on" onclick="document.getElementById(\'__targetgroupsIframe\').contentWindow.CheckAll();"><span class="cke_dialog_ui_button" id="tg_label1" unselectable="on">' + editor.lang.targetgroups.tgroupCheckAll + '</span></a>' +
                               '<a title="' + editor.lang.targetgroups.tgroupUnCheckAll + '" class="cke_dialog_ui_fileButton cke_dialog_ui_button" id="tg_uiElement2" role="button" aria-labelledby="tg_label2" hidefocus="true" href="javascript:void(0)" unselectable="on" onclick="document.getElementById(\'__targetgroupsIframe\').contentWindow.UnCheckAll();"><span class="cke_dialog_ui_button" id="tg_label2" unselectable="on">' + editor.lang.targetgroups.tgroupUnCheckAll + '</span></a>'
                    }

                ]
            }
        ],


        onShow: function() {
            var dialog = this;
            var editor = dialog.getParentEditor();
            if(!editor) { dialog.hide(); return;}

            var element = targetgroupsGetSelectedElement(editor);
            if(!element) { dialog.hide(); return;}

            if(CKEDITOR.config._targetgroupsSender == 'ELEMENT'){
              element = element.getAscendant(supported_targetgroups_Elements, true);
              if(!element) { dialog.hide(); return;}
            } else if(CKEDITOR.config._targetgroupsSender == 'TABLE') {
                 element = element.getAscendant('table', true);
                 if(!element) { dialog.hide(); return;}
               }else if(CKEDITOR.config._targetgroupsSender == 'TR') {
                   element = element.getAscendant('tr', true);
                   if(!element) { dialog.hide(); return;}
               }

            if(document.getElementById("__targetgroupsIframe")) {
               try{
                // refresh on always open window
                document.getElementById("__targetgroupsIframe").contentWindow.LoadTargetGroups(element.getAttribute('target_groups'));
               }catch(e){
               }
            }
        },

        onOk: function() {
                    var dialog = this;
                    var editor = dialog.getParentEditor();
                    if(!editor) {return;}
                    var element = targetgroupsGetSelectedElement(editor);
                    if(!element) { return;}

                    if(CKEDITOR.config._targetgroupsSender == 'ELEMENT'){
                      element = element.getAscendant(supported_targetgroups_Elements, true);
                      if(!element) { return;}
                    } else if(CKEDITOR.config._targetgroupsSender == 'TABLE') {
                         element = element.getAscendant('table', true);
                         if(!element) { return;}
                       }else if(CKEDITOR.config._targetgroupsSender == 'TR') {
                           element = element.getAscendant('tr', true);
                           if(!element) { return;}
                       }

                    if(document.getElementById("__targetgroupsIframe")) {
                       var targetgroups = document.getElementById("__targetgroupsIframe").contentWindow.GetCheckedTargetGroups();

                       if(!targetgroups || targetgroups == "") {
                           if(element.getAttribute('target_groups')){
                             editor.fire('saveSnapshot');
                             element.removeAttribute('target_groups');
                             editor.fire('saveSnapshot');
                             editor.fire("blur"); // internal hack for "dirty" check
                           }
                         }
                         else {
                           editor.fire('saveSnapshot');
                           element.setAttribute('target_groups', targetgroups);
                           editor.fire('saveSnapshot');
                           editor.fire("blur"); // internal hack for "dirty" check
                         }

                    }
                },
          onCancel: function() {
          }


    };
});