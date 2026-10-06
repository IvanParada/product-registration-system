<?php

function renderSelect(string $id, string $name, string $label): void
{
?>
    <div class="form-group">
        <label for="<?= $id ?>">
            <?= $label ?>
        </label>

        <select
            id="<?= $id ?>"
            name="<?= $name ?>">
            <option value=""></option>
        </select>
        <span
            class="field-error"
            id="<?= $id ?>Error"></span>
    </div>
<?php
}
