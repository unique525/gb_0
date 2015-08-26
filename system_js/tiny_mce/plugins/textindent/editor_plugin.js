/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function() {
	tinymce.create('tinymce.plugins.TextIndent', {
		init : function(ed, url) {
			var t = this;

			t.editor = ed;

			// Register commands
			ed.addCommand('mceTextIndent', function() {
			//���������
			var ed = tinyMCE.activeEditor, d = ed.dom, s = ed.selection, e, iv, iu , st;
 			st= s.getNode().style;
			var allinline=d.select('p');
    			//var bfontSize=document.body.currentStyle?document.body.currentStyle['fontSize']:document.defaultView.getComputedStyle(document.body,false)['fontSize'];
 			//iu=/[a-z%]+$/i.exec(bfontSize);//������ֵĴ�С(pt����px)
     			//iv=parseInt(bfontSize)*2;//�������ֵĿ��
			//alert(iu);alert(iv);
            		//ʵ����ѡȡ����ʱ��������䣬��ѡȡ���������ж��䡣
			if (s.getContent() != "") {
				if (st.textIndent == "" || st.textIndent == "undefined") {
				st.textIndent = '2em';
				}
				else {
				st.textIndent = '';
				}
			}
			else{
				if (st.textIndent == "" || st.textIndent == "undefined") {
				d.setStyle(allinline, 'text-indent', '2em');
				}
				else{
				d.setStyle(allinline, 'text-indent', '');
				}
                             }
			});

			// Register buttons
			ed.addButton('textindent', {title : '首行缩进2字',image : url + '/img/1.gif', cmd : 'mceTextIndent'});
		},

		getInfo : function() {
			return {
				longname : 'indent',
				author : 'hy',
				authorurl : '',
				infourl : '',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}

		// Private methods
	});

	// Register plugin
	tinymce.PluginManager.add('textindent', tinymce.plugins.TextIndent);
})();