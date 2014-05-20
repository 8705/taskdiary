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
                            <a href="/category/delete/<?php echo $this->escape($v['category_id']); ?>"><span class="glyphicon glyphicon-remove-circle"></span></a>
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
            <h2>今日のタスク</h2>
            <form action="/task/add_task" method="POST">
                <ul id="task_add">
                    <li class="clearfix">
                        <select name="category_id[]" class="input-category form-control">
                        <option value="">カテゴリーを選択</option>
                        <?php foreach($categories as $v): ?>
                            <option value="<?php echo $this->escape($v['category_id']) ?>"><?php echo $this->escape($v['category_name']) ?></option>
                        <?php endforeach; ?>
                        </select>
                        <input type="text" class="input-task form-control" data-input-num="1" name="task_name[]" placeholder="タスクを入力"/>
                        <input type="date" class="input-date" name="task_limit[]" value="<?php echo date('Y-m-d'); ?>">
                    </li>
                </ul>
                <p><input type="submit" value="送信" class="btn btn-primary"></p>
            </form>
            <form class="task-list" method="POST">
                <ul class="list-group sort-list ui-sortable">
                    <?php foreach ($tasks as $v): ?>
                        <li class="task list-group-item <?php if ($v['task_is_done'] == 1) echo 'done'; ?>" id="task_<?php echo $v['task_id']; ?>">
                            <p>
                                <input type="hidden" name="<?php echo $v['task_id']; ?>" value="0">
                                <input type="checkbox" class="check-task" name="<?php echo $v['task_id']; ?>" value="1" <?php if ($v['task_is_done'] == 1) echo "checked='checked'"; ?> >
                                <?php echo $this->escape($v['task_name']); ?>
                                <?php if (isset($v['category_name'])) echo '('.$this->escape($v['category_name']).')'; ?>
                                <span class="<?php !is_null($v['task_text'])?print'task-comment':print'task-comment-none';?>">
                                    <a href="javascript:void();" data-task-id="<?php echo $this->escape($v['task_id']); ?>"><span class="glyphicon glyphicon-comment"></span></a>
                                </span>
                                <span>
                                    <a href="/task/delete/<?php echo $this->escape($v['task_id']); ?>"><span class="glyphicon glyphicon-remove-circle"></span></a>
                                </span>
                                <?php if($v['task_limit'] <= date('Y-m-d')):?>
                                <span class="over-deadline glyphicon glyphicon-exclamation-sign"></span>
                                <?php endif; ?>
                                <span class="sort-task glyphicon glyphicon-align-justify"></span>
                            </p>
                            <div class="comment-popup" data-task-id="<?php echo $this->escape($v['task_id']);?>">
                                <p class="comment-cancel"><a><span class="glyphicon glyphicon-remove-circle"></span></a></p>
                                <div class="task-comment"><?php echo $this->escape($v['task_text']);?></div>
                                <!-- <div class="action-area">
                                    <p class="comment-edit-btn btn btn-danger" data-task-id="<?php echo $this->escape($v['task_id']);?>">編集</p>
                                </div> -->
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </form>
        </div>
    </div>
</div>