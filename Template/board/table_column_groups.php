<!-- column group titles -->

<tr>
    <?php $prev_colspan = 1 ?>
    <?php foreach ($swimlane['columns'] as $key=>$value): ?>
        <?php if($prev_colspan > 1): ?>
            <?php $prev_colspan-- ?>
            <?php continue ?>
        <?php endif ?>
        <?php if ($swimlane['columns'][$key]['column_group_code'] != Null): ?>
            <?php $colspan = 1 ?>
            <?php while (($key + $colspan) < count($swimlane['columns'])): ?>
                <?php $next_column_group_code = $swimlane['columns'][$key+$colspan]['column_group_code'] ?>
                <?php if ($next_column_group_code === $swimlane['columns'][$key]['column_group_code']): ?>
                    <?php $colspan++ ?>
                <?php else: ?>
                    <?php break ?>
                <?php endif ?>
            <?php endwhile ?>
            <?php $prev_colspan = $colspan ?>
            <td align="center" colspan=<?= $colspan ?>>
                <b><?= $this->text->e($swimlane['columns'][$key]['column_group_code']) ?></b>
            </td>
            <?php else: ?>
                <td></td>
        <?php endif ?>
    <?php endforeach ?>
</tr>
