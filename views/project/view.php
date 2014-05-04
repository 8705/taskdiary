<?php $this->setLayoutVar('title', 'アカウント') ?>

<div class="container">
    <div class="row">
    <div class="col-md-3 projects">
        <h2>プロジェクトリスト</h2>
        <ul class="list-group">
            <?php foreach ($projects as $project): ?>
            <li class="project list-group-item">
                <p><a href="/project/view/<?php echo $this->escape($project['project_id']); ?>"><?php echo $this->escape($project['project_name']); ?></a><span><a href="/project/delete/<?php echo $this->escape($project['project_id']); ?>">x</a></span></p>
                <p class="content"><?php echo $this->escape($project['project_text']); ?></p>
            </li>
            <?php endforeach; ?>
        </ul>
        <h2>プロジェクト追加</h2>
        <form action="/project/add" method="POST">
            <p>
                <input type="text" name="project_name">
            </p>
            <textarea name="project_text" id="" cols="30" rows="3"></textarea>
            <input type="submit" value="submit">
        </form>
        <h2>タスク追加</h2>
        <form action="/task/add" method="POST">
            <p>
                <select name="project_id" id="">
                    <option value="">プロジェクトを選択</option>
                    <?php foreach($projects as $project): ?>
                    <option value="<?php echo $this->escape($project['project_id']) ?>"><?php echo $this->escape($project['project_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p><input type="text" name="task_name"></p>
            <textarea name="task_text" id="" cols="30" rows="3"></textarea>
            <p>
                <select name="task_size" id="">
                    <option value="short">short</option>
                    <option value="middle">middle</option>
                </select>
            </p>
            <input type="submit" value="タスクGO!">
        </form>
    </div>
    <div class="col-md-9 tasks">
        <h2>タスクリスト（<?php echo $this->escape($project_name) ?>）</h2>
        <form action="/project/view/<?php echo $this->escape($project_id) ?>" method="POST">
            <ul class="list-group">
                <?php foreach ($tasks as $task): ?>
                <li class="task list-group-item <?php if ($task['task_is_done'] == 1) echo 'done'; ?>">
                    <p>
                        <input type="hidden" name="<?php echo $task['task_id']; ?>" value="0">
                        <input type="checkbox" name="<?php echo $task['task_id']; ?>" value="1" <?php if ($task['task_is_done'] == 1) echo "checked='checked'"; ?> >
                        <span class="label label-<?php echo $this->escape($task['task_size']); ?>"><?php echo $this->escape($task['task_size']); ?></span>
                        <?php echo $this->escape($task['task_name']); ?>
                    </p>
                    <p class="content"><?php echo $this->escape($task['task_text']); ?><span>[ <?php echo $this->escape($task['task_created']); ?>に作成 ]</span><span><a href="/task/delete/<?php echo $this->escape($task['task_id']); ?>">x</a></span></p>
                </li>
                <?php endforeach; ?>
            </ul>
            <input type='submit' value='状態を更新'>
        </form>
    </div>
    </div>
</div>