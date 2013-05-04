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
			source: "index.php/meal/get_types",
		      minLength: 1
		});
    };
    this.initializeAddMeal = initializeAddMeal;
    
    function submitMeal()
    {
    	 var p = {};
	     p['food'] = $('#food_types').val();
	     p['meal_date'] = $('#meal_date').val();
	     
	     // goofy ajax request from CI
	     $('#meal_response').load('index.php/meal/insert',p,function(str){
	    	 setTimeout(function() {
	    		 $('#meal_response').empty();
	    	 	} ,2000);
	     });
	     
	     $( "#food_types" ).focus();
    }
    this.submitMeal = submitMeal;

    /*
     * 
     * EVENT FunctionsinitializeTabs
     * 
     */
    
    function initializeAddEvent(){
    	// date picker
    	var jqEventDate = $('#event_date').datetimepicker({
			timeFormat: "hh:mm tt"
		}); 
    	jqEventDate.datetimepicker('setDate', (new Date()));
		
		// bind on click event to bootstrap button
		var jqSubmit = $('#add-event-submit button').click(this.submitEvent);
		
		// bind autocomplete
		$( "#event_types" ).autocomplete({
			source: "index.php/event/get_types",
		      minLength: 1
		});
   };

   this.initializeAddEvent = initializeAddEvent;
   
   function submitEvent()
   {
   	 	 var p = {};
	     p['event'] = $('#event_types').val();
	     p['event_date'] = $('#event_date').val();
	     
	     // goofy ajax request from CI
	     $('#event_response').load('index.php/event/insert',p,function(str){
	    	 setTimeout(function() {
	    		 $('#event_response').empty();
	    	 	} ,2000);
	     });
	     
	     $( "#event_types" ).focus();
   }
   this.submitEvent = submitEvent;
   
   
   function initializeTabs() {
		$('#myTab a').click(function (e) {
		  e.preventDefault();
		  $(this).tab('show');
		})
   }
   this.initializeTabs = initializeTabs;
   
    // go get the meal list via json
 	function initializeMealList() {
  		$.getJSON('../meal_list/', function(data) {
  			loadMealList(data);
		});
    }
 	this.initializeMealList = initializeMealList;
 	
 	// after the ajax call is complete, load the list
    function loadMealList(data)
    {
        var grid;
        var columns = [
          {id: "meal_id", name: "Meal Id", field: "meal_id"},
          {id: "meal_date", name: "Meal Date", field: "meal_date"},
          {id: "food_name", name: "Food", field: "food_name", width: "300"}
        ];

        var options = {
          enableCellNavigation: true,
          enableColumnReorder: false
        };
        
    	grid = new Slick.Grid("#mealGrid", data, columns, options);
            
    }

   
    return sneezySingleton;
};
