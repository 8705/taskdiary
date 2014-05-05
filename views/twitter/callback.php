<?php $this->setLayoutVar('title', 'Twitterログイン') ?>

<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <h2><?php echo $screen_name;?>さん、Twitterからようこそ！</h2>
            <p>我々のサービスで使用するユーザー名とメールアドレスを入力してください。</p>
            <form class="form-horizontal" action="/account/register" role="form" method="post">
                <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">User Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="user_name" class="form-control" id="inputEmail3" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="user_mail" class="form-control" id="inputEmail3" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                             <label><input type="checkbox" name="is_autologin"/> Remember me</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                         <button type="submit" class="btn btn-default">アカウント登録</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>