<?php $this->setLayoutVar('title', 'アカウント') ?>

<div class="container">
    <div class="row">
        <div class="col-md-9 tasks col-md-offset-1">
            <h2>未来のタスク</h2>
            <?php if (!$tasks): ?>
                <p>あなたの未来には何もありません</p>
            <?php endif ?>
            <table class="table">
                <tbody>
                    <?php
                        $date = 0;
                        $week = array('日', '月', '火', '水', '木', '金', '土');
                    ?>
                    <?php foreach ($tasks as $v): ?>
                        <?php $limit = strtotime($v['task_limit']); ?>
                        <?php if ($limit != $date): ?>
                            <tr class="newday">
                                <td>
                                    <?php echo date('Y', $limit)."年"
                                              .date('m', $limit)."月"
                                              .date('j', $limit)."日（".$week[date('w', $limit)]."）";
                                          $date = $limit;
                                    ?>
                                </td>
                        <?php else: ?>
                            <tr><td></td>
                        <?php endif; ?>
                            <td><?php echo date('G:i', strtotime($v['task_limit'])); ?></td>
                            <td><?php echo $v['task_name']; ?></td>
                            <td><?php echo $v['category_name']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>