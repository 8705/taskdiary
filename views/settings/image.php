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
                        <img src="/image/output/<?php echo $this->escape($user_id) ?>" alt="thumbnail" width="128" height="128">
                    </p>
                </div>
                <div class="col-md-9">
                    <form action="/settings/image" enctype="multipart/form-data" method="POST">
                        <input type="file" name="image" />
                        <p><input type="submit" value="送信" class="btn btn-success"/><p>
                    </form>
                    <?php if ($errors): ?>
                        <?php foreach ($errors as $v) echo $this->escape($v); ?>
                    <?php endif ?> 
                </div>
            </div>
        </div>
    </div>
</div>