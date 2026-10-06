<?php

function renderCheckboxGroup(string $containerId, string $label): void
{
?>
    <div class="form-group checkbox-group">
        <span class="form-label">
            <?= $label ?>
        </span>

        <div
            id="<?= $containerId ?>"
            class="checkbox-options"></div>

        <span
            class="field-error"
            id="<?= $containerId ?>Error"></span>
    </div>
<?php
}
