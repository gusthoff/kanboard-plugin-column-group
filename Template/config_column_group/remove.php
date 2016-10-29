<div class="page-header">
    <h2><?= t('Remove a column group') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-info">
        <?= t('Do you really want to remove this column group: "%s"?', $column_code) ?>
    </p>

    <div class="form-actions">
        <?= $this->url->link(t('Yes'), 'ConfigColumnGroupController', 'remove', array('plugin' => 'ColumnGroup', 'column_code' => $column_code), true, 'btn btn-red') ?>
        <?= t('or') ?> <?= $this->url->link(t('cancel'), 'ConfigColumnGroupController', 'index', array('plugin' => 'ColumnGroup'), false, 'close-popover') ?>
    </div>
</div>
