<li <?= $this->app->checkMenuSelection('ConfigColumnGroupController', 'index', 'ColumnGroup') ?>>
    <?= $this->url->link(t('Column groups'), 'ConfigColumnGroupController', 'index', array('plugin' => 'ColumnGroup')) ?>
</li>
