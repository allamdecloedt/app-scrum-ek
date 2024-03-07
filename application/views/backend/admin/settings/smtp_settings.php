<div class="row justify-content-md-center">
    <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" class="col-12 smtpForm" action="<?php echo route('smtp_settings/update') ; ?>" id = "smtpsettings">
                    <div class="col-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="mail_sender"><?php echo get_phrase('mail_sender') ; ?></label>
                            <div class="col-md-9">
                                <select class="form-control select2" data-toggle = "select2" name="mail_sender" id="mail_sender" onchange = "showHideSMTPCredentials(this.value)" required>
                                    <option value="php_mailer" <?php if (get_smtp('mail_sender') == 'php_mailer'): ?> selected <?php endif; ?>><?php echo get_phrase('php_mailer') ;?></option>
                                    <option value="generic_smtp" <?php if (get_smtp('mail_sender') == 'generic_smtp'): ?> selected <?php endif; ?>><?php echo get_phrase('generic_smtp') ;?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="smtp_protocol">SMTP <?php echo get_phrase('protocol') ; ?></label>
                            <div class="col-md-9">
                                <input type="text" id="smtp_protocol" name="smtp_protocol" class="form-control"  value="<?php echo get_smtp('smtp_protocol') ; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="smtp_host">SMTP <?php echo get_phrase('host') ; ?></label>
                            <div class="col-md-9">
                                <input type="text" id="smtp_host" name="smtp_host" class="form-control"  value="<?php echo get_smtp('smtp_host') ; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="smtp_port">SMTP <?php echo get_phrase('port') ; ?></label>
                            <div class="col-md-9">
                                <input type="text" id="smtp_port" name="smtp_port" class="form-control"  value="<?php echo get_smtp('smtp_port') ; ?>" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="smtp_username">SMTP <?php echo get_phrase('username') ; ?></label>
                            <div class="col-md-9">
                                <input type="text" id="smtp_username" name="smtp_username" class="form-control"  value="<?php echo get_smtp('smtp_username') ; ?>" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="smtp_password">SMTP <?php echo get_phrase('password') ; ?></label>
                            <div class="col-md-9">
                                <input type="text" id="smtp_password" name="smtp_password" class="form-control"  value="<?php echo get_smtp('smtp_password') ; ?>" required>
                            </div>
                        </div>
                        <div id="php-mailer-visibility-div">
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="smtp_secure">SMTP <?php echo get_phrase('secure') ; ?></label>
                                <div class="col-md-9">
                                    <input type="text" id="smtp_secure" name="smtp_secure" class="form-control"  value="<?php echo get_smtp('smtp_secure') ; ?>">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="smtp_set_from">SMTP <?php echo get_phrase('set_from') ; ?></label>
                                <div class="col-md-9">
                                    <input type="text" id="smtp_set_from" name="smtp_set_from" class="form-control"  value="<?php echo get_smtp('smtp_set_from') ; ?>">
                                </div>
                            </div>
                            <div class="form-group row mb-3 hidden">
                                <label class="col-md-3 col-form-label" for="smtp_show_error">SMTP <?php echo get_phrase('show_error') ; ?></label>
                                <div class="col-md-9">
                                    <select class="form-control select2" data-toggle = "select2" name="smtp_show_error" id="smtp_show_error">
                                        <option value="yes" <?php if (get_smtp('smtp_show_error') == 'yes'): ?> selected <?php endif; ?>><?php echo get_phrase('show') ;?></option>
                                        <option value="no" <?php if (get_smtp('smtp_show_error') == 'no'): ?> selected <?php endif; ?>><?php echo get_phrase('do_not_show') ;?></option>
                                    </select>
                                    <small class = "text-muted"><?php echo get_phrase("error_will_be_shown_if_sending_mail_fails"); ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-secondary col-xl-4 col-lg-6 col-md-12 col-sm-12" onclick="updateSmtpInfo()"><?php echo get_phrase('update_SMTP_settings') ; ?></button>
                        </div>
                    </div>
                </form>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        var mail_sender = $('#mail_sender').val();
        showHideSMTPCredentials(mail_sender);
    });
    function showHideSMTPCredentials(mail_sender) {
        if (mail_sender === "php_mailer") {
            $("#php-mailer-visibility-div").slideDown();
        }else{
            $("#php-mailer-visibility-div").slideUp();
        }
    }
</script>
