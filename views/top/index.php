<?php $this->setLayoutVar('title', 'アカウント') ?>

<div class="container">
    <div class="row">
        <div class="col-md-3 projects">
            <h2>過去のタスク一覧</h2>
            <ul class="list-group">
                <li><a href="/top/donetask">過去のタスク</a></li>
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

            <h2>タスク追加</h2>
            <form action="/task/add" method="POST">
                <p>
                    <select name="category_id" id="" class="form-control">
                        <option value="">カテゴリーを選択</option>
                        <?php foreach($categories as $v): ?>
                            <option value="<?php echo $this->escape($v['category_id']) ?>">
                                <?php echo $this->escape($v['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p><input type="text" class="form-control" placeholder="タスク" name="task_name"></p>
                <input type="date" name="task_limit">
                <input type="submit" class="btn btn-info" value="タスク追加">
            </form>
        </div>
        <div class="col-md-9 tasks">
            <h2>今日のタスク</h2>
            <form action="/task/updateIsDone" method="POST">
                <ul class="list-group">
                    <?php foreach ($tasks as $v): ?>
                        <li class="task list-group-item <?php if ($v['task_is_done'] == 1) echo 'done'; ?>">
                            <p>
                                <input type="hidden" name="<?php echo $v['task_id']; ?>" value="0">
                                <input type="checkbox" name="<?php echo $v['task_id']; ?>" value="1" <?php if ($v['task_is_done'] == 1) echo "checked='checked'"; ?> >
                                <?php echo $this->escape($v['task_name']); ?>
                                <?php if (isset($v['category_name'])) echo '('.$this->escape($v['category_name']).')'; ?>
                                <span>
                                    <a href="/comment/add/<?php echo $this->escape($v['task_id']); ?>">コメント</a>
                                </span>
                                <span>
                                    <a href="/task/delete/<?php echo $this->escape($v['task_id']); ?>">削除</a>
                                </span>
                            </p>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <input type='submit' class="btn btn-info" value='状態を更新'>
            </form>
        </div>
    </div>
</div>