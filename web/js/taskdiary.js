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
            console.log(last_num);
            var clone = $('input[data-input-num='+last_num+']').parent().clone(true);
            //var category = $('input[data-input-num='+last_num+']').parent().find('.input-category').val();
            // clone.find('.input-caegory').val('unko');
            $div.append(clone).find('.input-task:last').val('').attr('data-input-num', last_num + 1);
            // $div.append(clone).find('.input-task:last').val('');
            // $('#add_task li:last').find('input').each().val('test');
            // $('#add_task li:last').find('input').attr('data-input-num', last_num + 1);
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

        //コメント追加用
        function openEditor($this) {
            // this.closeComment();
            var task_id      = $this.data('task-id');
            var offset = $this.offset();
            var elm = this.getElm('comment-edit', task_id);
            $('body').append(elm);
            // $("#comment-editor").css({
            //     position:'absolute',
            //     top : (offset.top - 50)+'px',
            //     left : (offset.left - 100 )+'px'
            // })
        }

        function openPopup($this) {
            var task_id      = $this.data('task-id');
            var elm = this.getElm('comment-popup', task_id);
            var offset = $this.offset();
            $('body').append(elm);
            // $("#comment-popup").css({
            //     position:'absolute',
            //     top : (offset.top - 50)+'px',
            //     left : (offset.left - 100 )+'px'
            // })
            // $('.comment-popup[data-task-id='+task_id+']').show();
        }

        function closeComment(elm) {
            if(elm === undefined) {
                $('#comment-editor').remove();
                $('#comment-popup').remove();
            } else if(elm === 'popup') {
                $('#comment-popup').remove();
            }
        }

        function getElm(name, id) {
            var elm = '';
            if(name === 'comment-edit') {
                elm = $(
                    '<div id="comment-editor" data-task-id="'+id+'">'+
                        '<p class="comment-cancel"><a><span class="glyphicon glyphicon-remove-circle"></span></a></p>'+
                        '<textarea></textarea>'+
                        '<div class="action-area">'+
                            '<p class="comment-submit-btn" data-task-id="'+id+'">送信</p>'+
                        '</div>'+
                    '</div>'
                );
            } else if(name == 'comment-popup') {
                elm = $(
                    '<div id="comment-popup" data-task-id="'+id+'">'+
                        '<p class="comment-cancel"><a><span class="glyphicon glyphicon-remove-circle"></span></a></p>'+
                        '<div class="task-comment"></div>'+
                        '<div class="action-area">'+
                            '<p class="comment-edit-btn" data-task-id="'+id+'">編集</p>'+
                        '</div>'+
                    '</div>'
                );
            } else if(name == 'category-list') {
                elm = $(
                    '<ul class="input-category-list list-group" data-input-num="'+ id +'">'+
                    '</ul>'
                );
            }
            return elm;
        }
        function ajastComment(task_id) {
            var offset = $('#task_'+task_id).offset();
            $('#comment-popup, #comment-editor').css({
                position:'absolute',
                top : (offset.top - 0)+'px',
                left : (offset.left + 160 )+'px'
            });
        }
        function getCategoryList() {
            //ajax
            $.ajax({
                url: '/category/get_list/',
                type: 'POST',
                dateType : 'json',
                timeout:5000,
                data : {
                },
                beforeSend : function() {

                },
                success:function(data){
                    for ( i in data.category_list) {
                        $('.input-category-list').append(
                            '<li class="list-group-item">'+ data.category_list[i].category_name +'</li>'
                        )
                    }
                },
                error : function() {
                },
                complete : function() {

                },
            });
        }

        function selectCategory(e) {
            var category_name = e.text();
            console.log('text : '+category_name);
            var input_num = e.parent().attr('data-input-num');
            console.log('input_num : '+input_num);
            $('.input-task[data-input-num='+input_num+']').parent().find('.input-category').val(category_name);
        }
        function removeCategoryList(mode) {
            // var on_category_list = false;
            // $('.input-category-list').hover(
            //     function(){
            //         on_category_list = true;
            //         console.log(on_category_list);
            //     },
            //     function(){
            //         on_category_list = false;
            //         console.log(on_category_list);
            //     }
            // )
            // if(bool == true) {
            //     if(on_category_list == false) {
            //         $('.input-category-list').remove();
            //         return;
            //     }
            //     return;
            // }

            if(mode == 'remove') {
                if($('.input-category-list').hasClass('non-remove')) {
                    return;
                }
                $('.input-category-list').remove();
            } else if(mode == 'hide') {
                $('.input-category-list').hide();
            }
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
            'focusNextInput'    : focusNextInput,
            'openEditor'        : openEditor,
            'openPopup'         : openPopup,
            'closeComment'      : closeComment,
            'getElm'            : getElm,
            'ajastComment'      : ajastComment,
            'getCategoryList'   : getCategoryList,
            'selectCategory'    : selectCategory,
            'removeCategoryList': removeCategoryList
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
            success:function(data, status, xhr){
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

    //コメントなしアイコンクリック
    $(document).on('click', '.task-comment-none a', function(e){
        cancelEvent(e);
        task.closeComment();
        task.openEditor($(this));
        var task_id = $(this).data('task-id');
        task.ajastComment(task_id);
        $('#comment-editor textarea').focus();
    });

    //コメント有りアイコンクリック
    $(document).on('click', '.task-comment a', function(e){
        cancelEvent(e);
        task.closeComment();
        var task_id = $(this).data('task-id');
        task.openPopup($(this));
        task.ajastComment(task_id);
        $.ajax({
            url      : '/task/get_comment/'+ task_id,
            type     : 'POST',
            dateType : 'json',
            timeout  : 5000,
            data     : {
            },
            beforeSend : function() {
            },
            success:function(data){
                $('#comment-popup .task-comment').html(data.task_text);
            },
            error : function() {
                // popUpPanel(true, 'サーバーエラー')
            },
            complete : function() {

            },
        });
    });

    $(document).on('click', '.comment-edit-btn', function(e){
        cancelEvent(e);
        var task_id = $(this).data('task-id');
        var text = $('#comment-popup .task-comment').text();
        task.openEditor($(this));
        task.ajastComment(task_id);
        task.closeComment('popup');
        $('#comment-editor textarea').focus();
        $('#comment-editor textarea').val(text);
    })

    $(document).on('click', '.comment-cancel' ,function(e) {
        cancelEvent(e);
        task.closeComment();
    });


    $(document).on('click', '.comment-submit-btn', function(e){
        cancelEvent(e);

        var task_id = $(this).data('task-id');
        var text = $('#comment-editor textarea').val();
        $.ajax({
            url: '/task/add_comment/'+ task_id,
            type: 'POST',
            dateType : 'json',
            timeout:5000,
            data : {
                task_text:text
            },
            beforeSend : function() {
                //コメント空なら終了
                if(text === '') {
                    return false;
                }
            },
            success:function(data){
                if(data.task_text !== '') {
                    $('#task_'+data.task_id).find('.task-comment-none').removeClass('task-comment-none').addClass('task-comment');
                }
            },
            error : function() {
                // popUpPanel(true, 'サーバーエラー')
            },
            complete : function() {

            },
        });
        task.closeComment();
    });

    $(document).on('focus', '.input-category', function(e){
        //すでにある他のinputのカテゴリリスト消去
        task.removeCategoryList('remove');
        input_num = $(this).parent().find('.input-task').attr('data-input-num');
        //すでにulがないか確認
        if(!$(this).next().hasClass('input-category-list')) {
            var ul = task.getElm('category-list', input_num);
            $(this).after(ul);
        }

        var category_list = task.getCategoryList();
    });
    $(document).on('blur', '.input-category', function(e){
        task.removeCategoryList('remove');
    });
    $(document).on('click', '.input-category-list li', function(e){
        task.selectCategory($(this));
        task.removeCategoryList('hide');
    });
    $(document).on({
        mouseenter: function(){
            $(this).addClass('non-remove');
        },
        mouseleave: function(){
            $(this).removeClass('non-remove');
        }
    }, '.input-category-list');

    //sortable
    $('.sort-list').sortable({
        axis        : 'y',
        opacity     : 0.8,
        cursor      : 'move',
        connectWith : '.connected',
        // items       : '.sort-task',    //完了しているタスクは並び替え出来ない
        handle      : '.sort-task',
        placeholder : "placeholder",
        // grid : [30,30],
        start : function() {
            task.closeComment();
        },
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