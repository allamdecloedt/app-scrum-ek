<div class="login-section nav-link ">
    <!-- Login Section -->
    <div class="login-dropdown hidden-section display-none">
        <svg class="login-exit-svg" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
            class="bi bi-box-arrow-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
            <path fill-rule="evenodd"
                d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
        </svg>
        <form class="login-form mt-10" id="login-form" action="<?php echo site_url('login/validate_login_frontend'); ?>"
            method="post">
            <!-- Champ caché pour le jeton CSRF -->
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

            <div class="mb-4 mt-4 login-input">
                <label for="loginEmail"
                    class="login-input-label text-uppercase"><?php echo get_phrase("e-mail") ?><span class="required"> * </span></label>
                <input type="email" class="form-control shadow-none" id="loginEmail" aria-describedby="emailHelp"
                    name="login_email">

            </div>
            <div class="mb-3 login-input">
                <label for="loginPassword"
                    class=" login-input-label text-uppercase"><?php echo get_phrase("password") ?><span class="required"> * </span></label>
                <input type="password" class="form-control shadow-none" id="loginPassword" name="login_password">
            </div>
            <button type="submit" id="loginSubmit"
                class="login-button text-uppercase mb-3"><?php echo get_phrase("login") ?></button>
        </form>
        <a class="register-phrase text-uppercase">
            <?php echo get_phrase("no account yet? ") ?>
            <span class="ml-1  register-link"><span>(</span> <?php echo get_phrase("register") ?><span
                    class="ml-1">)</span></span>
        </a>

        <a class="forget-phrase text-uppercase">
            <?php echo get_phrase("Forgot account?") ?>
            <span class="ml-1  forget-link"><span>(</span> <?php echo get_phrase("forget password") ?><span
                    class="ml-1">)</span></span>
        </a>
    </div>

    <!-- Register Section -->
    <div class="register-dropdown hidden-section display-none">
        <svg class="register-exit-svg" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
            class="bi bi-box-arrow-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
            <path fill-rule="evenodd"
                d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
        </svg>
        <a class="text-uppercase">
            <span class="login-link">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-arrow-bar-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5M10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5" />
                </svg>
                <?php echo get_phrase("login") ?>
            </span>
        </a>

        <div class="round-indicator section-1-indicator step indicator-active"><span>1</span></div>
        <div class="line-1"></div>
        <div class="round-indicator section-2-indicator step"><span>2</span></div>
        <div class="line-2"></div>
        <div class="round-indicator section-3-indicator step"><span>3</span></div>

        <a class="register-back text-uppercase" id="prevBtn" onclick="nextPrev(-1)"><?php echo get_phrase("back") ?></a>
        <a class="register-next text-uppercase" id="nextBtn" onclick="nextPrev(1)"><?php echo get_phrase("next") ?></a>

        <form class="register-form mt-10" id="register-form" method="post" enctype="multipart/form-data"
            action="<?php echo site_url('register/register_user'); ?>">

            <!-- Champ caché pour le jeton CSRF -->
             <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

            <!-- First Register Form Section -->
            <div class="tab">
                <div class="mb-4 login-input">
                    <label for="registerFirstName"
                        class="login-input-label text-uppercase"><?php echo get_phrase("first name") ?><span class="required"> * </span></label>
                    <input type="text" class="form-control shadow-none information" id="registerFirstName"
                        name="register_first_name" required data-msg="<?php echo get_phrase("required") ?>">
                </div>
                <div class="mb-4 login-input">
                    <label for="registerLastName"
                        class=" login-input-label text-uppercase"><?php echo get_phrase("last name") ?><span class="required"> * </span></label>
                    <input id="registerLastName" type="text" class="form-control shadow-none information" id=""
                        name="register_last_name" required data-msg="<?php echo get_phrase("required") ?>">
                </div>
                <div class="mb-4 login-input">
                    <label for="registerEmail"
                        class=" login-input-label text-uppercase"><?php echo get_phrase("e-mail") ?><span class="required"> * </span></label>
                    <input id="registerEmail" type="email" class="form-control shadow-none information" id=""
                        name="register_email" required data-msg="<?php echo get_phrase("required") ?>">
                </div>
            </div>
            <!-- First Register Form Section End-->

            <!-- Second Register Form Section -->
            <div class="tab">

                <div class="mb-4 login-input">
                    <label for="register_date_of_birth"
                        class=" login-input-label text-uppercase"><?php echo get_phrase("date of birth") ?><span class="required"> * </span></label>
                    <input onfocus="'showPicker' in this && this.showPicker()" type="date"
                        class="form-control  shadow-none information" id="register_date_of_birth"
                        name="register_date_of_birth" required data-msg="<?php echo get_phrase("required") ?>">
                </div>
                <div class="mb-4 login-input">
                    <label for="register_gender"
                        class=" login-input-label text-uppercase"><?php echo get_phrase("gender") ?><span class="required"> * </span></label>
                    <select name="register_gender" id="register_gender"
                        class="form-control rounded-end shadow-none information" required
                        data-msg="<?php echo get_phrase("required") ?>">
                        <option value=""><?php echo get_phrase('select_your_gender'); ?></option>
                        <option value="Male"><?php echo get_phrase('male'); ?></option>
                        <option value="Female"><?php echo get_phrase('female'); ?></option>
                        <option value="Others"><?php echo get_phrase('others'); ?></option>
                    </select>
                </div>
                <div class="login-input">
                    <label for="student_image_upload" class="btn btn-sm student-img-button-label form-label text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                            class="bi bi-filetype-png" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M14 4.5V14a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zm-3.76 8.132q.114.23.14.492h-.776a.8.8 0 0 0-.097-.249.7.7 0 0 0-.17-.19.7.7 0 0 0-.237-.126 1 1 0 0 0-.299-.044q-.427 0-.665.302-.234.301-.234.85v.498q0 .351.097.615a.9.9 0 0 0 .304.413.87.87 0 0 0 .519.146 1 1 0 0 0 .457-.096.67.67 0 0 0 .272-.264q.09-.164.091-.363v-.255H8.82v-.59h1.576v.798q0 .29-.097.55a1.3 1.3 0 0 1-.293.458 1.4 1.4 0 0 1-.495.313q-.296.111-.697.111a2 2 0 0 1-.753-.132 1.45 1.45 0 0 1-.533-.377 1.6 1.6 0 0 1-.32-.58 2.5 2.5 0 0 1-.105-.745v-.506q0-.543.2-.95.201-.406.582-.633.384-.228.926-.228.357 0 .636.1.281.1.48.275.2.176.314.407Zm-8.64-.706H0v4h.791v-1.343h.803q.43 0 .732-.172.305-.177.463-.475a1.4 1.4 0 0 0 .161-.677q0-.374-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.381.57.57 0 0 1-.238.24.8.8 0 0 1-.375.082H.788v-1.406h.66q.327 0 .512.182.185.181.185.521m1.964 2.666V13.25h.032l1.761 2.675h.656v-3.999h-.75v2.66h-.032l-1.752-2.66h-.662v4z" />
                        </svg>
                        <span class="file-name-photo file-name"><?php echo get_phrase('picture'); ?>...</span>
                        <span class="required"> * </span>  </label>
                    <input id="student_image_upload" type="file" class="inputfile" name="student_image_upload"
                        accept=".jpg, .jpeg, .png" required>
                </div>
            </div>
            <!-- Second Register Form Section End-->

            <!-- Third Register Form Section -->
            <div class="tab">
                <div class="register-form-section pb-4">
                    <div class="mb-4 pt-3 login-input">
                        <label for="registerPassword"
                            class="login-input-label text-uppercase"><?php echo get_phrase("password") ?><span class="required"> * </span></label>
                        <input oninput="checkPassword()" type="password" class="form-control shadow-none password"
                            id="registerPassword" name="register_password"
                            data-msg="<?php echo get_phrase("required") ?>" required>
                    </div>
                    <div class=" mb-3 pt-2  login-input">
                        <label for="registerRepeatPassword"
                            class="login-input-label text-uppercase"><?php echo get_phrase("repeat password") ?><span class="required"> * </span></label>
                        <input oninput="checkPassword()" type="password" class="form-control shadow-none password"
                            id="registerRepeatPassword" name="register_repeat_password"
                            data-msg="<?php echo get_phrase("required") ?>" required>
                    </div>
                </div>
                <button type="submit" id="registerSubmit" class="register-button text-uppercase">
                    <?php echo get_phrase("register") ?>
                </button>
            </div>
            <!-- Third Register Form Section End-->
    </div>
        </form>

