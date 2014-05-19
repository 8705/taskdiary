/*
    Task Diary App Js
*/

$(function() {
    //イベントデフォルトを抑止
    function cancelEvent(e) {
        if (e.preventDefault) {
            e.preventDefault();
        } else if (window.event) {
            window.event.returnValue = false;
        }
    }

    function Task($div){
        function getNumberInput(e) {

        }
        function isFirstString(e) {
            if(e.val().length == 0) {
                return true;
            }
            return false;
        }
        function isLastChild(e) {
            // var last_num = $div.find('.input-task:last').data('input-num');
            var last_num = $div.find('.input-task').length;
            if(last_num == e.attr('data-input-num')) {
                return last_num;
            }
            return false;
        }
        function isPreLastChild(e) {
            var pre_last_num = $div.find('.input-task').length - 1;
            if(pre_last_num == e.attr('data-input-num')) {
                return true;
            }
            return false;
        }
        function isPressedEnter(number) {
            if(number === 13) {
                return true;
            }
            return false;
        }
        function appendInput(last_num) {
            var clone = $('input[data-input-num='+last_num+']').parent().clone(true);
            var category = $('input[data-input-num='+last_num+']').parent().find('select').val();
            clone.find('select').val(category);
            $div.append(clone).find('.input-task:last').val('').attr('data-input-num', last_num + 1);
        }
        function isEmpty(number, e) {
            if(number === 8) {
                if(e.val().length === 0) {
                    return true;
                }
            }
            return false;
        }
        function notEmpty(e) {
            if(e.val().length > 0) {
                return true;
            }
            return false;
        }
        function deleteLastInput() {
            var last_input = $div.find('.input-task:last');
            last_input.parent().remove();
        }
        function isPressedJp(number) {
            if(number === 229) {
                return true;
            }

            /*
                firefoxはkeyUp時に13を取得するしかエンター日本語からのエンター判別法がない
            */
            // if(number === 13) {
            //     return true;
            // }

            return false;
        }
        function focusNextInput(e) {
            var focused_id = e.attr('data-input-num');
            var next_focus = parseInt(focused_id) + 1;
            $('input[data-input-num='+ next_focus+']').focus();
        }
        $.extend(this,{
            'getNumberInput'    : getNumberInput,
            'isFirstString'     : isFirstString,
            'isLastChild'       : isLastChild,
            'isPreLastChild'    : isPreLastChild,
            'appendInput'       : appendInput,
            'isPressedEnter'    : isPressedEnter,
            'isEmpty'           : isEmpty,
            'notEmpty'          : notEmpty,
            'deleteLastInput'   : deleteLastInput,
            'isPressedJp'       : isPressedJp,
            'focusNextInput'    : focusNextInput
        });
    }

    var task = new Task($('#task_add'));

    $(document).on('keypress', '.input-task', function(e){
        var number              = task.getNumberInput($(this));
        var is_first_string     = task.isFirstString($(this));
        var is_last             = task.isLastChild($(this));
        var is_pressed_enter    = task.isPressedEnter(e.which);
        var not_empty           = task.notEmpty($(this));
        // var is_empty        = task.isEmpty($(this));

        //最初の文字が入力された
        if(is_last && is_first_string && e.which !== 13) {
            task.appendInput(is_last);
        }

        //改行ではないエンター押下時、次のinpuにフォーカス移動
        if(not_empty && is_pressed_enter) {
            task.focusNextInput($(this));

            //エンターでサブミット押す挙動を止める
            return false;
        }

    });

    $(document).on('keyup', '.input-task', function(e) {
        var is_last             = task.isLastChild($(this));
        var is_pre_last             = task.isPreLastChild($(this));
        var is_empty        = task.isEmpty(e.which, $(this));
        var is_first_string     = task.isFirstString($(this));
        var is_pressed_jp       = task.isPressedJp(e.which);
        //空になった
        if(is_pre_last && is_empty) {
            task.deleteLastInput();
        }

        if(is_last && is_first_string && is_pressed_jp) {
            task.appendInput(is_last);
        }
    });
    $(document).on('keydown','.input-task', function(e) {
        var is_last             = task.isLastChild($(this));
        var is_first_string     = task.isFirstString($(this));
        var is_pressed_jp       = task.isPressedJp(e.which);
        if(is_last && is_first_string && is_pressed_jp && e.which !== 13) {
            task.appendInput(is_last);
        }
    });
    $('#task-submit').click(function(){

    });
    $(document).on('click', '.task-done', function(e){
        var id      = task.getTaskId();
        var is_done = task.isDone();
        task.toggleDone(id, is_done);
    });

    //Check Task
    $(document).on('click','.check-task', function(e){
        cancelEvent(e);
        var taskId      = $(this).attr('name');
        var checked = '';

        $.ajax({
            url: '/task/done/'+ taskId,
            type: 'POST',
            timeout:5000,
            data : {
            },
            beforeSend : function() {
                //$('#task_' + taskId +' .check-task').html('<img src="/img/ajax-loader.gif" alt="" />');
            },
            success:function(data){
                if(data.task_is_done == 1) {
                    checked = true;

                } else {
                    checked = false;
                }
                $('.check-task[name='+data.task_id+']').prop('checked',checked);
                $('.check-task[name='+data.task_id+']').parent().parent().toggleClass('done');

            },
            error : function() {
                // popUpPanel(true, 'サーバーエラー')
            },
            complete : function() {
                // $('#task_' + taskId +' .check-task').html('<input type="checkbox" '+ checked +'/>');
            },
        });
    });

    //sortable
    $('.sort-list').sortable({
        axis : 'y',
        opacity : 0.8,
        cursor : 'move',
        // items : $('.sort-list li:not(.done)'),    //完了しているタスクは並び替え出来ない
        handle : $('li:not(.done)'),    //完了しているタスクは並び替え出来ない
        placeholder: "placeholder",
        // grid : [30,30],
        update : function(){
            $.ajax({
                url : '/task/sort',
                type : 'POST',
                timeout : 5000,
                data : {
                    sequence : $(this).sortable('serialize',{key:'task[]'})
                },
                beforeSend : function() {
                    //全ての編集中のタスクを元に戻す。
                },
                success : function() {

                },
                error : function() {

                },
                complete : function() {

                }
            });
        }
    });
});