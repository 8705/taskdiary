<?php $this->setLayoutVar('title', 'アカウント') ?>

<div class="container">
    <div class="row">
        <div class="col-md-3 projects">
            <h2>タスク一覧</h2>
            <ul class="list-group">
                <li class="list-group-item"><a href="/top/index">今日のタスク</a></li>
                <li class="list-group-item"><a href="/top/list?nav=0">過去のタスク</a></li>
            </ul>
            <h2>カテゴリー</h2>
            <ul class="list-group">
                <?php foreach ($categories as $v): ?>
                    <li class="project list-group-item">
                        <p>
                            <a href="/top/view/<?php echo $this->escape($v['category_id']); ?>">
                                <?php echo $this->escape($v['category_name']); ?>
                            </a>
                            <a href="/category/delete/<?php echo $this->escape($v['category_id']); ?>">x</a>
                        </p>
                    </li>
                <?php endforeach; ?>
                <form action="/category/add" method="POST">
                    <input type="text" class="form-control" placeholder="カテゴリー" name="category_name">
                    <input type="submit" class="btn btn-info" value="追加">
                </form>
            </ul>
        </div>
        <div class="col-md-9 tasks">
            <h2><?php echo $this->escape($year)."年".$this->escape($month)."月のタスク"; ?></h2>
            <a href="/top/list?nav=<?php echo $this->escape($prev) ?>">←前月</a>
            <a href="/top/list?nav=<?php echo $this->escape($next) ?>">次月→</a>
            <?php if (!$tasks): ?>
                <p>今月は何もしてません</p>
            <?php endif ?>
            <table class="table">
                <tbody>
                    <?php $date = 0; ?>
                    <?php foreach ($tasks as $v): ?>
                        <tr>
                            <td>
                                <?php if (date('j', strtotime($v['task_finish'])) != $date): ?>
                                    <?php echo date('j', strtotime($v['task_finish']))."日";
                                          $date = date('j', strtotime($v['task_finish']));
                                    ?>

                                <?php endif; ?>
                            </td>
                            <td><?php echo date('G:i', strtotime($v['task_finish'])); ?></td>
                            <td><?php echo $v['task_name']; ?></td>
                            <td><?php echo $v['category_name']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>