!(function(){
	$(document).on('click', '#login-tab .code-span', function(event) {
		$('#login-tab span').removeClass('on');
		$(this).addClass('on');
		$('#login-pwd').hide();
		$('#login-code').show();
	});
	$(document).on('click', '#login-tab .pwd-span', function(event) {
		$('#login-tab span').removeClass('on');
		$(this).addClass('on');
		$('#login-code').hide();
		$('#login-pwd').show();
	});
	$(document).on('click', '#pwd-submit', function(event) {
		var phone = $("#login-pwd input[name='mobile']").val();
		var pwd = $("#login-pwd input[name='password']").val();
		if(!checkPhone(phone)){
	        showError('登录手机号格式不正确');
	        return;
	    }
	    if(!checkEmpty(pwd)){
	        showError('密码不能为空');
	        return;
	    }
	    var url = getUrl('index.php?act=index&op=login');
	    var _csrf = $(".login-wrap input[name='_csrf_token']").val();
	    $.ajax({
	        type: 'POST',
	        data: { phone: phone, password: pwd, _csrf_token: _csrf},
	        url: url,
	        dataType: 'json',
	        success: function(data) {
	        	console.log(data);
	           if(data.code == 0){//成功
	           		location.href = 'index.php';
	           }else{
	           		showError(data.data);
	           }
	        }
	    });
	});
	//发送验证码
	$(document).on('click', '#captcha-btn', function(event) {
		var phone = $("#login-code input[name='mobile']").val();
		if(!checkPhone(phone)){
	        showError('登录手机号格式不正确');
	        return;
	    }
	    var url = getUrl('index.php?act=index&op=send_code');
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
	        data: { 'phone': phone},
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
	//验证码登录
	$(document).on('click', '#code-submit', function(event) {
		var phone = $("#login-code input[name='mobile']").val();
		var captcha = $("#login-code input[name='captcha']").val();
		if(!checkPhone(phone)){
	        showError('登录手机号格式不正确');
	        return;
	    }
	    if(!checkEmpty(captcha)){
	        showError('验证码不能为空');
	        return;
	    }
	    var url = getUrl('index.php?act=index&op=captcha_login');
	    var _csrf = $(".login-wrap input[name='_csrf_token']").val();
	    $.ajax({
	        type: 'POST',
	        data: { 'phone': phone, 'captcha': captcha, '_csrf_token': _csrf},
	        url: url,
	        dataType: 'json',
	        success: function(data) {
	           if(data.code == 0){//成功
	           		location.href = 'index.php';
	           }else{
	           		showError(data.data);
	           }
	        }
	    });
	});
}());
function showError(message){
	if($('#login-pwd').is(':visible')){//登录框显示
		var span = $('#login-pwd .error-span');
	}else{
		var span = $('#login-code .error-span');
	}
    span.html(message);
	span.show();
}
function hideError(){
	var span = $('.error-span');
	span.hide();
}