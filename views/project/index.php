<?php $this->setLayoutVar('title', 'アカウント') ?>

<div class="container">
    <div class="row">
    <div class="col-md-3 projects">
        <h2>長期目標リスト</h2>
        <ul class="list-group">
            <?php foreach ($projects as $project): ?>
            <li class="project list-group-item">
                <p><a href="/project/view/<?php echo $this->escape($project['project_id']); ?>"><?php echo $this->escape($project['project_name']); ?></a><span><a href="/project/delete/<?php echo $this->escape($project['project_id']); ?>">x</a></span></p>
                <p class="content"><?php echo $this->escape($project['project_text']); ?></p>
            </li>
            <?php endforeach; ?>
        </ul>
        <h2>目標追加</h2>
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
                <select name="project_id" id="" class="form-control">
                    <option value="">プロジェクトを選択</option>
                    <?php foreach($projects as $project): ?>
                    <option value="<?php echo $this->escape($project['project_id']) ?>"><?php echo $this->escape($project['project_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p><input type="text" class="form-control" placeholder="タスク" name="task_name"></p>
            <textarea name="task_text" class="form-control" placeholder="備考" id="" cols="30" rows="3"></textarea>
            <p>
                <select name="task_size" id="" class="form-control">
                    <option value="1">★☆☆☆☆</option>
                    <option value="2">★★☆☆☆</option>
                    <option value="3">★★★☆☆</option>
                    <option value="4">★★★★☆</option>
                    <option value="5">★★★★★</option>
                </select>
            </p>
            <input type="submit" class="btn btn-info" value="タスク追加">
        </form>
    </div>
    <div class="col-md-9 tasks">
        <h2>タスクリスト</h2>
        <form action="/project/index" method="POST">
            <ul class="list-group">
                <?php foreach ($tasks as $task): ?>
                    <li class="task list-group-item <?php if ($task['task_is_done'] == 1) echo 'done'; ?>">
                        <p>
                            <input type="hidden" name="<?php echo $task['task_id']; ?>" value="0">
                            <input type="checkbox" name="<?php echo $task['task_id']; ?>" value="1" <?php if ($task['task_is_done'] == 1) echo "checked='checked'"; ?> >
                            <span class="label label-<?php echo $this->escape($task['task_size']); ?>"><?php echo $this->escape($task['task_size']); ?></span>
                            <?php echo $this->escape($task['task_name']); ?>(<?php echo $this->escape($task['project_name']); ?>)
                        </p>
                        <p class="content"><?php echo $this->escape($task['task_text']); ?><span>[ <?php echo $this->escape($task['task_created']); ?>に作成 ]</span><span><a href="/task/delete/<?php echo $this->escape($task['task_id']); ?>">x</a></span></p>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <input type='submit' class="btn btn-info" value='状態を更新'>
        </form>
    </div>
    </div>
</div>