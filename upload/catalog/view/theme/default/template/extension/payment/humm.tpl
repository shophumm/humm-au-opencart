<?php if (isset($error)) : ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php else : ?>
<form action="<?php echo $action; ?>" method="post">
    <?php foreach ($params as $key => $value) { ?>
    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
    <?php } ?>

    <div class="buttons">
        <div class="pull-right">
            <input type="submit" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary"
                   data-loading-text="<?php echo $text_loading; ?>"/>
        </div>
    </div>
</form>
<script>
    $('#button-confirm').button('reset');
</script>
<?php endif; ?>
