/**
 * Functions for sneezy 
 */
var sneezySingleton = new function sneezySingleton() 
{
	var instance = this;
	sneezySingleton.getInstance = function() {
		return instance;
	};
	
	this.toString = function() {
		return "[object Singleton]";
	};


	/**
	 * Not the cleanest JS ever but seems to do the job
	 * @param type
	 */
	function initializeCategoryButton(type) {
		// for every button in the group
		$('#container-' + type + ' .category-button').click({type: type}, function (e) {
			// hide all panes
			$('#container-' + e.data.type + ' .content-pane-container').addClass('hide');
			
			// how the one that contains the role of this button
			var key = $(this).data('role');
			$('#container-' + e.data.type + ' .container-pane-' + key).removeClass('hide');
			
			// only per click should we load the jtable
			if (key == 'inventory') {
		  		$('#' + type + '-grid').jtable('load');
			}
				
		});
	}
	this.initializeCategoryButton = initializeCategoryButton;
	
    function initializeAdd(type){
    	// date picker
    	if (window.screen.availWidth > 960) {
    		var jqDate = $('#' + type +'-date').datetimepicker({
				timeFormat: "hh:mm tt"
			}); 
    		
    		jqDate.datetimepicker('setDate', (new Date()));
    	}
    	
		// bind on click event to bootstrap button
		var jqResult = $('#add-' + type + '-submit button').click( function() {
					sneezySingleton.getInstance().submitAdd(type);
				});
		
		// bind autocomplete
		$( "#" + type + "-types" ).autocomplete({
			  source: base_url + "index.php/" + type + "/get_types",
		      minLength: 1
		});
   };

   this.initializeAdd = initializeAdd;

   /**
    * Action for the submit button when adding 
    * @param type - type of data that is being added
    */
   function submitAdd(type) {
   	 	 var p = {};
	     p[type] = $('#' + type + '-types').val();
	     p[type + '-date'] = $('#' + type + '-date').val();
	     if ($('#' + type + '-date-container').is(':visible')) {
	    	 p[type + '-date'] = $('#' + type + '-date').val(); 
	     }
	     else {
	    	 p[type + '-date'] = $('#' + type + '-date-wheel').val();
	     }
	     p[type + '-note'] = $('#' + type + '-note').val();
	     
	     $('#' + type + '-response').load(base_url + 'index.php/' + type + '/insert',p,function(str){
	    	 setTimeout(function() {
	    		 $('#' + type + '-response').empty();
	    	 	} ,1500);
	     });
	     
	     $( '#' + type + '-note').val('');
	     $( '#' + type + '-types').val('').focus();
   };
   this.submitAdd = submitAdd;

   
   function initializeNavClick() {
	   
	   var arr = ['food', 'reaction','environment','medicine'];
	   
	   var length = arr.length;
	   var type = null;
	   
	   for (var i = 0; i < length; i++) {
		   type = arr[i];
		   
		   $('#nav-' + type).click({type: type}, function (e) {
			   $('.nav li').removeClass('active');
			   $(this).closest('li').addClass('active');
			   
			   $('.content-pane .content-category-container').addClass('hide');
			   $('.content-pane #container-' +  e.data.type).removeClass('hide');
			   $('.content-pane #container-' +  e.data.type).load(base_url + 'index.php/' +  e.data.type + '/category',{},function(str){});
			   $('.navbar-inner .btn').click();
		   });
		   
	   }
	   
	   $('#nav-timeline').click(function (e) {
		   $('.nav li').removeClass('active');
		   $(this).closest('li').addClass('active');
		   
		   $('.content-pane .content-category-container').addClass('hide');
		   $('.content-pane #container-timeline').removeClass('hide');
		   $('.content-pane #container-timeline').load(base_url + 'index.php/result/timeline',{},function(str){});
		   $('.navbar-inner .btn').click();
	   });
	   
	   $('#nav-hours-from-reaction').click(function (e) {
		   $('.nav li').removeClass('active');
		   $(this).closest('li').addClass('active');
		   
		   $('.content-pane .content-category-container').addClass('hide');
		   $('.content-pane #container-hours-from-reaction').removeClass('hide');
		   $('.content-pane #container-hours-from-reaction').load(base_url + 'index.php/result/hours_from_reaction',{},function(str){});
		   $('.navbar-inner .btn').click();
	   });
	   
	   $('#nav-type-merge').click(function (e) {
		   $('.nav li').removeClass('active');
		   $(this).closest('li').addClass('active');
		   
		   $('.content-pane .content-category-container').addClass('hide');
		   $('.content-pane #container-type-merge').removeClass('hide');
		   $('.content-pane #container-type-merge').load(base_url + 'index.php/maintain/merge_type',{},function(str){});
		   $('.navbar-inner .btn').click();
	   });
   }
   this.initializeNavClick = initializeNavClick;
 	
    // go get the meal list via json
 	function initializeInventory(type, columns) {
  		$('#' + type + '-grid').jtable({
            title: type + ' Inventory',
            paging: true, //Enable paginghttp://192.168.1.10/sneezy/index.php/meal/
            pageSize: 10, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: type + 'Date DESC', //Set default sorting
            gotoPageArea: 'none',
            
            //MealId as meal_id, MealDate as meal_date, FoodName as food_name
            actions: {
                listAction: 	base_url + 'index.php/' + type + '/retrieve_inventory/',
                deleteAction: 	base_url + 'index.php/' + type + '/delete/',
                updateAction: 	base_url + 'index.php/' + type + '/update/',
                createAction: ''
            },
            
            fields: columns

        });
 
    }
 	this.initializeInventory = initializeInventory;
 
 	function initializeHourReactionButton() {
 		$('#hours-from-reaction-start-date').datepicker({
			timeFormat: "hh:mm tt"
		}); 
		
 		$('#hours-from-reaction-end-date').datepicker({
			timeFormat: "hh:mm tt"
		});
 		
 		// bind autocomplete
		$( "#hours-from-reaction-type" ).autocomplete({
			  source: base_url + "index.php/reaction/get_types",
		      minLength: 1
		});
 		
 		$('#retrieve-hours-from-reaction-submit').click(function (e) {
			var startDate = $('#hours-from-reaction-start-date').val();
			startDate = startDate.replace(/\//g, '-');
			if (startDate == "") {
				startDate = '01-01-1970';
			}
			
			var endDate = $('#hours-from-reaction-end-date').val();
			endDate = endDate.replace(/\//g, '-');
			if (endDate == "") {
				endDate = '01-01-2020';
			}
			
			var gaps = $('#hours-from-reaction-gap').val();
			var scale = $('#hours-from-reaction-scale').val();
			var type = $('#hours-from-reaction-type').val();
            var min = $('#hours-from-reaction-min-eaten').val();
            var food = $('#hours-from-reaction-food-filter').val();
            var initial_hour = $('#hours-from-reaction-initial-hour').val();

            if (food == '')
            {
                food = 'no-filter';
            }
            food = encodeURIComponent(food);


            var columns = {
					FoodName:{key:true,title:"Food",create:false,edit:false},
					NumOfFood:{key:false,title:"Times Eaten",create:false,edit:false}
			};
			
			// @todo correct this duplication of logic in both js and php
            var floor = Math.floor(100/((gaps*2)+2));
            var hour =  parseInt(initial_hour);
			for (var i=1;i<=gaps;i++) {
				var h;
				if (scale == 'quadratic') {
					h = Math.pow(hour,2);
				}
				else if (scale == 'exponential') {
					h = Math.pow(2,hour);
				}
				else {
					h = hour;
				}
					
				columns["NumOf" + h +"Reactions"] = {key:false,title: "# " + h + " h",create:false,edit:false,width: floor + "%"};
                columns["PercentOf" + h +"Reactions"] = {key:false,title: "% " + h + " h",create:false,edit:false,width: floor + "%"};

                hour = hour + 1;
			}
			
			console.log(columns);
			
			var grid = $('#hours-from-reaction-grid');
			
			// remove any previous jtable instance
			if (!grid.is(':empty')) {
				grid.jtable('destroy');
			}
				
			
			// redefine the jtable
			grid.jtable({
				title: 'Number of reaction after food by hour',
	            paging: true, //Enable paginghttp://192.168.1.10/sneezy/index.php/meal/
	            pageSize: 10, //Set page size (default: 10)
	            sorting: true, //Enable sorting
	            defaultSorting: 'NumOfFood DESC', //Set default sorting
	            gotoPageArea: 'none',

	            actions: {
	                listAction:   base_url + 'index.php/result/retrieve_hours_from_reaction/' + gaps + '/' + scale + '/' + startDate + '/' + endDate + '/' + type + '/' + min + '/' + initial_hour + '/' + food + '/',
	                deleteAction: '',
	                updateAction: '',
	                createAction: ''
	            },
	            
	            fields: columns
			});
			
			// load the jtable
			grid.jtable('load');
			
		});
 		
 	}
 	this.initializeHourReactionButton = initializeHourReactionButton;
 	
 	function initializeMergeType() {
 		$( "#type-merge-from" ).autocomplete({
			  source: base_url + "index.php/food/get_types",
		      minLength: 1
		});
 		
 		$( "#type-merge-to" ).autocomplete({
			  source: base_url + "index.php/food/get_types",
		      minLength: 1
		});
 		
 		$('#type-merge-submit button').click( function() {
 			sneezySingleton.getInstance().submitMerge();
		});
 	};
 	this.initializeMergeType = initializeMergeType;
 	
 	function submitMerge() {
  	 	 var p = {};
	     p['type-merge-from'] = $('#type-merge-from').val();
	     p['type-merge-to'] = $('#type-merge-to').val();
	      
	     $('#merge-response').load(base_url + 'index.php/maintain/merge',p,function(str){
	    	 setTimeout(function() {
	    		 $('#merge-response').empty();
	    	 	} ,1500);
	     });
	     
	     $( '#type-merge-to').val('');
	     $( '#type-merge-from').val('').focus();
 	};
 	this.submitMerge = submitMerge;
 	
    return sneezySingleton;
};
