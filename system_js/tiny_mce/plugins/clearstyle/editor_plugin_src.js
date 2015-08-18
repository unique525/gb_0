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
	tinymce.create('tinymce.plugins.clearstyle', {
		init : function(ed, url) {
			var t = this;

			t.editor = ed;

			// Register commands
			ed.addCommand('mceClearStyle', function() {
                //���������
                var ed = tinyMCE.activeEditor, d = ed.dom, s = ed.selection, e, iv, iu , st;
                st= s.getNode().style;
                var html = ed.getContent();
                var bIgnoreFont = true;
                var bRemoveStyles = true;
                var CleanWordKeepsStructure=true;
                html = html.replace(/<o:p>\s*<\/o:p>/g, '') ;
                html = html.replace(/<o:p>.*?<\/o:p>/g, '&nbsp;') ;

                // Remove mso-xxx styles.
                html = html.replace( /\s*mso-[^:]+:[^;"]+;?/gi, '' ) ;

                // Remove margin styles.
                html = html.replace( /\s*MARGIN: 0cm 0cm 0pt\s*;/gi, '' ) ;
                html = html.replace( /\s*MARGIN: 0cm 0cm 0pt\s*"/gi, "\"" ) ;

                html = html.replace( /\s*TEXT-INDENT: 0cm\s*;/gi, '' ) ;
                html = html.replace( /\s*TEXT-INDENT: 0cm\s*"/gi, "\"" ) ;

                html = html.replace( /\s*TEXT-ALIGN: [^\s;]+;?"/gi, "\"" ) ;

                html = html.replace( /\s*PAGE-BREAK-BEFORE: [^\s;]+;?"/gi, "\"" ) ;

                html = html.replace( /\s*FONT-VARIANT: [^\s;]+;?"/gi, "\"" ) ;

                html = html.replace( /\s*tab-stops:[^;"]*;?/gi, '' ) ;
                html = html.replace( /\s*tab-stops:[^"]*/gi, '' ) ;

                // Remove FONT face attributes.
                if ( bIgnoreFont )
                {
                    html = html.replace( /\s*face="[^"]*"/gi, '' ) ;
                    html = html.replace( /\s*face=[^ >]*/gi, '' ) ;

                    html = html.replace( /([\s";])FONT-FAMILY:[^;"]*;?/gi, '$1' ) ;
                }

                // Remove Class attributes
                html = html.replace(/<(\w[^>]*) class=([^ |>]*)([^>]*)/gi, "<$1$3") ;

                html = html.replace( /([\s";])mso-[a-z\-]+:[^;"]*;?/gi, '$1' ) ;
                // Remove styles.
                if ( bRemoveStyles )
                    html = html.replace( /<(\w[^>]*) style="([^\"]*)"([^>]*)/gi, "<$1$3" ) ;

                // Remove empty styles.
                html =  html.replace( /\s*style="\s*"/gi, '' ) ;

                html = html.replace( /<SPAN\s*[^>]*>\s*&nbsp;\s*<\/SPAN>/gi, '&nbsp;' ) ;

                html = html.replace( /<SPAN\s*[^>]*><\/SPAN>/gi, '' ) ;

                // Remove Lang attributes
                html = html.replace(/<(\w[^>]*) lang=([^ |>]*)([^>]*)/gi, "<$1$3") ;

                //	html = html.replace( /<SPAN\s*>(.*?)<\/SPAN>/gi, '$1' ) ;

                //	html = html.replace( /<FONT\s*>(.*?)<\/FONT>/gi, '$1' ) ;

                // Remove XML elements and declarations
                html = html.replace(/<\\?\?xml[^>]*>/gi, '' ) ;

                // Remove Tags with XML namespace declarations: <o:p><\/o:p>
                html = html.replace(/<\/?\w+:[^>]*>/gi, '' ) ;

                // Remove comments [SF BUG-1481861].
                html = html.replace(/<\!--.*-->/g, '' ) ;

                html = html.replace( /<(U|I|STRIKE)>&nbsp;<\/\1>/g, '&nbsp;' ) ;

                html = html.replace( /<H\d>\s*<\/H\d>/gi, '' ) ;

                // Remove "display:none" tags.
                html = html.replace( /<(\w+)[^>]*\sstyle="[^"]*DISPLAY\s?:\s?none(.*?)<\/\1>/ig, '' ) ;
                //HTML链接中可能存在链入本页面的锚点
                html = html.replace(/(<\w+[^>]*\s)href="([^"]*)(#.+)"/ig,
                    function(a0,a1,a2,a3){
                        if(a2.indexOf("/editor/editor/fckeditor.html?InstanceName=TRS_Editor&amp;Toolbar=WCM6")!=-1){
                            return a1+'href="'+a3+'"';
                        }
                        return a0;
                    });
                if (CleanWordKeepsStructure)
                {
                    // The original <Hn> tag send from Word is something like this: <Hn style="margin-top:0px;margin-bottom:0px">
                    html = html.replace( /<H(\d)([^>]*)>/gi, '<h$1>' ) ;

                // Word likes to insert extra <font> tags, when using MSIE. (Wierd).
                //		html = html.replace( /<(H\d)><FONT[^>]*>(.*?)<\/FONT><\/\1>/gi, '<$1>$2<\/$1>' );
                //		html = html.replace( /<(H\d)><EM>(.*?)<\/EM><\/\1>/gi, '<$1>$2<\/$1>' );
                }
                else
                {
                    html = html.replace( /<H1([^>]*)>/gi, '<div$1><b><font size="6">' ) ;
                    html = html.replace( /<H2([^>]*)>/gi, '<div$1><b><font size="5">' ) ;
                    html = html.replace( /<H3([^>]*)>/gi, '<div$1><b><font size="4">' ) ;
                    html = html.replace( /<H4([^>]*)>/gi, '<div$1><b><font size="3">' ) ;
                    html = html.replace( /<H5([^>]*)>/gi, '<div$1><b><font size="2">' ) ;
                    html = html.replace( /<H6([^>]*)>/gi, '<div$1><b><font size="1">' ) ;

                    html = html.replace( /<\/H\d>/gi, '<\/font><\/b><\/div>' ) ;

                    // Transform <P> to <DIV>
                    //		var re = new RegExp( '(<P)([^>]*>.*?)(<\/P>)', 'gi' ) ;	// Different because of a IE 5.0 error
                    //		html = html.replace( re, '<div$2<\/div>' ) ;

                    // Remove empty tags (three times, just to be sure).
                    // This also removes any empty anchor
                    html = html.replace( /<([^\s>]+)(\s[^>]*)?>\s*<\/\1>/g, '' ) ;
                    html = html.replace( /<([^\s>]+)(\s[^>]*)?>\s*<\/\1>/g, '' ) ;
                    html = html.replace( /<([^\s>]+)(\s[^>]*)?>\s*<\/\1>/g, '' ) ;
                }
                //return html ;
                ed.setContent(html);
            });

			// Register buttons
			ed.addButton('clearstyle', {title : '清除（统一）样式',image : url + '/img/1.gif', cmd : 'mceClearStyle'});
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
	tinymce.PluginManager.add('clearstyle', tinymce.plugins.clearstyle);
})();