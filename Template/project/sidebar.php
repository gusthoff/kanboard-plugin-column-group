<li <?= $this->app->checkMenuSelection('ProjectColumnGroupController', 'index', 'ColumnGroup') ?>>
    <?= $this->url->link(t('Column groups'), 'ProjectColumnGroupController', 'index', array('plugin' => 'ColumnGroup', 'project_id' => $project['id'])) ?>
</li>
