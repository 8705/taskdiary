<?php $this->setLayoutVar('title', 'アカウント設定') ?>

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
            <h2>アカウント情報</h2>
            <table class="table-striped table-hover">
                <tr>
                    <th>ユーザー名</th>
                    <td><?php echo $login_user['user_name']; ?></td>
                </tr>
                <tr>
                    <th>メールアドレス</th>
                    <td></td>
                </tr>
                <tr>
                    <th>日記登録日数</th>
                    <td></td>
                </tr>
                <tr>
                    <th>完了したタスク数</th>
                    <td></td>
                </tr>
                <tr>
                    <th>twitter</th>
                    <td></td>
                </tr>
                <tr>
                    <th></th>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
</div>