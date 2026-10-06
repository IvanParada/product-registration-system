<?php

function renderTextarea(string $id, string $name, string $label): void
{
?>
    <div class="form-group">
        <label for="<?= $id ?>">
            <?= $label ?>
        </label>

        <textarea
            id="<?= $id ?>"
            name="<?= $name ?>"></textarea>
    </div>
    <span
        class="field-error"
        id="<?= $id ?>Error"></span>
<?php
}
