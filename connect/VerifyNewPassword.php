<script>
    jQuery('.form-control').removeClass('is-invalid');
    jQuery('.form-control').addClass('is-valid');
    jQuery('#inscript').removeClass('is-invalid').addClass('is-valid');
</script>
<?php
if (!empty($ErrorAuth['errors'])) {
    foreach ($ErrorAuth['errors'] as $k => $error) {
        ?>
        <script>
        jQuery("#charging").hide();
        jQuery("#form").show();
        jQuery('#error<?= $k ?>').remove();
        jQuery('#input<?= $k ?>').removeClass('is-valid').addClass('is-invalid');
        jQuery('#error').append('<div class="alert alert-danger" id="error<?= $k ?>" role="alert"><i class="fas fa-times"></i> <?= $error ?> </div>');
        </script>
        <?php
    }
} else{
    ?>
    <script>
        $('#action').text('Vous allez être rediriger.');
        document.location.href="index.php";
    </script>
    <?php
}