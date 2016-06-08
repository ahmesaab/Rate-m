	window.onload = function()
  	{
  		function getQueryStringValue(key)
		{  
			return unescape(window.location.search.replace(new RegExp("^(?:.*[&\\?]" + escape(key).replace(/[\.\+\*]/g, "\\$&") + "(?:\\=([^&]*))?)?.*$", "i"), "$1"));  
		}

		function findStarValue(AttributeScore)
		{
			return Math.round(AttributeScore*2)/2;
		}

		$(".rating").find("input:radio").on("click", function(){
			var currentFeild = $(this).closest('.rating');
	    	var rateScore = $(this).val();
	    	var rateAttribute = $(this).attr('name');
	    	var ratedUser = getQueryStringValue('id');
	    	var urlString = 'rate?rateScore='+rateScore+'&rateAttribute='+rateAttribute+'&ratedUser='+ratedUser;
	        $.ajax({
	        	url: urlString, 
	        	success: function(result)
	        	{
	        		var returnCode = result.split(':')[0];
	        		
		        	if(returnCode=='200')
		        	{
		        		var newAttributeScore = result.split(':')[1];
		        		newAttributeScore = findStarValue(newAttributeScore)
		        		currentFeild.find('input[value="'+newAttributeScore+'"]').prop('checked',true);
		        	}
		        	else if(returnCode=='422')
		        	{
		        		alert("You have rated this User before -_-");
		        	}
		        	else if(returnCode=='400')
		        	{
		        		alert("Invalid vote value x_x");
		        	}
		        	else if(returnCode=='403')
		        	{
		        		alert("You dont have permission to rate this User.");
		        	}
		        	else
		        	{
		        		alert("Unexpected response from server.");
		        	}
		    	},
		    	error: function() 
		    	{
            		alert("An error has occurred! Try Again!");
        		}
		    });
	        return false;
	    }); 
	 
	};

