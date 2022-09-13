"use strict";

// Class definition
var KTSigninGeneral = function() {
    // Elements
    var form;
    var submitButton;
    var validator;

    // Handle form
    var handleForm = function(e) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
			form,
			{
				fields: {					
					'email': {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: 'Hãy nhập đúng định dạng email!',
                            },
							notEmpty: {
								message: 'Vui lòng điền nhập email!'
							}
						}
					},
                    'password': {
                        validators: {
                            notEmpty: {
                                message: 'Vui lòng nhập mật khẩu!'
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

        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            // Prevent button default action
            e.preventDefault();

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
                        submitButton.removeAttribute('data-kt-indicator');

                        // Enable button
                        submitButton.disabled = false;

                        // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                        var emailSignin = document.querySelector('#kt_sign_in_form').querySelector('[name="email"]').value;
                        var passwordSignin = document.querySelector('#kt_sign_in_form').querySelector('[name="password"]').value;

                        $.ajax({
                            method: "POST",
                            url: "../../../../../../account/processSignin",
                            data: { email: emailSignin, password:passwordSignin }
                        })
                        .done(function(data) {
                            if(data == true){
                                Swal.fire({
                                    text: "Đăng nhập thành công!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Vào trang chủ",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) { 
                                        form.querySelector('[name="email"]').value= "";
                                        form.querySelector('[name="password"]').value= "";  
                                                                      
                                        //form.submit(); // submit form
                                        var redirectUrl = form.getAttribute('data-kt-redirect-url');
                                        if (redirectUrl) {
                                            location.href = redirectUrl;
                                        }
                                    }
                                });
                            }
                            else{
                                Swal.fire({
                                    text: "Vui lòng nhập chính xác email và mật khẩu của bạn.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, để tui nhập lại.",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        });
                    }, 2000);   						
                } else {
                    // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                    Swal.fire({
                        text: "Vui lòng nhập chính xác email và mật khẩu của bạn.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, để tui nhập lại.",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
		});
    }

    // Public functions
    return {
        // Initialization
        init: function() {
            form =  document.querySelector('#kt_sign_in_form');
            submitButton = document.querySelector('#kt_sign_in_submit');
            
            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTSigninGeneral.init();
});
