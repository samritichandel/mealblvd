
jQuery(function() {
//add and remove class on scroll
    var header = jQuery(".site-header");
    jQuery(window).scroll(function() {
        var scroll = jQuery(window).scrollTop();

        if (scroll >= 70) {
            header.addClass("header_fxd");
        } else {
            header.removeClass("header_fxd");
        }
    });
	
	//ajax for getting user email
	jQuery("#user_email").blur(function(){
        var email=jQuery(this).val();
		jQuery.ajax({
			type:"post",
			datatype: "html",
			url : ajaxurl,
			data :{ 'action':'get_email_address','email': email},
		    success : function(response)
            {
				
				if(response != "" || response != null)
				{
					jQuery('#user_email').removeClass('valid').addClass('error');
					jQuery('#email_already_exists').remove();
					jQuery('#user_email').after('<label class="error" id="email_already_exists">'+response+'</label>');
					jQuery('input[name="submit_regis"]').prop('disabled', true);
				}
				if(!response)
				{
					jQuery('#user_email').removeClass('error').addClass('valid');
					jQuery('#email_already_exists').remove();
					jQuery('input[name="submit_regis"]').prop('disabled', false);
				}
					
					
			}
		});
    });
	
	
	//validation for registeration
jQuery('#registration').validate({
	rules: {
		fname:{required: true},
		lname:{required: true},
		phone:
		{
			required: true,
			digits: true,
			maxlength: 12		 
		},
		user_email:
		{
			required: true,
			email: true		 
		},
		
		/*password: 
		{
			required: true,
			minlength: 5	
		},
		confirm_password:
		{
			required: true,
			minlength: 5,
			equalTo : "#password"
			
		}*/
	},
	messages:
	{
		fname: { required:  "Please enter your first name."},
		lname: {required : "Please enter your last name."},
		phone: {
				required: "Please enter a phone number.",
				digits: "Please enter a valid number.",
				maxlength: "Please enter a valid number."
			   },
		/*password: {
				required: "Please provide a password",
				minlength: "Your password should be atleat 8 characters long"
				},
		confirm_password:
		{
			required: "Please provide a password",
			minlength:"Your password should be atleat 8 characters long",
			equalTo : "Password does not match"
			
		}	*/	
	}
	
});

//validation for user profile page
jQuery('#user_profile').validate({
	rules: {
		fname:{required: true},
		lname:{required: true},
		phone:
		{
			required: true,
			digits: true,
			maxlength: 12		 
		},
		user_email_profile:
		{
			required: true,
			email: true		 
		},
		
		new_password: 
		{
			minlength: 8	
		},
		confirm_password:
		{
			minlength: 8,
			equalTo : "#new_password"
		}
	},
	messages:
	{
		fname: { required:  "Please enter your first name."},
		lname: {required : "Please enter your last name."},
		phone: {
				required: "Please enter a phone number.",
				digits: "Please enter a valid number.",
				maxlength: "Please enter a valid number."
			   },
		new_password: {
				minlength: "Your password should be atleat 8 characters long."
				},
		confirm_password:
		{
			minlength:"Your password should be atleat 8 characters long.",
			equalTo : "Password does not match."
		}		
	}
	
	
});

//validating the current password using ajax
var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms (5 seconds)

//on keyup, start the countdown
jQuery('#password').keyup(function(){
    clearTimeout(typingTimer);
    if (jQuery('#password').val()) {
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    }
});


function doneTyping () 
{
var pass=jQuery('#password').val();
var email=jQuery('#user_email_profile').val();
jQuery.ajax({
	type:"post",
	datatype: "html",
	url : ajaxurl,
	data :{ 'action':'get_password','email': email, 'pass':pass},
	success : function(response)
	{
		if(response === "Not matched")
		{
			jQuery('#password').addClass('error');
			jQuery('#pass_check').remove();
			jQuery('#password').after('<label class="error" id="pass_check">Provided password is incorrect.</label>');
			jQuery('input[name="submit_dash_profile"]').prop('disabled', true);
		}
		if(response === 'matched')
		{
			jQuery('#password').removeClass('error').addClass('valid');
			jQuery('#pass_check').remove();
			jQuery('input[name="submit_dash_profile"]').prop('disabled', false);
		}
	}
});
}

//validating the email if user changes it
jQuery('#user_email_profile').keyup(function(){
    clearTimeout(typingTimer);
    if (jQuery('#user_email_profile').val()) {
        typingTimer = setTimeout(doneTypingEmail, doneTypingInterval);
    }
});
function doneTypingEmail()
{
	var email=jQuery('#user_email_profile').val();
		jQuery.ajax({
			type:"post",
			datatype: "html",
			url : ajaxurl,
			data :{ 'action':'get_email_address','email': email},
		    success : function(response)
            {
				if(response != "" || response != null)
				{
					jQuery('#user_email_profile').removeClass('valid').addClass('error');
					jQuery('#email_profile').remove();
					jQuery('#user_email_profile').after('<label class="error" id="email_profile">'+response+'</label>');
					jQuery('input[name="submit_dash_profile"]').prop('disabled', true);
				}
				if(!response)
				{
					jQuery('#user_email_profile').removeClass('error').addClass('valid');
					jQuery('#email_profile').remove();
					jQuery('input[name="submit_dash_profile"]').prop('disabled', false);
				}
			}
		});
}

//allow alphabets only
jQuery('#fname,#lname').keydown(function (e) {
	if (e.shiftKey || e.ctrlKey || e.altKey) {
	e.preventDefault();
	} else {
	var key = e.keyCode;
	if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
	e.preventDefault();
	}
	}
	});
	
//post a meal validation
//add method for select validation
jQuery.validator.addClassRules("wp-editor-wrap", {
  required: true
});

jQuery.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg != value;
 }, "Value must not equal arg.");


 jQuery("#submit-job-form").validate({
  rules: {
   meal_type: { valueNotEquals: 0 },
   min_guest: { valueNotEquals: 0 },
   max_guest: { valueNotEquals: 0 },
   type_cuisine: { valueNotEquals: 0 },
   currency: { valueNotEquals: 0 },
   job_title: {required: true,maxlength: 100,},
   price: {required: true,digits: true,maxlength: 5},
   job_location : {maxlength: 200},
   company_phone :{maxlength: 20},
   company_twitter: {maxlength: 50}
   },
  messages: {
  meal_type: { valueNotEquals: "" },
  min_guest: { valueNotEquals: "" },
  max_guest: { valueNotEquals: "" },
  type_cuisine: { valueNotEquals: "" },
  currency: { valueNotEquals: "" },
  job_title: {
				required: "",
				maxlength: ""
			},
	price: {
				required: "",
				digits: "",
				maxlength: ""}
  } ,
  job_location : {maxlength: ""},
  company_phone: {maxlength: ""},
  company_twitter: {maxlength: ""}
 });


 //jQuery for load more dasboard comments
 size_li = jQuery(".dash-review-main>ul>li").size();
  x=2;
   jQuery('.dash-review-main>ul>li:lt('+x+')').show();
   jQuery('#review_btn').click(function () {
	    x= (x+5 <= size_li) ? x+5 : size_li;
		jQuery('.dash-review-main>ul>li:lt('+x+')').show();
		new_size=size_li-x;
		if(new_size > 0)
		{
			jQuery('#review_btn').html(new_size + ' More...');
		}
		 else 
		 {
			jQuery('#review_btn').hide();
		 }
    });
	
	//save id in 
	jQuery('.single_add_to_cart_button').click(function(){
		var id=jQuery('.job_listing').attr('id');
		var exact_id=id.substring(5);
		 jQuery.post(ajaxurl, {'action':'set_listing_id','id': exact_id});
	});
	
	
  
});


//redirect to host page on click of button
jQuery('.link-host-btn').click(function(){
window.location.href=window.location.host;
});

// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}



