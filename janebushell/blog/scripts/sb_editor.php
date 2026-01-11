
<script type="text/javascript">
	<!--
	
	// Insert Style Tags
	function ins_styles(theform,sb_code,prompt_text,tag_prompt) {
		// Insert [x]yyy[/x] style markup
		
		// Get selected text
		var selected_text = getSelectedText(theform);
		
		if (selected_text == '') {
			// Display prompt if no text is selected
			var inserttext = prompt( '<?php echo( $lang_string[ 'insert_styles' ] ); ?>'+"\n["+sb_code+"]xxx[/"+sb_code+"]", '' );
			if ( (inserttext != null) ) {
				insertAtCaret(theform, "["+sb_code+"]"+inserttext+"[/"+sb_code+"]");
				theform.focus();
			}
		} else {
			// Insert text automatically around selection
			insertAtCaret(theform, "["+sb_code+"]"+selected_text+"[/"+sb_code+"]");
			theform.focus();
		}
	}
	
	// Insert Style Tags
	function ins_style_dropdown(theform, sb_code) {
		// Insert [sb_code]xxx[/sb_code] style markup
		
		if ( sb_code != '-'+'-' ) {
			// Get selected text
			var selected_text = getSelectedText(theform);
		
			if (selected_text == '') {
				prompt_text = '[' + sb_code + ']xxx[/' + sb_code + ']';
				user_input = prompt( prompt_text, '' );
				if ( (user_input != null) ) {
					insertAtCaret(theform, '['+sb_code+']'+user_input+'[/'+sb_code+']');
					theform.focus();
				}
			} else {
				// Insert text automatically around selection
				insertAtCaret(theform, "["+sb_code+"]"+selected_text+"[/"+sb_code+"]");
				theform.focus();
			}				
		}
	}
	
	// Insert Image Tag
	function ins_image(theform,prompt_text) {
		// Insert [x]yyy[/x] style markup
		inserttext = prompt('<?php echo( $lang_string[ 'insert_image' ] ); ?>'+"\n[img="+prompt_text+"xxx]",prompt_text);
		if ((inserttext != null) && (inserttext != "")) {
			insertAtCaret(theform, "[img="+inserttext+"]");
		}
		theform.focus();
	}
	
	// Insert Image Tag
	function ins_image_v2(theform) {
		image_url = prompt('<?php echo( $lang_string[ 'insert_image' ] ); ?>'+'\n[img=http://xxx] or [img=xxx]\n\n<?php echo( $lang_string[ 'insert_image_optional' ] ); ?>\nwidth=xxx height=xxx popup=true/false float=left/right','http://');
		if ((image_url != null) && (image_url != '')) {
			// Optional
			image_width = prompt('<?php echo( $lang_string[ 'insert_image_width' ] ); ?>'+'\n[img=xxx width=xxx]','');
			image_height = prompt('<?php echo( $lang_string[ 'insert_image_height' ] ); ?>'+'\n[img=xxx height=xxx]','');
			image_popup = prompt('<?php echo( $lang_string[ 'insert_image_popup' ] ); ?>'+'\n[img=xxx popup=true/false]', '');
			image_float = prompt('<?php echo( $lang_string[ 'insert_image_float' ] ); ?>'+'\n[img=xxx float=left/right]','');
			
			str = '[img='+image_url;
			if ((image_width != null) && (image_width != '')) {
				str += ' width='+image_width;
			}
			if ((image_height != null) && (image_height != '')) {
				str += ' height='+image_height;
			}
			if ((image_popup != null) && (image_popup != '')) {
				image_popup.toLowerCase;
				if ( image_popup == 'true' || image_popup == 'false' ) {
					str += ' popup='+image_popup;
				}
			}
			if ((image_float != null) && (image_float != '')) {
				image_float.toLowerCase;
				if ( image_float == 'left' || image_float == 'right' ) {
					str += ' float='+image_float;
				}
			}
			str += ']';
			
			insertAtCaret(theform, str);
			theform.focus();
		
		}
	}
	
	// Insert Image Dropdown Menu
	function ins_image_dropdown(theform,theImage) {
		if (theImage.value != '-'+'-') {
			insertAtCaret(theform, theImage.value);
			theform.focus();
		}
	}
	
	// Insert URL Tag
	function ins_url(theform) {
	
		// inserts named url link - [url=mylink new=true]text[/url]
		link_url = prompt('<?php echo( $lang_string[ 'insert_url2' ] ); ?>'+'\n[url=xxx][/url]',"http://");
		if ( (link_url != null) ) {
		
			// Get selected text
			var link_text = getSelectedText(theform);
			if (link_text == '') {
				// Display prompt if no text is selected
				link_text = prompt('<?php echo( $lang_string[ 'insert_url1' ] ); ?>'+'\n[url=]xxx[/url]',"");
			}
			if ( (link_text == null) || (link_text == '') ) {
				link_text = link_url;
			}
			link_target = prompt('<?php echo( $lang_string[ 'insert_url3' ] ); ?>'+'\n[url= new=true/false][/url]','');
			str = '[url='+link_url;
			if ((link_target != null) && (link_target != '')) {
				link_target.toLowerCase;
				if ( link_target == 'true' || link_target == 'false' ) {
					str += ' new='+link_target;
					
				}
			}
			str += ']'+link_text+'[/url]';
			
			insertAtCaret(theform, str);
			theform.focus();
		}
	}
	
	// Insert URL Tag
	function ins_url_no_options(theform) {
		// inserts named url link - [url=mylink new=true]text[/url]
		link_url = prompt('<?php echo( $lang_string[ 'insert_url2' ] ); ?>'+'\n[url=xxx][/url]',"http://");
		if ( (link_url != null) ) {
			// Get selected text
			var link_text = getSelectedText(theform);
			if (link_text == '') {
				// Display prompt if no text is selected
				link_text = prompt('<?php echo( $lang_string[ 'insert_url1' ] ); ?>'+'\n[url=]xxx[/url]',"");
			}
			if ( (link_text == null) || (link_text == '') ) {
				link_text = link_url;
			}
			str = '[url='+link_url+']'+link_text+'[/url]';
			
			insertAtCaret(theform, str);
			theform.focus();
		}
	}
	
	//Insert Emoticon
	function ins_emoticon(theform, emoticon) {
		insertAtCaret(theform, emoticon);
		theform.focus();
	}
	
	// Validate the Form
	function validate(theform) {
		if (theform.blog_text.value=="" || theform.blog_subject.value=="") {
			alert("<?php echo( $lang_string[ 'form_error' ] ); ?>");
			return false;
		} else {
			return true;
		}
	}
	
	// Validate the Form
	function validate_static(theform) {
		if (theform.blog_text.value=="" || theform.blog_subject.value=="" || theform.file_name.value=="" ) {
			alert("<?php echo( $lang_string[ 'form_error' ] ); ?>");
			return false;
		} else {
			return true;
		}
	}

	// From:
	// http://parentnode.org/javascript/working-with-the-cursor-position/
	function insertAtCaret2(obj, text) {
		if (document.selection && document.selection.createRange) {
			// Internet Explorer 4.0x
			
			obj.focus();
			var orig = obj.value.replace(/\r\n/g, "\n"); // IE Bug
			var range = document.selection.createRange();
	
			if(range.parentElement() != obj) {
				return false;
			}
	
			range.text = text;
			
			var actual = tmp = obj.value.replace(/\r\n/g, "\n");
	
			for(var diff = 0; diff < orig.length; diff++) {
				if(orig.charAt(diff) != actual.charAt(diff)) break;
			}
	
			for(var index = 0, start = 0; 
				tmp.match(text.toString()) 
					&& (tmp = tmp.replace(text.toString(), "")) 
					&& index <= diff; 
				index = start + text.toString().length
			) {
				start = actual.indexOf(text.toString(), index);
			}
		} else if (obj.selectionStart >= 0) {
			// FireFox & Safari
			var start = obj.selectionStart;
			var end   = obj.selectionEnd;
	
			obj.value = obj.value.substr(0, start) 
				+ text 
				+ obj.value.substr(end, obj.value.length);
		}
		
		if (start != null) {
			setCaretTo(obj, start + text.length);
		} else {
			obj.value += text;
		}
	}
	
	function getSelectedText(obj) {
		if (document.selection && document.selection.createRange) {
			// Internet Explorer 4.0x
			
			obj.focus();
			var orig = obj.value.replace(/\r\n/g, "\n"); // IE Bug
			var range = document.selection.createRange();
			
			if (range.parentElement() != obj) {
				return '';
			}
			
			txt = range.text;
			
			return txt;
		} else if (obj.selectionStart >= 0) {
			// FireFox & Safari
			var start = obj.selectionStart;
			var end    = obj.selectionEnd;
			var txt    = obj.value.substr(start, end-start);
			
			return txt;
		} else {
			return '';
		}
	}
	
	function setCaretTo(obj, pos) {
		if(obj.createTextRange) {
			var range = obj.createTextRange();
			range.move('character', pos);
			range.select();
		} else if(obj.selectionStart) {
			obj.focus();
			obj.setSelectionRange(pos, pos);
		}
	}
	
	function getSel() {
		var txt = '';
		var foundIn = '';
		if (window.getSelection) {
			// the alternative code
			txt = window.getSelection();
			foundIn = 'window.getSelection()';
		} else if (document.getSelection) {
			// Navigator 4.0x
			txt = document.getSelection();
			foundIn = 'document.getSelection()';
		} else if (document.selection) {
			// Internet Explorer 4.0x
			txt = document.selection.createRange().text;
			foundIn = 'document.selection.createRange()';
		} else {
			return;
		}
		return txt;
	}

	function insertAtCaret(obj, text) {
		var mytext;
		obj.focus();
		
		if (document.selection) {
			// 'Code For IE'
			text = ' ' + text + ' ';
			if (obj.createTextRange && obj.caretPos) {
				var caretPos = obj.caretPos;
				caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
				return;
			}
		} else if (obj.selectionStart!==false) {
			// 'Code for Gecko'
			var start = obj.selectionStart;
			var end   = obj.selectionEnd;
			
			obj.value = obj.value.substr(0, start) + text + obj.value.substr(end, obj.value.length);
		}
		
		if (start != null) {
			setCaretTo(obj, start + text.length);
		} else {
			obj.focus();
			obj.value += text;
		}
	}

	// Insert at Caret position. Code from
	// http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130
	function storeCaret(textEl) {
		if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
	}

	// Validate the Form for Blocks
	function validate_block(theform) {
		if (theform.text.value=="" || theform.block_name.value=="") {
			alert("<?php echo( $lang_string[ 'form_error' ] ); ?>");
			return false;
		} else {
			return true;
		}
	}
	-->
</script>
