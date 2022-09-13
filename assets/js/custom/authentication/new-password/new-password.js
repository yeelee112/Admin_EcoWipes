"use strict";

// Class Definition
var KTAuthNewPassword = function() {
    // Elements
    var form;
    var submitButton;
	var validator;

    var handleForm = function(e) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
			form,
			{
				fields: {					
					'password': {
                        validators: {
                            notEmpty: {
                                message: 'Vui lòng nhập mật khẩu!'
                            },
                            callback: {
                                message: 'Vui lòng nhập mật khẩu phù hợp!',
                                callback: function(input) {
                                    if (input.value.length > 8) {
                                        return validatePassword();
                                    }
                                }
                            }
                        }
                    },
                    'confirm-password': {
                        validators: {
                            notEmpty: {
                                message: 'Vui lòng xác nhận lại mật khẩu!'
                            },
                            identical: {
                                compare: function() {
                                    return form.querySelector('[name="password"]').value;
                                },
                                message: 'Mật khẩu không giống nhau!'
                            }
                        }
                    }
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
						eleInvalidClass: '',  // comment to enable invalid state icons
                        eleValidClass: '' // comment to enable valid state icons
                    })
				}
			}
		);		

        submitButton.addEventListener('click', function (e) {
            e.preventDefault();
            var email = document.querySelector('#kt_new_password_form').querySelector('[name="email"]').value;
            var token = document.querySelector('#kt_new_password_form').querySelector('[name="token"]').value;
            var password = document.querySelector('#kt_new_password_form').querySelector('[name="password"]').value;
            var confirmPassword = document.querySelector('#kt_new_password_form').querySelector('[name="confirm-password"]').value;
            // Validate form
            validator.validate().then(function (status) {
                if (status == 'Valid') {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click 
                    submitButton.disabled = true;

                    // Simulate ajax request
                    setTimeout(function() {
                        // Hide loading indication

                        // Enable button
                        submitButton.disabled = false;
                        $.ajax({
                            method: "POST",
                            url: "/account/processNewPassword",
                            data: {email: email, token: token, password: password, cpassword: confirmPassword}
                        })
                        .done(function(data) {
                            if(data == true){
                                submitButton.removeAttribute('data-kt-indicator');
                                Swal.fire({
                                    text: "Cập nhật mật khẩu thành công!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Chuyển về trang đăng nhập",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) { 
                                        form.querySelector('[name="password"]').value= "";                          
                                        form.querySelector('[name="confirm-password"]').value= "";                          
                                        //form.submit();
                                        var redirectUrl = form.getAttribute('data-kt-redirect-url');
                                        if (redirectUrl) {
                                            location.href = redirectUrl;
                                        }
                                    }
                                });
                            }
                            else{
                                Swal.fire({
                                    text: "Cập nhật mật khẩu không thành công. Vui lòng thử lại!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Thử lại thôi",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) { 
                                        form.querySelector('[name="password"]').value= "";                          
                                        form.querySelector('[name="confirm-password"]').value= "";                          
                                        //form.submit();
                                    }
                                });
                            }
                        });
                        // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                        
                    }, 1000);   						
                } else {
                    // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                    Swal.fire({
                        text: "Có một vài lỗi đang xuất hiện nơi đây, vui lòng thử lại!",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Để tui thử lại",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });  
		});
    }

    // Public Functions
    return {
        // public functions
        init: function() {
            form = document.querySelector('#kt_new_password_form');
            submitButton = document.querySelector('#kt_new_password_submit');

            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTAuthNewPassword.init();
});
