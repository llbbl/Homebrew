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
			source: "get_food_types",
		      minLength: 2
		});
    };

    this.initializeAddMeal = initializeAddMeal;
    
    function submitMeal()
    {
    	 var p = {};
	     p['food'] = $('#food_types').val();
	     p['mealDate'] = $('#meal_date').val();
	     
	     // goofy ajax request from CI
	     $('#response').load('insert',p,function(str){

	     });
    }
    this.submitMeal = submitMeal;

    
    
    return sneezySingleton;
};
