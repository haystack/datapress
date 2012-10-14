(function() {
	tinymce.create('tinymce.plugins.dpEditData', {

		init : function(ed, url) {
			ed.onMouseUp.add(function(ed, e) {
				n = e.target;
				if ( ed.dom.getAttrib(n, 'class') == 'dp_editdata' ) {		
					vp = ed.dom.getViewPort(ed.getWin());
           			p1 = tinymce.DOM.getPos(ed.getContentAreaContainer());
        			p2 = ed.dom.getPos(n);
		            X = Math.max(p2.x - vp.x, 0) + p1.x;
		            Y = Math.max(p2.y - vp.y, 0) + p1.y;    

				    jQuery.fn.contextMenu.displayfuncs[ed.dom.getAttrib(n, 'displayid')](X, Y);
				} else {
				    jQuery.fn.contextMenu.hide();
				}
			});

		},

		getInfo : function() {
			return {
				longname : 'Edit Data',
				author : 'DataPress',
				authorurl : 'http://projects.csail.mit.edu/datapress',
				infourl : '',
				version : "1.0"
			};
		}
	});

	tinymce.PluginManager.add('dpeditdata', tinymce.plugins.dpEditData);
})();
