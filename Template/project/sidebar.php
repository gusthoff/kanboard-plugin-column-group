<li <?= $this->app->checkMenuSelection('ProjectConfigColumnGroupController', 'index', 'ColumnGroup') ?>>
    <?= $this->url->link(t('Column groups'), 'ProjectConfigColumnGroupController', 'index', array('plugin' => 'ColumnGroup', 'project_id' => $project['id'])) ?>
</li>
<li <?= $this->app->checkMenuSelection('ProjectColumnGroupController', 'index', 'ColumnGroup') ?>>
    <?= $this->url->link(t('Columns & groups'), 'ProjectColumnGroupController', 'index', array('plugin' => 'ColumnGroup', 'project_id' => $project['id'])) ?>
</li>
