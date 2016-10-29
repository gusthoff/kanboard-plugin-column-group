<div class="page-header">
    <h2><?= t('Edit the board for "%s"', $project['name']) ?></h2>
    <ul>
    </ul>
</div>

<?php if (empty($columns)): ?>
    <p class="alert alert-error"><?= t('Your board doesn\'t have any columns!') ?></p>
<?php else: ?>
    <table
        class="columns-table table-striped"
        data-save-position-url="<?= $this->url->href('ProjectColumnGroupController', 'move', array('plugin' => 'ColumnGroup', 'project_id' => $project['id'])) ?>">
        <thead>
        <tr>
            <th class="column-70"><?= t('Column title') ?></th>
            <th class="column-25"><?= t('Column group code') ?></th>
            <th class="column-5"><?= t('Actions') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($columns as $column): ?>
        <tr data-column-id="<?= $column['id'] ?>">
            <td>
                <?= $this->text->e($column['title']) ?>
                <?php if (! empty($column['description'])): ?>
                    <span class="tooltip" title="<?= $this->text->markdownAttribute($column['description']) ?>">
                        <i class="fa fa-info-circle"></i>
                    </span>
                <?php endif ?>
            </td>
            <td>
                <?= $this->text->e($column['column_group_code']) ?>
            </td>
            <td>
                <div class="dropdown">
                <a href="#" class="dropdown-menu dropdown-menu-link-icon"><i class="fa fa-cog fa-fw"></i><i class="fa fa-caret-down"></i></a>
                <ul>
                    <li>
                        <i class="fa fa-pencil-square-o fa-fw" aria-hidden="true"></i>
                        <?= $this->url->link(t('Edit'), 'ProjectColumnGroupController', 'edit', array('plugin' => 'ColumnGroup', 'project_id' => $project['id'], 'column_id' => $column['id']), false, 'popover') ?>
                    </li>
                    <?php if (! empty($column['column_group_code'])): ?>
                        <li>
                            <i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                            <?= $this->url->link(t('Remove'), 'ProjectColumnGroupController', 'confirm', array('plugin' => 'ColumnGroup', 'project_id' => $project['id'], 'column_id' => $column['id']), false, 'popover') ?>
                        </li>
                    <?php endif ?>
                </ul>
                </div>
            </td>
        </tr>
        <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
