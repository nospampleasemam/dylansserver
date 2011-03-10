$(document).ready(function() {
  $('.submit').click(function() {
    if ($('#comment_text').val() != '') {
	 var challenge = Recaptcha.get_challenge();
	 var response = Recaptcha.get_response();
     var captcha_data = { "challenge" : challenge,
	 				     "response" : response};
     $.ajax({  
       type: "GET",  
       url: "/captcha",  
       data: captcha_data,  
       success: function(data) {  
	     if (data.split('\n')[0] == 'true') {
	       var name = $("#comment_name").val();
	       var email = $("#comment_email").val();
	       var text = $("#comment_text").val();
           var comment_data = { "captcha" : "passed",
		                        "name" : name,
                                "email" : email,
                                "text" : text};
		   $.ajax({
		     type: "POST",
			 // the url may need to be adjusted for
			 // trailing slashes
			 url: "verify",
			 data: comment_data,
			 success: function() {
			   console.log('posted new comment');
			 }
		   });
		 } else {
		   console.log('reCAPTCHA said you\'re not human.');
		 }
       },
	   error: function() {
	     console.log('error');
	   },  
	   complete: function() {
	     Recaptcha.destroy();
	   }
     }); 
	} else {
	  console.log('but you didn\'t write anything!');
	}
	return false;
  });
});
