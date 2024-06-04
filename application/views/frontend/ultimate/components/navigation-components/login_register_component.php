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
            <div class="mb-4 mt-4 login-input">
                <label for="loginEmail"
                    class="login-input-label text-uppercase"><?php echo get_phrase("e-mail") ?></label>
                <input type="email" class="form-control shadow-none" id="loginEmail" aria-describedby="emailHelp"
                    name="login_email">

            </div>
            <div class="mb-3 login-input">
                <label for="loginPassword"
                    class=" login-input-label text-uppercase"><?php echo get_phrase("password") ?></label>
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

        <form class="register-form mt-10" id="register-form" method="post"
            action="<?php echo site_url('register/register_user'); ?>">

            <!-- First Register Form Section -->
            <div class="tab">
                <div class="mb-4 login-input">
                    <label for="registerFirstName"
                        class="login-input-label text-uppercase"><?php echo get_phrase("first name") ?></label>
                    <input type="text" class="form-control shadow-none information" id="registerFirstName"
                        name="register_first_name" required data-msg="<?php echo get_phrase("required") ?>">
                </div>
                <div class="mb-4 login-input">
                    <label for="registerLastName"
                        class=" login-input-label text-uppercase"><?php echo get_phrase("last name") ?></label>
                    <input id="registerLastName" type="text" class="form-control shadow-none information" id=""
                        name="register_last_name" required data-msg="<?php echo get_phrase("required") ?>">
                </div>
                <div class="mb-4 login-input">
                    <label for="registerEmail"
                        class=" login-input-label text-uppercase"><?php echo get_phrase("e-mail") ?></label>
                    <input id="registerEmail" type="email" class="form-control shadow-none information" id=""
                        name="register_email" required data-msg="<?php echo get_phrase("required") ?>">
                </div>
            </div>
            <!-- First Register Form Section End-->

            <!-- Second Register Form Section -->
            <div class="tab">

                <div class="mb-4 pt-3 login-input">
                    <label for="register_date_of_birth"
                        class=" login-input-label text-uppercase"><?php echo get_phrase("date of birth") ?></label>
                    <input onfocus="'showPicker' in this && this.showPicker()" type="date"
                        class="form-control  shadow-none information" id="register_date_of_birth"
                        name="register_date_of_birth" required data-msg="<?php echo get_phrase("required") ?>">
                </div>
                <div class="mb-4 pt-2 login-input">
                    <label for="register_gender"
                        class=" login-input-label text-uppercase"><?php echo get_phrase("gender") ?></label>
                    <select name="register_gender" id="register_gender"
                        class="form-control rounded-end shadow-none information" required
                        data-msg="<?php echo get_phrase("required") ?>">
                        <option value=""><?php echo get_phrase('select_your_gender'); ?></option>
                        <option value="Male"><?php echo get_phrase('male'); ?></option>
                        <option value="Female"><?php echo get_phrase('female'); ?></option>
                        <option value="Others"><?php echo get_phrase('others'); ?></option>
                    </select>
                </div>
            </div>
            <!-- Second Register Form Section End-->

            <!-- Third Register Form Section -->
            <div class="tab">
                <div class="register-form-section pb-4">
                    <div class="mb-4 pt-3 login-input">
                        <label for="registerPassword"
                            class="login-input-label text-uppercase"><?php echo get_phrase("password") ?></label>
                        <input oninput="checkPassword()" type="password" class="form-control shadow-none password"
                            id="registerPassword" name="register_password"
                            data-msg="<?php echo get_phrase("required") ?>" required>
                    </div>
                    <div class=" mb-3 pt-2  login-input">
                        <label for="registerRepeatPassword"
                            class="login-input-label text-uppercase"><?php echo get_phrase("repeat password") ?></label>
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
                valid = false;
            }
        }

        for (i = 0; i < y.length; i++) {
            if (y[i].value == "") {
                y[i].className += " invalid";
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
            document.getElementById("register_date_of_birth").hidePicker();
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
        }

        if (!valid) {
            event.preventDefault();
        } else {
            document.getElementById("register-form").submit();
        }
    });

</script>