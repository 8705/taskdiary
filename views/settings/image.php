<?php $this->setLayoutVar('title', 'プロフィール画像') ?>

<div class="container">
    <div class="row">
        <div class="col-md-3 projects">
            <ul class="list-group">
                <li class="list-group-item"><a href="/settings/image">プロフィール画像設定</a></li>
                <li class="list-group-item"><a href="/settings/mail">メール設定</a></li>
                <li class="list-group-item"><a href="/settings/password">パスワード設定</a></li>
            </ul>
        </div>
        <div class="col-md-9 tasks">
            <h2>プロフィール画像</h2>
            <div class="row">
                <div class="col-md-3">
                    <p class="thumbnail">

                    </p>
                </div>
                <div class="col-md-9">
                    <form enctype="multipart/form-data" action="/settings/update_image" method="POST">
                        <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
                        <input name="image" type="file" />
                        <p><input type="submit" name="save" value="送信" class="btn btn-success"/><p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>