<!-- forget Section -->
        <div class="forget-dropdown hidden-section display-none">
            <svg class="login-exit-svg" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                <path fill-rule="evenodd"
                    d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
            </svg>
            <a class="text-uppercase">
                <span class="loginforge-link">
                    <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-arrow-bar-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5M10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5" />
                    </svg>
                    <?php echo get_phrase("login") ?>
                </span>
            </a>


        
            <form class="forget-form mt-10" id="forget-form" method="post" enctype="multipart/form-data"
                action="<?php echo site_url('login/send_reset_link'); ?>">
                <!-- Champ caché pour le jeton CSRF -->
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />


                <!-- First forget Form Section -->
                <!-- <div class="tab"> -->
                    <div class="mb-4 login-input">
                        <label for="forgetEmail"
                            class="login-input-label text-uppercase"><?php echo get_phrase("Email") ?><span class="required"> * </span></label>
                        <input type="text" class="form-control shadow-none information" id="forgotEmail"
                            name="email" required data-msg="<?php echo get_phrase("required") ?>">
                    </div>
                    <button type="submit" id="registerSubmit" class="register-button text-uppercase">
                    <?php echo get_phrase("sent_password_reset_link") ?>
                    </button>
                <!-- </div> -->
                <!-- First forget Form Section End-->
            </form>
        </div>
       
