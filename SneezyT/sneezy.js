/**
 * Functions for sneezy 
 */
var sneezySingleton = new function sneezySingleton() 
{
	var instance = this;
	sneezySingleton.getInstance = function()
	{
		return instance;
	}
	
	this.toString = function()
	{
		return "[object Singleton]";
	}
    
	function initialize()
	{
		this.initializeAddMeal();
		this.initializeAddEvent();
		this.initializeTabs();
	}
	
	this.initialize = initialize;

	function initializeNav()
	{
		this.initializeAddMeal();
		this.initializeAddEvent();
		this.initializeNavClick();
		this.initializeMealList();
		this.initializeEventList();
	}
	
	this.initializeNav = initializeNav;

	
	/*
	 * 
	 * MEAL functions
	 * 
	 */
    
    function initializeAddMeal(){
    	 // date picker
		var jqMealDate = $('#meal_date').datetimepicker({
			timeFormat: "hh:mm tt"
		}); 
		jqMealDate.datetimepicker('setDate', (new Date()));
	
		// bind on click event to bootstrap button
		var jqSubmit = $('#add-meal-submit button').click(this.submitMeal);
		
		// bind autocomplete
		$( "#food_types" ).autocomplete({
			source: "http://192.168.1.10/sneezy/index.php/meal/get_types",
		    minLength: 1
		});
    };
    this.initializeAddMeal = initializeAddMeal;
    
    function submitMeal()
    {
    	 var p = {};
	     p['food'] = $('#food_types').val();
	     
	     if (jQuery('#meal_date_container').is(':visible')) {
	    	 p['meal_date'] = $('#meal_date').val(); 
	     }
	     else {
	    	 p['meal_date'] = $('#meal_date_wheel').val();
	     }
	    
	     p['meal-note'] = $( "#meal-note" ).val();
	     
	     // goofy ajax request from CI
	     $('#meal_response').load('http://192.168.1.10/sneezy/index.php/meal/insert',p,function(str){
	    	 setTimeout(function() {
	    		 $('#meal_response').empty();
	    	 	} ,1500);
	     });
	     
	     $( "#meal-note" ).val('');
	     $( "#food_types" ).val('').focus();
    }
    this.submitMeal = submitMeal;

    /*
     * 
     * EVENT FunctionsinitializeTabs
     * id="navbar-button" 
     */
    
    function initializeAddEvent(){
    	// date picker
    	if (window.screen.availWidth > 960) {
    		var jqEventDate = $('#event_date').datetimepicker({
				timeFormat: "hh:mm tt"
			}); 
	    	jqEventDate.datetimepicker('setDate', (new Date()));
    	}
    	
		// bind on click event to bootstrap button
		var jqSubmit = $('#add-event-submit button').click(this.submitEvent);
		
		// bind autocomplete
		$( "#event_types" ).autocomplete({
			source: "http://192.168.1.10/sneezy/index.php/event/get_types",
		      minLength: 1
		});
   };

   this.initializeAddEvent = initializeAddEvent;
   
   function submitEvent() {
   	 	 var p = {};
	     p['event'] = $('#event_types').val();
	     p['event_date'] = $('#event_date').val();
	     if (jQuery('#meal_date_container').is(':visible')) {
	    	 p['event_date'] = $('#event_date').val(); 
	     }
	     else {
	    	 p['event_date'] = $('#event_date_wheel').val();
	     }
	     p['event-note'] = $('#event-note').val();
	     
	     // goofy ajax request from CI
	     $('#event_response').load('http://192.168.1.10/sneezy/index.php/event/insert',p,function(str){
	    	 setTimeout(function() {
	    		 $('#event_response').empty();
	    	 	} ,1500);
	     });
	     
	     $( "#event-note").val('');
	     $( "#event_types" ).val('').focus();
   };
   this.submitEvent = submitEvent;

   
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
   }
   this.initializeNavClick = initializeNavClick;
   
    // go get the meal list via json
 	function initializeMealList() {
 		$('#mealGrid').jtable({
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
                updateAction: '',
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
                    edit: false
                }
            }
        });
 
        //Load student list from server
        $('#mealGrid').jtable('load');
    }
 	this.initializeMealList = initializeMealList;

    // go get the meal list via json
 	function initializeEventList() {
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
                updateAction: '',
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
                    edit: false
                }
            }
        });
 
        //Load student list from server
        $('#eventGrid').jtable('load');
    }
 	this.initializeEventList = initializeEventList;
 	
 	/**
 	 * Hopefully all result functions can call this method
 	 */
 	function initializeResults() {
 		initializeHoursFromEvent();
 	}
 	this.initializeResults = initializeResults;
 	
 	function initializeHoursFromEvent() {
 		$('#eventGrid').jtable({
            title: 'Event List',
            paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'EventDate ASC', //Set default sorting
            gotoPageArea: 'none',
            //MealId as meal_id, MealDate as meal_date, FoodName as food_name
            actions: {
                listAction: 'http://192.168.1.10/sneezy/index.php/event/event_list/',
                deleteAction: '',
                updateAction: '',
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
                }
            }
        });
 
        //Load student list from server
        $('#eventGrid').jtable('load');
 	}
 	
    return sneezySingleton;
};
