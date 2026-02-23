(function($) {
	$.fn.PavFooterControl = function(opts) {
		// default configuration
		var config = $.extend({}, {
			opt1: null,
			text_warning_select:'Please select One to remove?',
			text_confirm_remove:'Are you sure to remove this?',
			JSON:null,
			TOKEN:''
		}, opts);
		// main function
		function DoSomething(e) {
			
		}

		var footRowActive = null;
		var footColumnActive = null;
		// add Footer Row

		function AddFooterRow( editor ){
			var current = $( '<div class="footwrap"><div class="footrow  content-wrap clearfix"><div class="btn-delete btn-action">Remove</div><div class="btn-addcols btn-action">Add Column</div><div class="row-fluid"></div></div></div>'  );
			$(editor).append( current );
			$(".footrow",editor).removeClass('active');
			current.addClass( 'active');
			setFootRowActive( current );
		}

		function RemoveFooterRow( editor ){
			if( $(".footrow.active", editor ).length<=0 ){
				alert( config.text_warning_select );	
			}else if( confirm(config.text_confirm_remove) ){
				$(".footrow.active", editor ).remove();
				setFootRowActive( null );
			}	
			return true;
		}

		function AddFootColumn( e ){
			var spans = 12;

			var current = $( '<div class="footcol span3"><a class="btn-action btn-remove">delete</a></div>'  );
			$(e).append(  current );
			$(".footcol",e).removeClass('active');

			current.addClass( 'active');
			footColumnActive = current;
			$(footColumnActive).data( 'coldata', createDefaultColumnData( true ) );

			$(footColumnActive).attr( "id", "row"+($(".footwrap").length-1)+"col"+($(current).parent().find(".footcol").length-1) );

		    addDropAndDragableCol( footColumnActive );

			restoreColumnDataForm();
		}

		function addDropAndDragableCol( footColumnActive ){
			$( footColumnActive ).droppable({ 
		        ctiveClass: "ui-state-default",
		        hoverClass: "ui-state-hover",
		        accept:".widget",
		        drop: function( event, ui ) { 		
		           var w = $('<div class="inject-widget content-wrap"><div class="w-setting btn-action">Setting</div><div class="w-delete  btn-action">Delete</div></div>');
		           w.attr('data-widget',$(ui.draggable).attr('id'));
		           var id =  $(this).attr("id") + "_" + $(ui.draggable).attr('id')+"_"+ ($(".inject-widget",this).length+1);
		           w.append( '<textarea style="display:none" type="hidden" name="data_widget[]" id="'+id+'" value="" />' );
		           w.append( $(".widget-header",$(ui.draggable)).clone()  ); 		
		           $(this).append( w );
		            // alert( html() )
		        }
		      });
		     	

		      $(  footColumnActive ).sortable({
					items: ".inject-widget",
					connectWith:'.footcol'
				});

		}	

		function onFormColumnHandler( colum ){
			
			 
			$("input, select", "#columnform .advfooter-toolbar").change( function(){
				var data = $(footColumnActive).data( 'coldata' );
				if( $(this).attr('name') == 'column_span' ){
					var _class = $(footColumnActive).attr("class").replace(/span\d+/,"span"+$(this).val() );
					 $(footColumnActive).attr( 	"class",_class ); 
				}
				data[$(this).attr('name')] = $(this).val();
				$(footColumnActive).data( 'coldata', data );
			} );	

		}
		function setFootRowActive( e ){
			footRowActive = e;
		}

		function createDefaultColumnData( isform ){
			var data = new Object();
			data.column_class = '';
			data.column_span = '3';
			data.column_type  = 'text';
			return data;	
		}

		function restoreColumnDataForm(){
			if( footColumnActive ){
				var data = $(footColumnActive).data('coldata');
				$.each( data, function(i,e){ 
					$( '[name="'+i+'"]','#columnform' ).val( e );
				} );
			}
		}

		
		 
		function onHandlerWidgets( editor ){

			$( ".advfoot-widgets .widget" ).draggable({
		          appendTo: "body",
		           helper: "clone"
		      });


			addDropAndDragableCol( ".footcol" );	
		    


		     $(editor).delegate('.w-setting', 'click', function(){
		     	var rid = Math.floor(Math.random( 1000 )*1000);

		     	var _setting = $('<div id="setting-dialog"><form id="setting-form'+rid+'"></form></div>');
		     	_setting.find('form').append( $('#'+$(this).parent().attr("data-widget")+" .widget-form").clone().show() );
		     	var input = $(this).parent().find("textarea");
		     		if( input.val() ){
		     			try{ 
			     			var  aa = jQuery.parseJSON( $.trim(input.val() )) ;
			     			if( aa  ) {
			     				$.each(aa, function(k,v){
			     					 $('[name="'+k+'"]',_setting.find('form')).val( v ); 
			     				} );
			     			}
			     		}catch( e ){

			     		}	
			     		 
			     		if( $(".image-input", _setting).val() ) {
			     			  $.ajax({
						          url: 'index.php?route=common/filemanager/image&token='+config.TOKEN+'&image=' + encodeURIComponent( $(".image-input", _setting).val() ),
						          dataType: 'text',
						          success: function(data) {
						            $('.image-preview' , _setting).attr("src",data);
						          }
						        });
			     		}
		     		}
		     	 $( _setting ).dialog({
						title: 'Widget Form Setting',
						close: function (event, ui) {
							 _setting.remove();
						},	
						bgiframe: false,
						width: 700,
						height: 500,
						resizable: false,
						modal: false,
						open:function(){
							 
						},
						buttons:{'Save':function(){ 
							var _data = new Object();
							$.each($("#setting-dialog form").serializeArray(), function(i,e){ // alert( e.name + "="+e.value	);
								_data[e.name] = e.value; 	
							} );
						//	alert(  JSON.stringify(_data) );
							  $(input).val( JSON.stringify(_data) );
							  $( _setting ).dialog('close');
						} }
				}); 
		     } );
		      $(editor).delegate('.w-delete', 'click', function(){
			    if( confirm(config.text_confirm_remove) ){
			    	$(this).parent().remove();		
			    } 	
		     } );		
		}
		// initialize every element
		this.each(function() {  
			var toolbar = $( ".advfooter-toolbar", $(this) );
			var editor  = $(".advfooter-editor", this ); 
		
			$("#frm-column-properties").hide();
			$("#add-footrow", toolbar).click( function () {
					AddFooterRow( editor );
					return false; 
			});

			$("ul li#remove-footrow", toolbar).click( function () {
					RemoveFooterRow( editor );
					if( !footColumnActive ) {
						$("#frm-column-properties").hide();
					}
			});

			$("#add-footcolumn", toolbar).click( function(){
				AddFootColumn( $(".footrow .row-fluid", footRowActive) );

			} );

			$(editor).delegate( ".footrow",'click',function(){
			 	$(".footrow",editor).removeClass("active");$(this).addClass("active");	
			 	setFootRowActive( $(this).parent() ); 		 
			} );

			/** **/
			$(editor).delegate( ".footrow > .btn-action",'click',function(){
				if( $(this).hasClass('btn-addcols') ) {
					AddFootColumn( $(".footrow .row-fluid",footRowActive)  );
					$("#frm-column-properties").show();
				}else {
					RemoveFooterRow( editor );
					$("#frm-column-properties").hide();
				}
			});


			$(editor).delegate( ".footcol",'click',function(){
			 	$(".footcol",editor).removeClass("active");$(this).addClass("active");	
			 	footColumnActive = this;
			 	restoreColumnDataForm();
			 	$("#frm-column-properties").show();
			} );

			$(editor).delegate(".footcol > .btn-remove","click", function(){  
				if( footColumnActive && confirm(config.text_confirm_remove) ){
					$( footColumnActive ).remove();		
			 		$("#frm-column-properties").hide();
				}

			} ) ;
			///

			if( config.JSON && config.JSON != null ){
			  var obj = jQuery.parseJSON( config.JSON );
				$.each(obj,function(i, row){
					$.each( row , function(j,col ){	
						$("#row"+i+"col"+j).data('coldata', col );
					});
				});  
			}
			 
			$( "#widgets-bar" ).draggable({ axis: "y" });

			///
			onFormColumnHandler( footColumnActive );

			$("#btn-save-data").click( function(){
				 var rows = new Array();var i = 0;
				 var widgets = new Object();
				 $(".footrow", editor ).each( function(){
				 	rows[i] = new Array();
				 	var j = 0;
				 	$('.footcol', this).each( function(){
				 		rows[i][j] = $(this).data('coldata');
			 			rows[i][j].widgets_data = new Array();
			 			rows[i][j].widgets_type = new Array();
			 				var k = 0;
			 				var _id = "row"+i+"col"+j;	
			 					 
			 			 	widgets[_id]= new Object();
			 				$(".inject-widget",this).each( function(ip,ipt){  
		 						widgets[_id]["widget"+k]= new Object();
		 						widgets[_id]["widget"+k]['data'] = $("textarea",this).val();
		 						widgets[_id]["widget"+k]['type'] = $(this).attr('data-widget');  
			 		 			 					k++;
			 				} );
			 			j++;
				 	} );
				 	i++;
				 } ); 

				 $("#content").append( '<form style="" id="submit-frm-datarows" enctype="multipart/form-data" method="post"><input name="data-widgets" id="input-data-widgets"><input name="data-rows" id="input-data-rows"></form>' );

			 	
				var data = JSON.stringify(rows)
				$("#input-data-rows").val( data );
				$("#input-data-widgets").val( JSON.stringify(widgets) );
			 

				$.ajax( {
						url:$(this).attr('href'),
						data:$("#submit-frm-datarows").serialize(),
						type:'POST'
	
				} ).done( function(){
					$("#submit-frm-datarows").remove();
					 	$('#frmdata').submit();
					//   location.reload();
				} );

				return false; 
			} );


				// 

			onHandlerWidgets( editor );		
		// 	DoSomething( $(this) );
		});
		return this;
	};
	
})(jQuery);


 