</div>
</div>
</div>

<script type="text/javascript">

    var inputs = document.querySelectorAll('.information');
    var passwordInputs = document.querySelectorAll('.password');
    var selects = document.getElementsByTagName('select');

    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener('input', function () {

            if (this.value != "")
                this.classList.remove("invalid");

        });

    }

    for (var i = 0; i < passwordInputs.length; i++) {
        passwordInputs[i].addEventListener('input', function () {
            if (this.value != "" && checkPassword()) {
                this.classList.remove("invalid");
            }

        });
    }

    for (var i = 0; i < selects.length; i++) {
        selects[i].addEventListener('change', function () {
            if (this.value != "") {
                this.classList.remove("invalid");
            } else {
                this.classList.add("invalid");
            }
        });
    }


    var currentTab = 0;
    showTab(currentTab);

    document.addEventListener("DOMContentLoaded", function () {
        showTab(currentTab);
    });

    function check_email(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function validateEmail(email) {
        return new Promise((resolve, reject) => {
            var emailInput = document.getElementById("registerEmail");
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            var regex = emailRegex.test(email);

            if (!regex) {
                error_notify('<?php echo get_phrase("please_enter_a_valid_email_address"); ?>');
                resolve(false);
            } else {
                fetch('<?php echo base_url("register/validate_email"); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email: email }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status == false) {
                            emailInput.classList.add("invalid");
                            error_notify('<?php echo get_phrase("email_already_in_use") ?>');
                            resolve(false);
                        } else {
                            resolve(true);
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        resolve(false);
                    });
            }
        });
    }

    function showTab(n) {
        var x = document.getElementsByClassName("tab");
        for (var i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        x[n].style.display = "block";

        if (n == 0) {
            document.getElementById("prevBtn").style.display = "none";
        } else {
            document.getElementById("prevBtn").style.display = "inline";
        }

        if (n == (x.length - 1)) {
            document.getElementById("nextBtn").innerHTML = "Submit";
        } else {
            document.getElementById("nextBtn").innerHTML = "Next";
        }
        fixStepIndicator(n);
    }

    async function nextPrev(n) {
        var x = document.getElementsByClassName("tab");
        if (n == 1 && !(await validateForm())) {
            return false;
        }
        x[currentTab].style.display = "none";
        currentTab = currentTab + n;
        showTab(currentTab);
    }

    async function validateForm() {
        var x, y, z, i, valid = true;
        x = document.getElementsByClassName("tab");
        y = x[currentTab].getElementsByTagName("input");
        z = x[currentTab].getElementsByTagName("select");

        for (i = 0; i < z.length; i++) {
            if (z[i].value == "") {
                z[i].className += " invalid";
                error_notify('<?php echo get_phrase('please_fill_in_the_required_fields'); ?>');
                valid = false;
            }
        }

        for (i = 0; i < y.length; i++) {
            if (y[i].value == "") {
                y[i].className += " invalid";
                if (y[i].type == "file") {
                    error_notify('<?php echo get_phrase('please_choose_an_image'); ?>');

                    y[i].className += " invalid";
                    valid = false;

                } else
                    error_notify('<?php echo get_phrase('please_fill_in_the_required_fields'); ?>');
                valid = false;
            }

            if (y[i].type == "email") {
                const emailValid = await validateEmail(y[i].value);
                if (!emailValid) {
                    y[i].className += " invalid";
                    valid = false;
                }
            }
        }

        return valid;
    }

    function fixStepIndicator(n) {
        var i, x = document.getElementsByClassName("step");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        x[n].className += " active";
    }

    function checkPassword() {
        var password = document.getElementById("registerPassword").value;
        var repeatPassword = document.getElementById("registerRepeatPassword").value;
        if (password != repeatPassword && repeatPassword != "" && !document.getElementById("registerRepeatPassword").classList.contains("invalid")) {
            document.getElementById("registerRepeatPassword").classList.add("invalid");
            return false;
        }
        if (password == repeatPassword) {
            document.getElementById("registerRepeatPassword").classList.remove("invalid");
            return true;
        }
    }

    document.getElementById("register_date_of_birth").addEventListener("click", () => {
        try {
            document.getElementById("register_date_of_birth");
        } catch (error) {
            console.error(error);
        }
    });

    document.getElementById("registerSubmit").addEventListener("click", function (event) {
        let registerDropdown = document.querySelector(".register-dropdown");
        let inputfields = registerDropdown.getElementsByTagName("input");
        let valid = true;

        for (let i = 0; i < inputfields.length; i++) {
            if (inputfields[i].classList.contains("invalid") && inputfields[i].value == "" && inputfields[i].type != "password") {
                error_notify('<?php echo get_phrase('please_fill_in_the_required_fields'); ?>');
                valid = false;
            }
            if (inputfields[i].classList.contains("invalid") && inputfields[i].type == "password" && !checkPassword() && inputfields[i].value != "") {
                error_notify('<?php echo get_phrase('passwords_need_to_match'); ?>');
                valid = false;
            }
            if (inputfields[i].type == "password" && inputfields[i].value == "") {
                error_notify('<?php echo get_phrase('please_fill_in_your_password'); ?>');
                valid = false;
            }
            if (inputfields[i].type == "file" && inputfields[i].value == "") {
                console.log(inputfields[i].value);
                valid = false;
            }
        }

        if (!valid) {
            event.preventDefault();
        } else {
            document.getElementById("register-form").submit();
        }
    });


    function getFileExtension(fileName) {
        // Split the fileName by period
        var parts = fileName.split(".");
        // Get the last part of the array which should be the extension
        var extension = parts[parts.length - 1];
        return extension.toLowerCase();
    }

    // Listen for file input change for student image

    document
        .getElementById("student_image_upload")
        .addEventListener("change", function () {
            var fileName = this.value.split("\\").pop(); // Gets the file name
            if (fileName.length > 10) {
                fileName =
                    fileName.substring(0, 10) + "... ." + getFileExtension(fileName); // Truncate the file name if it's too long
            }

            document.querySelector(".file-name-photo").textContent = fileName;
        });

</script>