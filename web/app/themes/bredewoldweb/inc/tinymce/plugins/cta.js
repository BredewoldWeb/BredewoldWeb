(function () {

    tinymce.PluginManager.add('cta', function (editor, url) {
        
        /* Add buttons to the menu */
        var $menu = [];
        $menu.push({
            text: 'Cta 1',
            icon: 'br-cta-1',
            onclick: function () {
                var c = editor.selection.getContent();
                if (!c) { c = 'button'; }
                editor.insertContent('<a class="cta cta-1" href="#">' + c + '</a>');
            }
        });

        editor.addButton('cta', {
            text: 'Buttons',
            icon: 'br-buttons',
            type: 'menubutton',
            menu: $menu
        });

    });
 
 })();
 