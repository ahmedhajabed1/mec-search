/**
 * MEC Organizer Manager - Multi-Step Registration Form
 */

(function($) {
    'use strict';

    // Default translations in case mecom_reg is not defined
    const defaults = {
        ajax_url: '',
        nonce: '',
        recaptcha_enabled: false,
        twilio_enabled: false,
        i18n: {
            submitting: 'Submitting...',
            error: 'An error occurred. Please try again.',
            required: 'This field is required',
            invalid_email: 'Please enter a valid email address',
            invalid_phone: 'Please enter a valid phone number',
            password_short: 'Password must be at least 8 characters',
            recaptcha_required: 'Please complete the reCAPTCHA',
            confirm_cancel: 'Are you sure you want to cancel?',
            social_placeholder: 'https://...',
            sending_code: 'Sending verification code...',
            code_sent: 'Verification code sent!',
            verifying: 'Verifying...',
            invalid_code: 'Invalid verification code',
            code_expired: 'Code expired. Please request a new one.',
            resend_in: 'Resend in'
        }
    };

    // Merge with localized data
    const settings = typeof mecom_reg !== 'undefined' ? $.extend(true, {}, defaults, mecom_reg) : defaults;

    const MECOMRegistration = {
        form: null,
        currentStep: 1,
        totalSteps: 5,
        phoneVerified: false,
        resendCountdown: 0,
        resendTimer: null,
        
        init: function() {
            console.log('MECOM Registration: Initializing...');
            this.form = $('#mecom-host-registration-form');
            console.log('MECOM Registration: Form element found:', this.form.length);
            if (!this.form.length) {
                console.log('MECOM Registration: Form not found, exiting init');
                return;
            }
            
            console.log('MECOM Registration: Form found, binding events');
            this.bindEvents();
            this.initWordCounters();
            console.log('MECOM Registration: Init complete');
        },
        
        bindEvents: function() {
            const self = this;
            
            console.log('MECOM Registration: Binding events');
            console.log('MECOM Registration: Form found:', this.form.length > 0);
            console.log('MECOM Registration: Settings:', settings);
            
            // Next button
            $(document).on('click', '.mecom-btn-next', function(e) {
                e.preventDefault();
                console.log('MECOM Registration: Next button clicked');
                const nextStep = parseInt($(this).data('next'), 10);
                console.log('MECOM Registration: Next step:', nextStep);
                
                const isValid = self.validateCurrentStep();
                console.log('MECOM Registration: Validation result:', isValid);
                
                if (isValid) {
                    // If going to phone verification step (step 2) and Twilio is enabled, send SMS
                    if (nextStep === 2 && settings.twilio_enabled) {
                        self.sendVerificationCode();
                    } else {
                        self.goToStep(nextStep);
                    }
                }
            });
            
            // Verify button on step 2
            $(document).on('click', '.mecom-btn-verify', function(e) {
                e.preventDefault();
                self.verifyCode();
            });
            
            // Resend code link
            $(document).on('click', '.mecom-resend-code', function(e) {
                e.preventDefault();
                if (self.resendCountdown <= 0) {
                    self.sendVerificationCode();
                }
            });
            
            // Change phone link
            $(document).on('click', '.mecom-change-phone', function(e) {
                e.preventDefault();
                self.goToStep(1);
                // Focus on phone field
                setTimeout(function() {
                    $('#mecom_phone').focus();
                }, 300);
            });
            
            // Previous button
            $(document).on('click', '.mecom-btn-prev', function(e) {
                e.preventDefault();
                const prevStep = parseInt($(this).data('prev'), 10);
                self.goToStep(prevStep);
            });
            
            // Form submit
            this.form.on('submit', function(e) {
                console.log('MECOM Registration: Form submit triggered');
                e.preventDefault();
                e.stopPropagation();
                console.log('MECOM Registration: Default prevented');
                if (self.validateCurrentStep()) {
                    console.log('MECOM Registration: Validation passed, submitting');
                    self.submitForm();
                } else {
                    console.log('MECOM Registration: Validation failed on submit');
                }
                return false;
            });
            
            // Submit button click (backup handler)
            $(document).on('click', '.mecom-btn-submit', function(e) {
                console.log('MECOM Registration: Submit button clicked');
                e.preventDefault();
                if (self.validateCurrentStep()) {
                    console.log('MECOM Registration: Validation passed, submitting via button');
                    self.submitForm();
                } else {
                    console.log('MECOM Registration: Validation failed on button click');
                }
            });
            
            // Cancel button
            $(document).on('click', '.mecom-btn-cancel', function(e) {
                e.preventDefault();
                if (confirm(settings.i18n.confirm_cancel)) {
                    window.history.back();
                }
            });
            
            // Close modal button
            $(document).on('click', '.mecom-modal-close', function(e) {
                e.preventDefault();
                if (confirm(settings.i18n.confirm_cancel)) {
                    window.history.back();
                }
            });
            
            // Password toggle
            $(document).on('click', '.mecom-password-toggle', function() {
                const $input = $(this).siblings('input');
                const $eyeOpen = $(this).find('.eye-open');
                const $eyeClosed = $(this).find('.eye-closed');
                
                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $eyeOpen.hide();
                    $eyeClosed.show();
                } else {
                    $input.attr('type', 'password');
                    $eyeOpen.show();
                    $eyeClosed.hide();
                }
            });
            
            // Add social link
            $(document).on('click', '.mecom-add-social-btn', function() {
                const $wrapper = $(this).siblings('.mecom-social-links-wrapper');
                const $newRow = $('<div class="mecom-social-link-row"><input type="url" name="social_links[]" placeholder="' + settings.i18n.social_placeholder + '"></div>');
                $wrapper.append($newRow);
                $newRow.find('input').focus();
            });
            
            // Phone country change - update display
            $('#mecom_phone_country, #mecom_phone').on('change keyup', function() {
                self.updatePhoneDisplay();
            });
            
            // Verification code input auto-advance
            $(document).on('input', '.mecom-code-input', function() {
                const $this = $(this);
                const index = $this.data('index');
                
                if ($this.val().length === 1 && index < 3) {
                    $this.closest('.mecom-verification-code')
                        .find('.mecom-code-input[data-index="' + (index + 1) + '"]')
                        .focus();
                }
            });
            
            // Backspace on code input
            $(document).on('keydown', '.mecom-code-input', function(e) {
                const $this = $(this);
                const index = $this.data('index');
                
                if (e.keyCode === 8 && $this.val() === '' && index > 0) {
                    $this.closest('.mecom-verification-code')
                        .find('.mecom-code-input[data-index="' + (index - 1) + '"]')
                        .focus();
                }
            });
            
            // Clear error on input
            $(document).on('focus', '.mecom-form-group input, .mecom-form-group select, .mecom-form-group textarea', function() {
                $(this).closest('.mecom-form-group').removeClass('has-error');
                $(this).siblings('.mecom-error-message').remove();
            });
        },
        
        initWordCounters: function() {
            const self = this;
            
            // Fun facts counter
            $('#mecom_fun_facts').on('input', function() {
                const words = self.countWords($(this).val());
                $('#fun-facts-count').text(words);
                
                if (words > 250) {
                    $(this).closest('.mecom-form-group').addClass('has-error');
                } else {
                    $(this).closest('.mecom-form-group').removeClass('has-error');
                }
            });
            
            // Description counter
            $('#mecom_description').on('input', function() {
                const words = self.countWords($(this).val());
                $('#description-count').text(words);
                
                if (words > 750) {
                    $(this).closest('.mecom-form-group').addClass('has-error');
                } else {
                    $(this).closest('.mecom-form-group').removeClass('has-error');
                }
            });
        },
        
        countWords: function(text) {
            if (!text || !text.trim()) return 0;
            return text.trim().split(/\s+/).length;
        },
        
        updatePhoneDisplay: function() {
            const country = $('#mecom_phone_country').val() || '+1';
            const phone = $('#mecom_phone').val() || '';
            const formatted = country + ' ' + phone.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
            $('#mecom-display-phone').text(formatted);
        },
        
        goToStep: function(step) {
            // Hide all steps
            this.form.find('.mecom-form-step').hide().removeClass('active');
            
            // Show target step
            this.form.find('.mecom-form-step[data-step="' + step + '"]').show().addClass('active');
            
            this.currentStep = step;
            
            // Update phone display if going to step 2
            if (step === 2) {
                this.updatePhoneDisplay();
            }
            
            // Scroll to top of form
            $('html, body').animate({
                scrollTop: this.form.offset().top - 50
            }, 300);
        },
        
        validateCurrentStep: function() {
            console.log('MECOM Registration: Validating step', this.currentStep);
            const $step = this.form.find('.mecom-form-step[data-step="' + this.currentStep + '"]');
            console.log('MECOM Registration: Step element found:', $step.length > 0);
            let isValid = true;
            let firstError = null;
            
            // Clear previous errors
            $step.find('.mecom-form-group').removeClass('has-error');
            $step.find('.mecom-error-message').remove();
            
            // Step 1 validation
            if (this.currentStep === 1) {
                // Name
                const $name = $('#mecom_name');
                console.log('MECOM Registration: Name field value:', $name.val());
                if (!$name.val() || !$name.val().trim()) {
                    this.showError($name, settings.i18n.required);
                    if (!firstError) firstError = $name;
                    isValid = false;
                    console.log('MECOM Registration: Name validation failed');
                }
                
                // Email
                const $email = $('#mecom_email');
                console.log('MECOM Registration: Email field value:', $email.val());
                if (!$email.val() || !$email.val().trim()) {
                    this.showError($email, settings.i18n.required);
                    if (!firstError) firstError = $email;
                    isValid = false;
                    console.log('MECOM Registration: Email empty validation failed');
                } else if (!this.isValidEmail($email.val())) {
                    this.showError($email, settings.i18n.invalid_email);
                    if (!firstError) firstError = $email;
                    isValid = false;
                    console.log('MECOM Registration: Email format validation failed');
                }
                
                // Phone
                const $phone = $('#mecom_phone');
                console.log('MECOM Registration: Phone field value:', $phone.val());
                if (!$phone.val() || !$phone.val().trim()) {
                    this.showError($phone, settings.i18n.required);
                    if (!firstError) firstError = $phone;
                    isValid = false;
                    console.log('MECOM Registration: Phone validation failed');
                }
                
                // Location
                const $location = $('#mecom_location');
                console.log('MECOM Registration: Location field value:', $location.val());
                if (!$location.val() || !$location.val().trim()) {
                    this.showError($location, settings.i18n.required);
                    if (!firstError) firstError = $location;
                    isValid = false;
                    console.log('MECOM Registration: Location validation failed');
                }
                
                // Password
                const $password = $('#mecom_password');
                console.log('MECOM Registration: Password field length:', $password.val() ? $password.val().length : 0);
                if (!$password.val()) {
                    this.showError($password, settings.i18n.required);
                    if (!firstError) firstError = $password;
                    isValid = false;
                    console.log('MECOM Registration: Password empty validation failed');
                } else if ($password.val().length < 8) {
                    this.showError($password, settings.i18n.password_short);
                    if (!firstError) firstError = $password;
                    isValid = false;
                    console.log('MECOM Registration: Password length validation failed');
                }
                
                // reCAPTCHA - only check if enabled AND grecaptcha exists AND there's a reCAPTCHA element
                console.log('MECOM Registration: reCAPTCHA enabled:', settings.recaptcha_enabled);
                console.log('MECOM Registration: grecaptcha exists:', typeof grecaptcha !== 'undefined');
                console.log('MECOM Registration: g-recaptcha element exists:', $('.g-recaptcha').length > 0);
                if (settings.recaptcha_enabled === true && typeof grecaptcha !== 'undefined' && $('.g-recaptcha').length > 0) {
                    try {
                        const response = grecaptcha.getResponse();
                        console.log('MECOM Registration: reCAPTCHA response:', response ? 'received' : 'empty');
                        if (!response) {
                            this.showError($('.g-recaptcha'), settings.i18n.recaptcha_required);
                            isValid = false;
                            console.log('MECOM Registration: reCAPTCHA validation failed');
                        }
                    } catch (e) {
                        // reCAPTCHA not ready, skip validation
                        console.log('reCAPTCHA not ready:', e);
                    }
                }
            }
            
            console.log('MECOM Registration: Final validation result:', isValid);
            
            // Step 4 validation (word counts)
            if (this.currentStep === 4) {
                const funFactsWords = this.countWords($('#mecom_fun_facts').val());
                const descWords = this.countWords($('#mecom_description').val());
                
                if (funFactsWords > 250) {
                    this.showError($('#mecom_fun_facts'), 'Maximum 250 words allowed');
                    isValid = false;
                }
                
                if (descWords > 750) {
                    this.showError($('#mecom_description'), 'Maximum 750 words allowed');
                    isValid = false;
                }
            }
            
            // Focus first error field
            if (firstError) {
                firstError.focus();
            }
            
            return isValid;
        },
        
        showError: function($element, message) {
            const $group = $element.closest('.mecom-form-group');
            if (!$group.hasClass('has-error')) {
                $group.addClass('has-error');
                $group.append('<span class="mecom-error-message">' + message + '</span>');
            }
        },
        
        isValidEmail: function(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },
        
        /**
         * Send SMS verification code
         */
        sendVerificationCode: function() {
            const self = this;
            const phone = $('#mecom_phone').val();
            const phoneCountry = $('#mecom_phone_country').val();
            
            console.log('MECOM Registration: Sending verification code to', phoneCountry + phone);
            
            // Show loading state on button
            const $btn = this.form.find('.mecom-btn-next[data-next="2"]');
            const originalText = $btn.text();
            $btn.prop('disabled', true).text(settings.i18n.sending_code);
            
            $.ajax({
                url: settings.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecom_send_sms_code',
                    nonce: settings.nonce,
                    phone: phone,
                    phone_country: phoneCountry
                },
                success: function(response) {
                    console.log('MECOM Registration: SMS response:', response);
                    $btn.prop('disabled', false).text(originalText);
                    
                    if (response.success) {
                        // Go to verification step
                        self.goToStep(2);
                        
                        // Update displayed phone
                        if (response.data && response.data.phone) {
                            $('#mecom-display-phone').text(response.data.phone);
                        } else {
                            self.updatePhoneDisplay();
                        }
                        
                        // Start resend countdown
                        self.startResendCountdown(60);
                        
                        // Focus first code input
                        setTimeout(function() {
                            $('.mecom-code-input[data-index="0"]').focus();
                        }, 300);
                    } else {
                        alert(response.data || settings.i18n.error);
                    }
                },
                error: function(xhr) {
                    console.log('MECOM Registration: SMS error:', xhr);
                    $btn.prop('disabled', false).text(originalText);
                    alert(settings.i18n.error);
                }
            });
        },
        
        /**
         * Verify the entered code
         */
        verifyCode: function() {
            const self = this;
            const phone = $('#mecom_phone').val();
            const phoneCountry = $('#mecom_phone_country').val();
            
            // Get code from all input fields
            let code = '';
            $('.mecom-code-input').each(function() {
                code += $(this).val();
            });
            
            console.log('MECOM Registration: Verifying code:', code);
            
            if (code.length !== 4) {
                alert(settings.i18n.invalid_code);
                return;
            }
            
            // Show loading state
            const $btn = this.form.find('.mecom-btn-verify');
            const originalText = $btn.text();
            $btn.prop('disabled', true).text(settings.i18n.verifying);
            
            $.ajax({
                url: settings.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecom_verify_sms_code',
                    nonce: settings.nonce,
                    phone: phone,
                    phone_country: phoneCountry,
                    code: code
                },
                success: function(response) {
                    console.log('MECOM Registration: Verify response:', response);
                    $btn.prop('disabled', false).text(originalText);
                    
                    if (response.success) {
                        self.phoneVerified = true;
                        
                        // Clear countdown
                        if (self.resendTimer) {
                            clearInterval(self.resendTimer);
                        }
                        
                        // Go to next step (step 3 or step 4 depending on config)
                        const showBusiness = self.form.data('show-business') === true || self.form.data('show-business') === 'true';
                        const showProfile = self.form.data('show-profile') === true || self.form.data('show-profile') === 'true';
                        
                        if (showBusiness) {
                            self.goToStep(3);
                        } else if (showProfile) {
                            self.goToStep(4);
                        } else {
                            self.goToStep(5);
                        }
                    } else {
                        alert(response.data || settings.i18n.invalid_code);
                        // Clear code inputs
                        $('.mecom-code-input').val('');
                        $('.mecom-code-input[data-index="0"]').focus();
                    }
                },
                error: function(xhr) {
                    console.log('MECOM Registration: Verify error:', xhr);
                    $btn.prop('disabled', false).text(originalText);
                    alert(settings.i18n.error);
                }
            });
        },
        
        /**
         * Start resend countdown timer
         */
        startResendCountdown: function(seconds) {
            const self = this;
            this.resendCountdown = seconds;
            
            // Clear any existing timer
            if (this.resendTimer) {
                clearInterval(this.resendTimer);
            }
            
            // Update display
            this.updateResendDisplay();
            
            // Start countdown
            this.resendTimer = setInterval(function() {
                self.resendCountdown--;
                self.updateResendDisplay();
                
                if (self.resendCountdown <= 0) {
                    clearInterval(self.resendTimer);
                }
            }, 1000);
        },
        
        /**
         * Update resend timer display
         */
        updateResendDisplay: function() {
            const $timer = $('#mecom-resend-countdown');
            const $resendBtn = $('.mecom-resend-code');
            
            if (this.resendCountdown > 0) {
                $timer.text(this.resendCountdown);
                $resendBtn.addClass('disabled').css('pointer-events', 'none');
                $('.mecom-resend-timer').show();
            } else {
                $resendBtn.removeClass('disabled').css('pointer-events', 'auto');
                $('.mecom-resend-timer').hide();
            }
        },
        
        submitForm: function() {
            console.log('MECOM Registration: submitForm called');
            const self = this;
            const $loading = this.form.find('.mecom-form-loading');
            const $submitBtn = this.form.find('.mecom-btn-submit');
            
            console.log('MECOM Registration: Loading element found:', $loading.length);
            console.log('MECOM Registration: Submit button found:', $submitBtn.length);
            console.log('MECOM Registration: AJAX URL:', settings.ajax_url);
            console.log('MECOM Registration: Nonce:', settings.nonce);
            
            // Show loading
            $loading.show();
            $submitBtn.prop('disabled', true).text(settings.i18n.submitting);
            
            // Gather form data
            const formData = {
                action: 'mecom_register_host',
                nonce: settings.nonce,
                name: $('#mecom_name').val(),
                email: $('#mecom_email').val(),
                phone: $('#mecom_phone').val(),
                phone_country: $('#mecom_phone_country').val(),
                location: $('#mecom_location').val(),
                password: $('#mecom_password').val(),
                business_name: $('#mecom_business_name').val(),
                business_address: $('#mecom_business_address').val(),
                business_ein: $('#mecom_business_ein').val(),
                website: $('#mecom_website').val(),
                social_links: [],
                fun_facts: $('#mecom_fun_facts').val(),
                description: $('#mecom_description').val(),
                need_business_help: $('#mecom_need_business_help').val()
            };
            
            // Gather social links
            $('input[name="social_links[]"]').each(function() {
                const val = $(this).val();
                if (val && val.trim()) {
                    formData.social_links.push(val.trim());
                }
            });
            
            // reCAPTCHA response
            if (settings.recaptcha_enabled === true && typeof grecaptcha !== 'undefined') {
                try {
                    formData.recaptcha_response = grecaptcha.getResponse();
                } catch (e) {
                    console.log('reCAPTCHA error:', e);
                }
            }
            
            console.log('MECOM Registration: Sending AJAX request');
            console.log('MECOM Registration: Form data:', formData);
            
            $.ajax({
                url: settings.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log('MECOM Registration: AJAX success');
                    console.log('MECOM Registration: Response:', response);
                    $loading.hide();
                    
                    if (response.success) {
                        console.log('MECOM Registration: Response success, going to step 5');
                        // Show thank you step
                        self.goToStep(5);
                        
                        // If instant approval (no pending), redirect after delay
                        if (response.data && !response.data.pending && response.data.redirect) {
                            console.log('MECOM Registration: Will redirect in 3s to:', response.data.redirect);
                            setTimeout(function() {
                                window.location.href = response.data.redirect;
                            }, 3000);
                        }
                    } else {
                        console.log('MECOM Registration: Response error:', response.data);
                        alert(response.data || settings.i18n.error);
                        $submitBtn.prop('disabled', false).text('Finish');
                        
                        // Reset reCAPTCHA
                        if (typeof grecaptcha !== 'undefined') {
                            try {
                                grecaptcha.reset();
                            } catch (e) {}
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.log('MECOM Registration: AJAX error');
                    console.log('MECOM Registration: Status:', status);
                    console.log('MECOM Registration: Error:', error);
                    console.log('MECOM Registration: XHR responseText:', xhr.responseText);
                    $loading.hide();
                    
                    // Show more helpful error message
                    let errorMsg = settings.i18n.error;
                    if (xhr.responseText) {
                        console.log('MECOM Registration: Server response:', xhr.responseText.substring(0, 500));
                        // If it looks like a PHP error, show a friendly message
                        if (xhr.responseText.indexOf('Fatal error') !== -1 || xhr.responseText.indexOf('Parse error') !== -1) {
                            errorMsg = 'Server error. Please contact support.';
                        }
                    }
                    
                    alert(errorMsg);
                    $submitBtn.prop('disabled', false).text('Finish');
                    
                    // Reset reCAPTCHA
                    if (typeof grecaptcha !== 'undefined') {
                        try {
                            grecaptcha.reset();
                        } catch (e) {}
                    }
                }
            });
        }
    };
    
    $(document).ready(function() {
        console.log('MECOM Registration: Document ready');
        MECOMRegistration.init();
    });
    
    // Also try to init after a delay for Elementor
    $(window).on('load', function() {
        console.log('MECOM Registration: Window load');
        if (!MECOMRegistration.form || !MECOMRegistration.form.length) {
            console.log('MECOM Registration: Retrying init on window load');
            MECOMRegistration.init();
        }
    });
    
    // Elementor frontend init hook
    $(window).on('elementor/frontend/init', function() {
        console.log('MECOM Registration: Elementor frontend init');
        elementorFrontend.hooks.addAction('frontend/element_ready/mecom-host-registration.default', function($scope) {
            console.log('MECOM Registration: Widget ready, reinitializing');
            MECOMRegistration.form = $scope.find('#mecom-host-registration-form');
            if (MECOMRegistration.form.length) {
                MECOMRegistration.initWordCounters();
            }
        });
    });
    
})(jQuery);
