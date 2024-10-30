jQuery(document).ready(function($) {
	if ( $( ".wpcf7-form" ).length ) {

		cf7_customradiobtn();

		cf7_formulas();

		$("body").on("change",".wpcf7 input,.wpcf7 select",function(e){
			cf7_formulas();
		})


		$("input[type='number']").bind('keyup', function () {
		  cf7_formulas();
		})

		$("input[type='text']").bind('keyup', function () {
		  cf7_formulas();
		})

		function cf7_formulas(){
		   var total = 0;
	       var match;
	       var reg =[]; 

	      	$("form.wpcf7-form input").each(function () { 
	       		if( $(this).attr("type") == "checkbox" || $(this).attr("type") == "radio"  ) {
	       			var name = $(this).attr("name").replace("[]", "");
	       			reg.push(name);
	       			//alert($(this).attr("name"));
	       		}else{
	       			reg.push($(this).attr("name"));
	       		}
	       	})

	       	$("form.wpcf7-form select").each(function () {
	       		var name_select = $(this).attr("name").replace("[]", "");
	       	     //console.log($(this)); 
	       		reg.push(name_select);
	       	})
	       	reg = duplicates_type(reg);
	       	var all_tag = new RegExp( reg.join("|"));
		       	$( ".occf7cal-total" ).each(function( index ) {
		       		// console.log(index); 
		       		var precision = $(this).attr("precision");
		       		var prefix = $(this).attr("prefix");
		       		var eq = $(this).data('formulas');
		       		var type = '';
		       		if(eq != '' || eq.length != 0){
						while ( match = all_tag.exec( eq ) ){
							var perfact_match = wordInString(eq,match[0]);
							if(perfact_match != false){
								var type = $("input[name="+match[0]+"]").attr("type");
								if( type === undefined ) {
									var type = $("input[name='"+match[0]+"[]']").attr("type");
								}
								//console.log(type);
								if( type =="checkbox" ){
									var vl = 0;
									$("input[name='"+match[0]+"[]']:checked").each(function () {
										var attr = $(this).attr('price');
										//console.log(attr);
										if (typeof attr !== typeof undefined && attr !== false) {
											vl += new Number(attr);
										} else {
											vl += new Number($(this).val());
										}
									});
								}else if( type == "radio"){
									var attr = $("input[name='"+match[0]+"']:checked").attr('price');
									if (typeof attr !== typeof undefined && attr !== false) {
										var vl = attr;
									} else {
										 var vl = $("input[name='"+match[0]+"']:checked").val();
									}
									
								}else if( type === undefined ){
									var check_select = $("select[name="+match[0]+"]").val();
									//console.log('--'+check_select+'--')
									if(check_select === undefined){
										var vl = 0;
										$("select[name='"+match[0]+"[]'] option:selected").each(function () {
											var attr = $(this).attr('price');
											if (typeof attr !== typeof undefined && attr !== false) {
												vl += new Number(attr);
											} else {
												vl += new Number($(this).val());
											}
										});
									} else {
										var vl = 0;
										$("select[name="+match[0]+"] option:selected").each(function () {
											var attr = $(this).attr('price');
											if (typeof attr !== typeof undefined && attr !== false) {
												vl += new Number(attr);
											} else {
												vl += new Number($(this).val());
											}
										});
									}
								}else{
									var attr = $("input[name="+match[0]+"]").attr('price');
									if (typeof attr !== typeof undefined && attr !== false) {
									    var vl = attr;
									} else {
										var vl = $("input[name="+match[0]+"]").val();	
									}
								}
								if(!$.isNumeric(vl)){
									vl = 0;
									//alert("value must be numeric");
								}

							}else{
							 	var error = 1;
							}
							eq = eq.replace( match[0], vl );
							nueq = '';
							neq = eq;
							var neqf = '';
							if(eq.indexOf("sqrt") != -1){
								var neweq = eq.split(" ");
								for(var i = 0; i < neweq.length; i++){
									if(neweq[i].indexOf("sqrt") != -1){
									   var sqrt = neweq[i].match("sqrt((.*))");
									   var sqrtd = sqrt[1].replace(/[()]/g,'')
									   var sqrtroot = Math.sqrt(parseInt(sqrtd));
									   neq = neq.replace( sqrt[1], sqrtroot );
									}     
							     }
							     nueq = neq.split("sqrt").join('');
							}
							if(nueq === ''){
								neqf = eq;
							 } else {
							 	neqf = nueq;
							 }
							//console.log(neqf);

						}
					
		       		}else{
		       			alert("Please Enter Formula in Calculator");
		       			return false;
		       		}
		       		if(error == 1){
		       			alert("Please Enter Valid Formula in Calculator");
		       			return false;
		       		}
					try{
						var fresult = ''; 
						//console.log(neqf);
					    var r = eval( neqf ); // Evaluate the final equation
					    if( precision != undefined ) {
					      	fresult = r.toFixed(precision);
						} else {
							fresult = r;
						}

						total = fresult;
						//console.log('--'+total);

					}
					catch(e)
					{
						alert( "Error:" + neqf );
					}

					if(index === 0){
						if( prefix != undefined ) {
							$(this).val(prefix + total);
						}else{
							//console.log(total);
							$(this).val(total);
						}
					} else {
						$(this).val('Pro Version');
					}
					
				});
		}

		function cf7_customradiobtn(){
			$("form.wpcf7-form input").each(function (index) { 
	       		if( $(this).attr("type") == "radio" || $(this).attr("type") == "checkbox" ) {
	       			var inputval = $(this).attr("value");

	       			if(inputval.indexOf("--") != -1){
		       			$(this).val('');
		       			$(this).parent().find('span.wpcf7-list-item-label').text('Required Pro Version');  
	       			}
	       		}
	       	})
			$("form.wpcf7-form select option").each(function (index) {
		       	if($(this).attr("type") === undefined) {
		       		var selectval = $(this).attr("value");
		       		if(selectval.indexOf("--") != -1){
			       		$(this).attr("value",'');
			       		$(this).text('Required Pro Version');
		       		}		
		       	}
	       	})

		}

	}
	function duplicates_type(arr) {
	    var obj = {};
	    var ret_arr = [];
	    for (var i = 0; i < arr.length; i++) {
	        obj[arr[i]] = true;
	    }
	    //console.log(obj);
	    for (var key in obj) {
	    	if("_wpcf7" == key || "_wpcf7_version" == key  || "_wpcf7_locale" == key  || "_wpcf7_unit_tag" == key || "_wpnonce" == key || "undefined" == key  || "_wpcf7_container_post" == key || "_wpcf7_nonce" == key  ){

	    	}else {
	    		ret_arr.push(key);
	    	}
	    }
	    return ret_arr;
	}
	function wordInString(s, word){
	  return new RegExp( '\\b' + word + '\\b', 'i').test(s);
	}



	jQuery(".occf7cal_slider_div").each(function() {
	     	var step=jQuery(this).attr("step");
		    var min=jQuery(this).attr("min");
		    var max=jQuery(this).attr("max");
		    var prefixx=jQuery(this).attr("prefix");
		    var prefixpos=jQuery(this).attr("prefixpos");
		    var color=jQuery(this).attr("color");
		    var caltoltip=jQuery(this).attr("caltoltip");
		    //alert(caltoltip);

		    var istep = parseInt(step);
		    var imin = parseInt(min);
		    var imax = parseInt(max);
		   
		    if(caltoltip == "top"){
		    	var tooltip = jQuery('<div id="ctooltip" class="top"/>').css({
			        top: -35,
    				
			    })
		    }
		    if(caltoltip == "left"){
		    	var tooltip = jQuery('<div id="ctooltip" class="left"/>').css({
			        right: 35
			    })
		    }
		    if(caltoltip == "right"){
		    	var tooltip = jQuery('<div id="ctooltip" class="right"/>').css({ 
			        left: 35			    
			    })
		    }
		    if(caltoltip == "bottom"){
		    	var tooltip = jQuery('<div id="ctooltip" class="bottom"/>').css({
			        bottom: -35
			    })
		    }
		    

		    if(prefixx == null){
		    	prefix = " ";
		    }else{
		    	prefix = prefixx;
		    }

		    if(prefixpos == "left") {
		        tooltip.text(prefix + min);
		    }else {  
		        tooltip.text(min + prefix);        
		    }
		

		    var curr = jQuery(this);
		    jQuery(this).slider({
		        step:istep,
		        min:imin,
		        max:imax,
		        values: imin,
		        create: attachSlider,
		        slide: function( event, ui ) {
		        	curr.find(".occf7cal_slider").val(ui.value);
		        	
		        	cf7_formulas();
		        	var clr = jQuery(this).attr("color");
		        	var pre = jQuery(this).attr("prefix");
		        	if(pre == null){
				    	prefix = " ";
				    }else{
				    	prefix = pre;
				    }
		        	

			    	if(prefixpos == "left"){
		                curr.find("#ctooltip").text(prefix  + ui.value);  
		            }else {
		               curr.find("#ctooltip").text(ui.value + prefix);
		            }

				   	curr.find(".ui-state-default").css("background-color",clr);
		            
		        }        
			}).find(".ui-slider-handle").append(tooltip).hover(function() {
		    	tooltip.show()
		    })
		    function attachSlider() {
		        jQuery(this).find(".ui-slider-handle").css("background-color",color);
		    }
	});
});