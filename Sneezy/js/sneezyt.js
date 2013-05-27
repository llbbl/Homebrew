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

    /*
     * Add Functions  
     */

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
	    	 p[type + '-date'] = $('#' + type + '-wheel').val();
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
	   $('#nav-add-meal').click(function (e) {
		   $('.nav li').removeClass('active');
		   $(this).closest('li').addClass('active');
		   
		   $('.content-pane .content-pane-container').addClass('hide');
		   $('.content-pane #container-add-meal').removeClass('hide');
		   $('.content-pane #container-add-meal').load(base_url + 'index.php/food/add',{},function(str){});
		   $('.navbar-inner .btn').click();
	   });
	   
	   $('#nav-add-event').click(function (e) {
		   $('.nav li').removeClass('active');
		   $(this).closest('li').addClass('active');
		   
		   $('.content-pane .content-pane-container').addClass('hide');
		   $('.content-pane #container-add-event').removeClass('hide');
		   $('.content-pane #container-add-event').load(base_url + 'index.php/event/add',{},function(str){});
		   $('.navbar-inner .btn').click();
	   });
	   
	   $('#nav-meal-list').click(function (e) {
		   $('.nav li').removeClass('active');
		   $(this).closest('li').addClass('active');
		   
		   $('.content-pane .content-pane-container').addClass('hide');
		   $('.content-pane #container-meal-list').removeClass('hide');
		   $('.navbar-inner .btn').click();
	   });
	   
	   $('#nav-event-list').click(function (e) {
		   $('.nav li').removeClass('active');
		   $(this).closest('li').addClass('active');
		   
		   $('.content-pane .content-pane-container').addClass('hide');
		   $('.content-pane #container-event-list').removeClass('hide');
		   $('.navbar-inner .btn').click();
	   });
	   
	   $('#nav-timeline').click(function (e) {
		   $('.nav li').removeClass('active');
		   $(this).closest('li').addClass('active');
		   
		   $('.content-pane .content-pane-container').addClass('hide');
		   $('.content-pane #container-timeline').removeClass('hide');
		   $('.content-pane #container-timeline').load(base_url + 'index.php/result/timeline',{},function(str){});
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
 
  		$('#' + type + '-grid').jtable('load');
    }
 	this.initializeInventory = initializeInventory;
 
 	 	
    return sneezySingleton;
};
