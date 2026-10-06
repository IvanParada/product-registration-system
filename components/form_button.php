<?php

function renderSubmitButton(string $text, string $id = 'submitButton'): void
{
?>
    <button
        type="submit"
        id="<?= $id ?>">
        <?= $text ?>
    </button>
<?php
}
