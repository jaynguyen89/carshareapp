<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<script>
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 30000);
</script>

<div class="alert alert-warning text-center" onclick="this.classList.add('hidden')"><?= $message ?></div>
