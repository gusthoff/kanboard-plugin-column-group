<div class="page-header">
    <h2><?= t('Edit column "%s"', $column['title']) ?></h2>
</div>

<form class="popover-form" method="post" action="<?= $this->url->href('ProjectColumnGroupController', 'update', array('plugin' => 'ColumnGroup', 'project_id' => $project['id'], 'column_id' => $column['id'])) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>

    <?= $this->form->hidden('id', $values) ?>
    <?= $this->form->hidden('project_id', $values) ?>

    <?= $this->form->label(t('Column title'), 'title') ?>
    <?= $this->form->text('title', $values, $errors, array('autofocus', 'readonly', 'maxlength="50"')) ?>

    <?= $this->form->label(t('Column group code'), 'column_group_code') ?>
    <?= $this->form->select('column_group_code', $column_group_codes, $values, $errors, array('autofocus',  'maxlength="30"')) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-blue"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'ProjectColumnGroupController', 'index', array('plugin' => 'ColumnGroup', 'project_id' => $project['id']), false, 'close-popover') ?>
    </div>
</form>
