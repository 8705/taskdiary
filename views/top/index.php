<?php $this->setLayoutVar('title', 'アカウント') ?>

<div class="container">
    <div class="row insert-task">
        <div class="col-md-12 tasks">
            <form action="/task/add_task" method="POST" id="task-form">
                <ul id="task_add">
                    <li class="clearfix">
                        <input type="text" class="input-task form-control" data-input-num="1" name="task_name" placeholder="タスクを入力"/>
                    </li>
                    <input type="checkbox" name="enable-notify" value="1">
                    <select class="" name="notify-year" disabled=disabled>
                      <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?>年</option>
                      <option value="<?php echo date('Y')+1; ?>"><?php echo date('Y')+1; ?>年</option>
                    </select>
                    <select name="notify-month" id="" disabled=disabled>
                      <?php for($i=1;$i<=12;$i++): ?>
                        <option value="<?php echo $i ?>" <?php echo date('n')==$i ?"selected":""; ?>><?php echo $i ?>月</option>
                      <?php endfor; ?>
                    </select>
                    <select name="notify-day" id="" disabled=disabled>
                      <?php for($i=1;$i<=31;$i++): ?>
                        <option value="<?php echo $i ?>" <?php echo date('j')==$i ?"selected":""; ?>><?php echo $i ?>日</option>
                      <?php endfor; ?>
                    </select>
                    <select name="notify-hour" id="" disabled=disabled>
                      <?php for($i=0;$i<=23;$i++): ?>
                        <option value="<?php echo $i ?>" <?php echo date('G')==$i ?"selected":""; ?>><?php echo $i ?>時</option>
                      <?php endfor; ?>
                    </select>
                    <select name="notify-minute" id="" disabled=disabled>
                      <?php for ($i=0;$i<=59;$i +=1): ?>
                      <option value="<?php echo $i ?>" <?php echo date('i')+1==$i ?"selected":""; ?>><?php echo $i; ?>分</option>
                      <?php endfor; ?>
                    </select>
                    <input type="hidden" name="task_time" value="0">
                </ul>
                <!-- <p><input type="submit" value="追加" class="btn btn-info"></p> -->
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <!-- todays tasks -->
            <h2><?php echo (new DateTime)->format('m月d日')."中にやること" ?></h2>
            <form class="task-list" method="POST">
                <ul class="list-group sort-list ui-sortable connected todays">
                    <?php if(count($todays)): ?>
                    <?php foreach ($todays as $v): ?>
                      <li class="task list-group-item <?php if ($v['task_is_done'] == 1) echo 'done'; ?>" id="task_<?php echo $v['task_id']; ?>" data-notify="<?php echo $v['notify_datetime']; ?>">
                          <p>
                              <input type="hidden" name="<?php echo $v['task_id']; ?>" value="0">
                              <input type="checkbox" class="check-task" name="<?php echo $v['task_id']; ?>" value="1" <?php if ($v['task_is_done'] == 1) echo "checked='checked'"; ?> >
                              <span class="task_name"><?php echo $this->escape($v['task_name']); ?></span>
                              <?php if (isset($v['category_name'])) echo '('.$this->escape($v['category_name']).')'; ?>
                              <span class="<?php !is_null($v['task_text'])?print'task-comment':print'task-comment-none';?>">
                                  <a href="javascript:void();" data-task-id="<?php echo $this->escape($v['task_id']); ?>"><span class="glyphicon glyphicon-comment"></span></a>
                              </span>
                              <?php if($v['notify_datetime']): ?>
                                  <span class="notify-time <?php echo date('Y-m-d H:i:s') > $v['notify_datetime']?"over":""; ?>"><?php echo date('Y-m-d H:i', strtotime($v['notify_datetime'])); ?></span>
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
        <div class="col-md-4">
            <!-- futures tasks -->
            <h2>そのうちやること</h2>
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
