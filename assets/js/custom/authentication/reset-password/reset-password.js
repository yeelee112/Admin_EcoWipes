"use strict";

// Class Definition
var KTAuthResetPassword = function() {
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
					'email': {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: 'The value is not a valid email address',
                            },
							notEmpty: {
								message: 'Email address is required'
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
            var email = document.querySelector('#kt_password_reset_form').querySelector('[name="email"]').value;
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
                            method: "GET",
                            url: "/account/processSendEmail",
                            data: {email: email}
                        })
                        .done(function(data) {
                            if(data == true){
                                submitButton.removeAttribute('data-kt-indicator');
                                Swal.fire({
                                    text: "Hệ thống đã gửi email vào hộp thư của bạn! Hãy kiểm tra chúng nào.",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Được rồi đi thôi",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) { 
                                        form.querySelector('[name="email"]').value= "";                          
                                        //form.submit();
                                    }
                                });
                            }
                            else{
                                Swal.fire({
                                    text: "Email chưa được sử dụng, vui lòng sử dụng email khác!",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Để tui thử lại",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }); 
                                submitButton.setAttribute('data-kt-indicator', 'off');
                            }
                        });
                        // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                        
                    }, 0);   						
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
            form = document.querySelector('#kt_password_reset_form');
            submitButton = document.querySelector('#kt_password_reset_submit');

            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTAuthResetPassword.init();
});
