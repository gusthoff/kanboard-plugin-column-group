<div class="page-header">
    <h2><?= t('Remove column from group') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-info">
        <?= t('Do you really want to remove the association to this column group: "%s"?', $column_group_code) ?>
    </p>

    <div class="form-actions">
        <?= $this->url->link(t('Yes'), 'ProjectColumnGroupController', 'remove', array('plugin' => 'ColumnGroup', 'project_id' => $project['id'], 'column_id' => $column_id), true, 'btn btn-red') ?>
        <?= t('or') ?> <?= $this->url->link(t('cancel'), 'ProjectColumnGroupController', 'index', array('plugin' => 'ColumnGroup', 'project_id' => $project['id']), false, 'close-popover') ?>
    </div>
</div>
