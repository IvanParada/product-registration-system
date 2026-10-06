<?php

function renderInput(string $id, string $name, string $label, string $type = 'text'): void
{
?>
    <div class="form-group">
        <label for="<?= htmlspecialchars($id) ?>">
            <?= htmlspecialchars($label) ?>
        </label>

        <input
            type="<?= htmlspecialchars($type) ?>"
            id="<?= htmlspecialchars($id) ?>"
            name="<?= htmlspecialchars($name) ?>">
        <span
            class="field-error"
            id="<?= $id ?>Error"></span>
    </div>
<?php
}
