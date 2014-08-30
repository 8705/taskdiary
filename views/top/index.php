<?php $this->setLayoutVar('title', 'アカウント') ?>

<div class="container">
    <div class="row">
        <div class="col-md-9 tasks col-md-offset-1">

            <form action="/task/add_task" method="POST">
                <ul id="task_add">
                    <li class="clearfix">
                        <!-- <input type="text" name="category_name[]" class="input-category" data-input-num="1" placeholder="カテゴリを入力"> -->
                        <input type="text" class="input-task form-control" data-input-num="1" name="task_name[]" placeholder="タスクを入力"/>
                    </li>
                </ul>
                <p><input type="submit" value="追加" class="btn btn-primary"></p>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <!-- todays tasks -->
            <h2>today's</h2>
            <form class="task-list" method="POST">
                <ul class="list-group sort-list ui-sortable connected todays">
                    <?php if(count($todays)): ?>
                    <?php foreach ($todays as $v): ?>
                        <li class="task list-group-item <?php if ($v['task_is_done'] == 1) echo 'done'; ?>" id="task_<?php echo $v['task_id']; ?>">
                            <p>
                                <input type="hidden" name="<?php echo $v['task_id']; ?>" value="0">
                                <input type="checkbox" class="check-task" name="<?php echo $v['task_id']; ?>" value="1" <?php if ($v['task_is_done'] == 1) echo "checked='checked'"; ?> >
                                <?php echo $this->escape($v['task_name']); ?>
                                <?php if (isset($v['category_name'])) echo '('.$this->escape($v['category_name']).')'; ?>
                                <span class="<?php !is_null($v['task_text'])?print'task-comment':print'task-comment-none';?>">
                                    <a href="javascript:void();" data-task-id="<?php echo $this->escape($v['task_id']); ?>"><span class="glyphicon glyphicon-comment"></span></a>
                                </span>
                                <span class="delete-task">
                                    <a href="/task/delete/<?php echo $this->escape($v['task_id']); ?>"><span class="glyphicon glyphicon-remove-circle"></span></a>
                                </span>
                                <?php if($v['task_limit'] <= date('Y-m-d')):?>
                                <span class="over-deadline glyphicon glyphicon-exclamation-sign"></span>
                                <?php endif; ?>
                            </p>
                        </li>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <li class="task list-group-item empty-task">
                            タスクがありません
                        </li>
                    <?php endif; ?>
                </ul>
            </form>
        </div>
        <div class="col-md-4">
            <!-- futures tasks -->
            <h2>future's</h2>
            <form class="task-list" method="POST">
                <ul class="list-group sort-list ui-sortable connected futures">
                    <?php if(count($futures)): ?>
                    <?php foreach ($futures as $v): ?>
                        <li class="task list-group-item <?php if ($v['task_is_done'] == 1) echo 'done'; ?>" id="task_<?php echo $v['task_id']; ?>">
                            <p>
                                <input type="hidden" name="<?php echo $v['task_id']; ?>" value="0">
                                <input type="checkbox" class="check-task" name="<?php echo $v['task_id']; ?>" value="1" <?php if ($v['task_is_done'] == 1) echo "checked='checked'"; ?> disabled="disabled">
                                <?php echo $this->escape($v['task_name']); ?>
                                <?php if (isset($v['category_name'])) echo '('.$this->escape($v['category_name']).')'; ?>
                                <span class="<?php !is_null($v['task_text'])?print'task-comment':print'task-comment-none';?>">
                                    <a href="javascript:void();" data-task-id="<?php echo $this->escape($v['task_id']); ?>"><span class="glyphicon glyphicon-comment"></span></a>
                                </span>
                                <span class="delete-task">
                                    <a href="/task/delete/<?php echo $this->escape($v['task_id']); ?>"><span class="glyphicon glyphicon-remove-circle"></span></a>
                                </span>
                            </p>
                        </li>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <li class="task list-group-item empty-task">
                            タスクがありません
                        </li>
                    <?php endif; ?>
                </ul>
            </form>
        </div>
        </div>
    </div>
</div>