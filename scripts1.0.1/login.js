var Login = {
    Validated: false,
	Login: function() {
		$.ajax({
			dataType: 'json',
			type: "POST",
			url: "proc/controller.php",
			data: { func: "login", email: $('#loginEmail').val(), passwd: $('#loginPassword').val() }
		}).done(function(data) {
			if(data && !data.error){
				//document.location.href = "/";
                Login.Validated = true;
                $('#loginForm').submit();
			}else{
				if(data && data.error){
					Toolbox.ShowFeedback('feedbackContainer','error',data.error);
				}else{
					Toolbox.ShowFeedback('feedbackContainer','error','Unexpected error');
				}
			}
		});
	}
}

$( document ).ready(function() {
	$("#loginEmail").focus();

	$(document).keypress(function(e){
			if(e.which==13){
				Login.Login();
			}
	});

    $('#loginForm').submit(function() {
        //alert(Login.Validated);
        if(Login.Validated == false){
            return false;
        }else{
            $("#loginBtnIngresar").click()
        }
    });
});
