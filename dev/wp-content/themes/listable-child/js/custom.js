//add and remove class on scroll
jQuery(function() {


    //caches a jQuery object containing the header element
    var header = jQuery(".site-header");
    jQuery(window).scroll(function() {
        var scroll = jQuery(window).scrollTop();

        if (scroll >= 70) {
            header.addClass("header_fxd");
        } else {
            header.removeClass("header_fxd");
        }
    });
	
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
		fname: { required:  "Please enter your first name"},
		lname: {required : "Please enter your last name"},
		phone: {
				required: "Please enter a phone number",
				digits: "Please enter a valid number",
				maxlength: "Please enter a valid number"
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
   job_title: {required: true,maxlength: 10,},
   price: {required: true,digits: true,maxlength: 5},
   job_location : {maxlength: 50},
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


	
});

//redirect to host page on click of button
jQuery('.link-host-btn').click(function(){
window.location.href=window.location.host;
});



