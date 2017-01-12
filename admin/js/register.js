!(function(){
	//发送验证码
	$(document).on('click', '#captcha-btn', function(event) {
		var phone = $("#register-form input[name='mobile']").val();
		if(!checkPhone(phone)){
	        showError('登录手机号格式不正确');
	        return;
	    }
	    var url = getUrl('index.php?act=cooperation&op=send_code');
	    var captchaBtn = $('#captcha-btn'),
		    captchaState = false,
		    captchaTimer,
		    countdownTime,
			countdown = function() {
		        if (countdownTime > 0) {
		            captchaBtn.text(countdownTime + "秒后重新点击发送");
		            countdownTime--;
		        } else {
		            captchaBtn.html("&nbsp;发送验证码&nbsp;");
		            clearInterval(captchaTimer);
		            captchaBtn.removeAttr('disabled');
		            captchaState = false;
		        }
		    };
	    $.ajax({
	        type: 'POST',
	        data: { mobile: phone},
	        url: url,
	        dataType: 'json',
	        beforeSend: function() {
	        	hideError();
	        	$('#captcha-btn').text('请稍候...');
	        },
	        success: function(data) {
	           if(data.success == true){//成功
	           		captchaBtn.attr('disabled', 'disabled');
		            captchaState = true;
		            countdownTime = 60;
		            countdown();
		            captchaTimer = setInterval(countdown, 1000);
	           }else{
	           		$('#captcha-btn').html('&nbsp;发送验证码&nbsp;');
	           		showError(data.data);
	           }
	        }
	    });
	});
	//注册
	$(document).on('click', '#register-submit', function(event) {
		var phone = $("#register-form input[name='mobile']").val();
		if(!checkPhone(phone)){
	        showError('登录手机号格式不正确');
	        return;
	    }
	    var captcha_code = $("#register-form input[name='captcha']").val();
	    if(!checkEmpty(captcha_code)){
	        showError('验证码不能为空');
	        return;
	    }
	    var pwd = $("#register-form input[name='password']").val();
	    if(!checkEmpty(pwd)){
	        showError('密码不能为空');
	        return;
	    }
	    if(!checkPassword(pwd)){
	        showError('密码只能为6到12位字母或数字');
	        return;
	    }
	    var url = getUrl('index.php?act=cooperation&op=register');
	    var _csrf = $(".login-wrap input[name='_csrf_token']").val();
	    $.ajax({
	        type: 'POST',
	        data: { mobile: phone, code: captcha_code, password: pwd, _csrf_token: _csrf},
	        url: url,
	        dataType: 'json',
	        beforeSend: function(){
	        	hideError();
	        	$('#register-submit').val('请稍后');
	        },
	        success: function(data) {
	           if(data.success == true){//成功
	           		var url = getUrl('index.php?act=cooperation&op=progress');
	           		location.href = url;
	           }else{
	           		showError(data.data);
	           }
	        }
	    });
	}); 
}());
function showError(message){
	var span = $('#register-form .error-span');
    span.html(message);
	span.show();
}
function hideError(){
	var span = $('#register-form .error-span');
	span.hide();
}