<?php $this->setLayoutVar('title', 'アカウント') ?>

<div class="container">
    <div class="row insert-task">
        <div class="col-md-12 tasks">
            <form action="/task/add_task" method="POST" id="task-form">
                <ul id="task_add">
                    <li class="clearfix">
                        <input type="text" class="input-task form-control" data-input-num="1" name="task_name" placeholder="タスクを入力"/>
                        <select name="task_time" class="input-time form-control">
                            <option value="0">何分？</option>
                            <option value="5">5分</option>
                            <option value="10">10分</option>
                            <option value="15">15分</option>
                            <option value="20">20分</option>
                            <option value="30">30分</option>
                            <option value="40">40分</option>
                            <option value="50">50分</option>
                            <option value="60">1時間</option>
                            <option value="90">1時間30分</option>
                            <option value="120">2時間</option>
                            <option value="150">2時間30分</option>
                            <option value="180">3時間</option>
                            <option value="240">4時間</option>
                        </select>
                    </li>
                    <!-- <input type="hidden" name="task_time" value="0"> -->
                </ul>
                <!-- <p><input type="submit" value="追加" class="btn btn-info"></p> -->
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
                                <?php if($v['task_time']): ?>
                                    <span class="task-time label label-primary" data-time="<?php echo $v['task_time']; ?>"><?php echo $v['task_time']; ?> min</span>
                                <?php endif; ?>
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
                                <?php if($v['task_time']): ?>
                                    <span class="task-time label label-primary" data-time="<?php echo $v['task_time']; ?>"><?php echo $v['task_time']; ?> min</span>
                                <?php endif; ?>
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