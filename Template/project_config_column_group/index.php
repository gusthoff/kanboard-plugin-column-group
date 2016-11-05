<div class="page-header">
    <h2><?= t('Edit local column groups') ?></h2>
    <ul>
        <li>
            <i class="fa fa-plus" aria-hidden="true"></i>
            <?= $this->url->link(t('Add new local column group for this project'), 'ProjectConfigColumnGroupController', 'create', array('plugin' => 'ColumnGroup', 'project_id' => $project['id']), false, 'popover') ?>
        </li>
    </ul>
</div>

<?php if (empty($columns)): ?>
    <p class="alert alert-error"><?= t('Your board doesn\'t have any local column groups!') ?></p>
<?php else: ?>
    <table
        class="columns-table table-striped"
        data-save-position-url="<?= $this->url->href('ProjectConfigColumnGroupController', 'move', array('plugin' => 'ColumnGroup', 'project_id' => $project['id'])) ?>">
        <thead>
        <tr>
            <th class="column-70"><?= t('Column group title') ?></th>
            <th class="column-25"><?= t('Column group code') ?></th>
            <th class="column-5"><?= t('Actions') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($columns as $column): ?>
        <tr data-column-id="<?= $column['code'] ?>">
            <td>
                <?= $this->text->e($column['title']) ?>
                <?php if (! empty($column['description'])): ?>
                    <span class="tooltip" title="<?= $this->text->markdownAttribute($column['description']) ?>">
                        <i class="fa fa-info-circle"></i>
                    </span>
                <?php endif ?>
            </td>
            <td>
                <?= $this->text->e($column['code']) ?>
            </td>
            <td>
                <div class="dropdown">
                <a href="#" class="dropdown-menu dropdown-menu-link-icon"><i class="fa fa-cog fa-fw"></i><i class="fa fa-caret-down"></i></a>
                <ul>
                    <li>
                        <i class="fa fa-pencil-square-o fa-fw" aria-hidden="true"></i>
                        <?= $this->url->link(t('Edit'), 'ProjectConfigColumnGroupController', 'edit', array('plugin' => 'ColumnGroup', 'column_code' => $column['code'], 'project_id' => $project['id']), false, 'popover') ?>
                    </li>
                    <li>
                        <i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                        <?= $this->url->link(t('Remove'), 'ProjectConfigColumnGroupController', 'confirm', array('plugin' => 'ColumnGroup', 'column_code' => $column['code'], 'project_id' => $project['id']), false, 'popover') ?>
                    </li>
                </ul>
                </div>
            </td>
        </tr>
        <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>

<div class="page-header">
    <h2><?= t('Edit global column groups') ?></h2>
    <ul>
        <li>
            <i class="fa fa-plus" aria-hidden="true"></i>
            <?= $this->url->link(t('Add new global column group'), 'ConfigColumnGroupController', 'createSimple', array('plugin' => 'ColumnGroup'), false, 'popover') ?>
        </li>
    </ul>
</div>

<?php if (empty($global_columns)): ?>
    <p class="alert alert-error"><?= t('Your board doesn\'t have any column groups!') ?></p>
<?php else: ?>
    <table
        class="columns-table table-striped"
        <thead>
        <tr>
            <th class="column-70"><?= t('Column group title') ?></th>
            <th class="column-25"><?= t('Column group code') ?></th>
            <th class="column-5"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($global_columns as $column): ?>
        <tr data-column-id="<?= $column['code'] ?>">
            <td>
                <?= $this->text->e($column['title']) ?>
                <?php if (! empty($column['description'])): ?>
                    <span class="tooltip" title="<?= $this->text->markdownAttribute($column['description']) ?>">
                        <i class="fa fa-info-circle"></i>
                    </span>
                <?php endif ?>
            </td>
            <td>
                <?= $this->text->e($column['code']) ?>
            </td>
            <td>
            </td>
        </tr>
        <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>

<div class="page-header">
    <h2><?= t('External column groups') ?></h2>
</div>

<?php if (empty($ext_columns)): ?>
    <p class="alert alert-error"><?= t('Your board doesn\'t have any external column groups!') ?></p>
<?php else: ?>
    <table
        class="columns-table table-striped"
        <thead>
        <tr>
            <th class="column-70"><?= t('Column group title') ?></th>
            <th class="column-25"><?= t('Column group code') ?></th>
            <th class="column-5"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($ext_columns as $column): ?>
        <tr data-column-id="<?= $column['code'] ?>">
            <td>
                <?= $this->text->e($column['title']) ?>
                <?php if (! empty($column['description'])): ?>
                    <span class="tooltip" title="<?= $this->text->markdownAttribute($column['description']) ?>">
                        <i class="fa fa-info-circle"></i>
                    </span>
                <?php endif ?>
            </td>
            <td>
                <?= $this->text->e($column['code']) ?>
            </td>
            <td>
            </td>
        </tr>
        <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
