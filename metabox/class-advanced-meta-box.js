// JavaScript Document

jQuery(document).ready(function($) {
	$("select.rw-taxonomy").live("change",
		function() {
			tax_name = $(this).val();
			$(this).siblings("div.tax-child").addClass("hidden").find("input:checkbox, select").attr("disabled", true);
			$("div#term_"+tax_name).removeClass("hidden").children("input:checkbox, select").removeAttr("disabled");
		}
	);
	$("input:checkbox.rw-taxonomy").live("change",
		function() {
			tax_name = $(this).val();	
			if ($(this).is(':checked')) {
				$(this).siblings("div#term_"+tax_name).removeClass("hidden");
				$(this).siblings("div#term_"+tax_name).children("input:checkbox, select").removeAttr('disabled');
			} else {
				$(this).siblings("div#term_"+tax_name).addClass("hidden");
				$(this).siblings("div#term_"+tax_name).find("input:checkbox, select").attr('disabled', true);
			}
		}
	);
	$('.adv-upload-button').live('click', function(){
		old_tb_remove = window.tb_remove;
		var tb_remove_called = 0;
		var data = $(this).attr("rel");
		var gallery = $(this).closest('td').find('ul.rw-att-img');
		var data = $(this).attr('rel').split('|'),
			post_id = data[0],
			field_id = data[1],
			backup = window.send_to_editor;		// backup the original 'send_to_editor' function which adds images to the editor
		window.tb_remove = function() {
			tb_remove_called++;
			if(tb_remove_called<=1) {
				$.post(ajaxurl,{action: 'advanced_update_images', data: data },function(response) {
					gallery.html(response);
					gallery.sortable(refresh);
				});
			}
			old_tb_remove(); // calls the tb_remove() of the Thickbox plugin
			window.tb_remove = old_tb_remove;
			return false;
		};
		tb_show('', 'media-upload.php?post_id='+post_id+'; ?>&type=image&TB_iframe=true');				
		return false;
	});
	
	//embed code
	$(".veda_view_embed").live("click", function() {
		var data = $(this).parent().siblings(".embed_url").val(),
			nonce = $(this).parent().siblings(".embed_nonce").val(),
			container = $(this).parent().siblings("div.veda_embed");
		$.post(ajaxurl, {action: 'advanced_show_embed', data: data, nonce: nonce}, function(response){
			if (response == "1") {
				alert( "Link Not Embeddible.");
			} else {
				container.html(response);	
			}
		});
		return false;
	});
	
	$(".veda_remove_embed").live("click", function() {
		var	container = $(this).parent().siblings(".veda_embed");
		container.empty();
		return false;
	});
	
	//Text lists
	$('.rw-list-delete').live('click', function() {
		var list = $(this).closest('ul.rw-list'),
		listItem = $(this).closest('li') ;
		listLength = $(list).find('li').length;
		if(listLength < 2) {
			$(listItem).find(":input")
				.not(':button, :submit, :reset, :hidden')
				.val('')
				.removeAttr('checked')
				.removeAttr('selected');	
		} else {
			$(listItem).css({'background-color' : '#ff8d8d'}).fadeOut('slow', function() { 
				$(this).remove();
			});
		}
		return false;
	});
	
	$('.rw-list-add').live('click', function() {
		var list = $(this).siblings('ul.rw-list'),
		cloneItem = $(list)
			.find('li')
			.eq(0)
			.clone();
			
		$(list).append(cloneItem);
		$(cloneItem)
			.find(":input")
			.not(':button, :submit, :reset, :hidden')
			.val('')
			.removeAttr('checked')
			.removeAttr('selected');
		return false;
	});
	
	//Update
	var namespace = function(name, separator, container, val){
		var ns = name.split(separator || '.'),
		o = container || window, i, len;
		for(i = 0, len = ns.length; i < len; i++){
			var v = (i==len-1 && val) ? val : {};
			o = o[ns[i]] = o[ns[i]] || v;
		}
		return o;
	};
	var get_record_data = function () {
		var field_data = {}, 
			parent = $(this), 
			field_names = $(parent).find("input[name = 'rw_field_info[]']").map(function() {
				return $(this).val();
			 }).get(),
			 fields = $(parent).find("input:text, textarea, select, input:radio:checked, input:checkbox:checked, input:hidden").not(':disabled');
			 
		 //Cycle through the field_names
		 $(field_names).each(function() {
			 name = this;
			 field = fields.filter("."+name);
			 if(field.length < 1) return;
			 if(field.attr("name").indexOf('[]') === -1) {
				temp = field.val();
			} else {
				temp = field.map(function() {
					return $(this).val()
				}).get();
				//alert(temp);
			}
			namespace(name,'-',field_data,temp);
		 });
		 field_data['node_id'] = parent.find("input:hidden[name='node_id[]']").val();
		 return field_data;
	};
	
	var clear_data = function(parent) {
		$(parent).find(":input")
		.not(':button, :submit, :reset, :hidden, :checkbox, :radio')
		 .val('').change();
		$(parent).find(":checkbox, :radio").not(':button, :submit, :reset, :hidden')
		 .removeAttr('checked')
		 .removeAttr('selected').change();

	};
	
	$("a.clear_data").live("click", function() {
		data = $(this).closest("table");
		$(data).each(function() {
			clear_data(this);
		});
		return false;
	});
	
	$("a.update_data").live("click", function () {
		var data = {},
		temp = $(this).attr("rel").split('|'),
		node = $(this).closest("table");
		data = $(node).map(get_record_data).get();
		$.post(ajaxurl,	{action: temp[0], 
				nonce: temp[1], 
				data: data, 
				post_ID: temp[2], 
				post_type: temp[3]},	
		function(response){
			if(response == -1) {
				alert("Error updating data");	
			} else {
				alert(response);	
			}
		});
		return false;
	});
	
	$("a.rw-add-new-term").live('click', function() {
		var temp = $(this).attr("rel").split('|'),
		data = $(this).closest("div.rw-new-term").find('input.rw-new-term').val(),
		parent = $(this).closest("div.rw-new-term").find('select.rw-tax-parent').val(),
		termdiv = $(this).closest("div.rw-new-term").siblings("div.rw-terms");
		$(this).closest("div.rw-new-term").find('input.rw-new-term').val('');
		$(this).closest("div.rw-new-term").find('select.rw-tax-parent').val('');
		if(!parent) parent = 0;
		//termdiv.remove();
		if(!data) return false;
		$.post(ajaxurl,	{action: temp[0], 
				nonce: temp[1], 
				data: data, 
				parent : parent,
				post_id: temp[2], 
				tax: temp[3]},	
		function(response){
			if(response == 1) {
				alert("Error updating data");	
			} else {
				$(termdiv).html(response);	
			}
		});
		return false;
	});
	
	$("a.rw-show-add-term").live('click', function() {
		$(this).hide().siblings("div.rw-new-term").show();	
		return false;
	});
	$("a.rw-term-cancel").live('click', function() {
		$(this).closest("div.rw-new-term").hide().siblings("a.rw-show-add-term").show();	
		return false;
	});

});