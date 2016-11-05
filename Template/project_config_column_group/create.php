<div class="page-header">
    <h2><?= t('Add a new column group') ?></h2>
</div>
<form class="popover-form" method="post" action="<?= $this->url->href('ProjectConfigColumnGroupController', 'save', array('plugin' => 'ColumnGroup', 'project_id' => $project['id'])) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>

    <?= $this->form->label(t('Column group code'), 'code') ?>
    <?= $this->form->text('code', $values, $errors, array('autofocus',  'maxlength="30"')) ?>

    <?= $this->form->label(t('Title'), 'title') ?>
    <?= $this->form->text('title', $values, $errors, array('autofocus', 'maxlength="50"')) ?>

    <?= $this->form->label(t('Description'), 'description') ?>
    <?= $this->form->text('description', $values, $errors, array('autofocus',  'maxlength="30"')) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-blue"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'ProjectConfigColumnGroupController', 'index', array('plugin' => 'ColumnGroup', 'project_id' => $project['id']), false, 'close-popover') ?>
    </div>
</form>
