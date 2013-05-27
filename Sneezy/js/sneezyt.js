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
    		var jqDate = $('#' + type +'_date').datetimepicker({
				timeFormat: "hh:mm tt"
			}); 
    		jqDate.datetimepicker('setDate', (new Date()));
    	}
    	
		// bind on click event to bootstrap button
		$('#add-' + type + '-submit button').click(this.submitAdd(type));
		
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
	     
	     // goofy ajax request from CI
	     $('#' + type + '-response').load(base_url + 'index.php/event/insert',p,function(str){
	    	 setTimeout(function() {
	    		 $('#' + type + '-response').empty();
	    	 	} ,1500);
	     });
	     
	     $( '#' + type + '-note').val('');
	     $( '#' + type + 'types').val('').focus();
   };
   this.submitAdd = submitAdd;

   
   function initializeNavClick() {
	   $('#nav-add-meal').click(function (e) {
		   $('.nav li').removeClass('active');
		   $(this).closest('li').addClass('active');
		   
		   $('.content-pane .content-pane-container').addClass('hide');
		   $('.content-pane #container-add-meal').removeClass('hide');
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
 	function initializeInventoryMeal() {
 		$('$meal-Grid').jtable({
            title: 'Meal List',
            paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'MealDate DESC', //Set default sorting
            gotoPageArea: 'none',
            //MealId as meal_id, MealDate as meal_date, FoodName as food_name
            actions: {
                listAction: 'http://192.168.1.10/sneezy/index.php/meal/meal_list/',
                deleteAction: 'http://192.168.1.10/sneezy/index.php/meal/delete/',
                updateAction: 'http://192.168.1.10/sneezy/index.php/meal/update/',
                createAction: ''
            },
            fields: {
            	MealId: {
                    key: true,
                    create: false,
                    edit: false
                },
                MealDate: {
                    title: 'Date',
                    create: false,
                    edit: false
                },
                FoodName: {
                    title: 'Food',
                    create: false,
                    edit: false
                },
                MealNote: {
                    title: 'Note',
                    create: false,
                    edit: true
                }
            }
        });
 
        //Load student list from server
        $('#meal-grid').jtable('load');
    }
 	this.initializeInventoryMeal = initializeInventoryMeal;

    // go get the meal list via json
 	function initializeInventoryEvent() {
  		$('#eventGrid').jtable({
            title: 'Event List',
            paging: true, //Enable paginghttp://192.168.1.10/sneezy/index.php/meal/
            pageSize: 10, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'EventDate DESC', //Set default sorting
            gotoPageArea: 'none',
            //MealId as meal_id, MealDate as meal_date, FoodName as food_name
            actions: {
                listAction: 'http://192.168.1.10/sneezy/index.php/event/event_list/',
                deleteAction: 'http://192.168.1.10/sneezy/index.php/event/delete/',
                updateAction: 'http://192.168.1.10/sneezy/index.php/event/update/',
                createAction: ''
            },
            fields: {
            	EventId: {
                    key: true,
                    create: false,
                    edit: false
                },
                EventDate: {
                    title: 'Date',
                    width: '23%',
                    create: false,
                    edit: false
                },
                EventName: {
                    title: 'Event',
                    create: false,
                    edit: false
                },
                EventNote: {
                    title: 'Note',
                    create: false,
                    edit: true
                }
            }
        });
 
        //Load student list from server
        $('#eventGrid').jtable('load');
    }
 	this.initializeInventoryEvent = initializeInventoryEvent;
 	
 	 	
    return sneezySingleton;
};
