<?php $this->setLayoutVar('title', 'アカウント') ?>

<div class="container">
    <div class="row">
    <div class="col-md-3 projects">
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
        </ul>
        <h2>共有プロジェクト</h2>
        <ul class="list-group">
            <?php foreach ($projects as $v): ?>
            <li class="project list-group-item">
                <p>
                    <a href="/project/view/<?php echo $this->escape($v['project_id']); ?>">
                        <?php echo $this->escape($v['project_name']); ?>
                    </a>
                    <a href="/project/delete/<?php echo $this->escape($v['project_id']); ?>">x</a>
                </p>
                <p class="content"><?php echo $this->escape($v['project_text']); ?></p>
            </li>
            <?php endforeach; ?>
        </ul>
        <h2>カテゴリー追加</h2>
        <form action="/category/add" method="POST">
            <p>
                <input type="text" class="form-control" placeholder="目標" name="category_name">
            </p>
            <input type="submit" class="btn btn-info" value="目標追加">
        </form>
        <h2>プロジェクト追加</h2>
        <form action="/project/add" method="POST">
            <p>
                <input type="text" class="form-control" placeholder="目標" name="project_name">
            </p>
            <textarea name="project_text" class="form-control" placeholder="備考" id="" cols="30" rows="3"></textarea>
            <input type="submit" class="btn btn-info" value="目標追加">
        </form>
        <h2>タスク追加</h2>
        <form action="/task/add" method="POST">
            <p>
                <select name="category_id" id="" class="form-control">
                    <option value="">プロジェクトを選択</option>
                    <?php foreach($categories as $v): ?>
                        <option value="<?php echo $this->escape($v['category_id']) ?>">
                            <?php echo $this->escape($v['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p><input type="text" class="form-control" placeholder="タスク" name="task_name"></p>
            <input type="submit" class="btn btn-info" value="タスク追加">
        </form>
    </div>
    <div class="col-md-9 tasks">
        <h2>タスクリスト</h2>
        <form action="/project/index" method="POST">
            <ul class="list-group">
                <?php foreach ($tasks as $v): ?>
                    <li class="task list-group-item <?php if ($v['task_is_done'] == 1) echo 'done'; ?>">
                        <p>
                            <input type="hidden" name="<?php echo $v['task_id']; ?>" value="0">
                            <input type="checkbox" name="<?php echo $v['task_id']; ?>" value="1" <?php if ($v['task_is_done'] == 1) echo "checked='checked'"; ?> >
                            <span class="label label-<?php echo $this->escape($v['task_size']); ?>">
                                <?php echo $this->escape($v['task_size']); ?>
                            </span>
                            <?php echo $this->escape($v['task_name']); ?>(<?php echo $this->escape($v['project_name']); ?>)
                        </p>
                        <p class="content">
                            <?php echo $this->escape($v['task_text']); ?>
                            <span>
                                [ <?php echo $this->escape($v['task_created']); ?>に作成 ]
                            </span>
                            <span>
                                <a href="/task/delete/<?php echo $this->escape($v['task_id']); ?>">x</a>
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