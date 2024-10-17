<?php foreach (openJSONFile($param1) as $key => $value): ?>
    <div class="form-row row align-items-end mb-2">
        <div class="form-group col-10">
            <label for="name"><?php echo ucfirst(str_replace('_', ' ', $key)); ?></label>
            <input type="text" class="form-control" name="updated_phrase" value="<?php echo $value; ?>" id = "phrase-<?php echo $key; ?>">
            <input type="hidden" name="id" id = "language_id_{{ $key }}" value="{{ $language->id }}">
        </div>
        <div class="form-group col-2">
            <button class="btn btn-icon btn-success" type="button" onclick="updatePhrase('<?php echo $key; ?>')"><i class="mdi mdi-check-circle-outline"></i></button>
        </div>
    </div>
<?php endforeach; ?>


<script type="text/javascript">
function updatePhrase(key) {
    
    $('#btn-'+key).text('...');
    var updatedValue = $('#phrase-'+key).val();
    var currentEditingLanguage = '<?php echo $param1; ?>';
    // Récupérer le nom et la valeur du jeton CSRF depuis l'input caché
    var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
    var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();

    $.ajax({
        type : "POST",
        url  : "<?php echo route('language/update_phrase'); ?>",
        data : {updatedValue : updatedValue, currentEditingLanguage : currentEditingLanguage, key : key, [csrfName]: csrfHash},
        dataType: 'json',
        success : function(response) {
            // Mettre à jour le jeton CSRF avec le nouveau jeton renvoyé dans la réponse
            var newCsrfName = response.csrf.csrfName;
            var newCsrfHash = response.csrf.csrfHash;
            $('input[name="' + newCsrfName + '"]').val(newCsrfHash); // Mise à jour du token CSRF
            $('#btn-'+key).html('<i class = "mdi mdi-check-circle"></i>');
            success_notify('<?php echo get_phrase('phrase_updated').". ".get_phrase('please_make_sure_to_reload_the_browser');?>');
        }
    });
}
</script>
