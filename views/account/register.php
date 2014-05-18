<?php $this->setLayoutVar('title', 'アカウント登録') ?>

<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">

            <h2>新規ユーザー登録</h2>
            <form class="form-horizontal" action="/account/register" role="form" method="post" novalidate>
                <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">User Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="user_name" class="form-control <?php isset($errors['user_name'])? print 'error':print''; ?>" id="inputEmail3" value="<?php echo $this->escape($user_name); ?>"/>
                        <?php if(isset($errors['user_name'])): ?>
                        <p class="error-message"><?php echo $errors['user_name'][0]; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="user_mail" class="form-control <?php isset($errors['user_mail'])? print 'error':print''; ?>" id="inputEmail3" value="<?php echo $this->escape($user_mail); ?>"/>
                            <?php if(isset($errors['user_mail'])): ?>
                            <p class="error-message"><?php echo $errors['user_mail'][0]; ?></p>
                            <?php endif; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" name="user_password" class="form-control <?php isset($errors['user_password'])? print 'error':print''; ?>" id="inputPassword3" value="<?php echo $this->escape($user_password); ?>"/>
                        <?php if(isset($errors['user_password'])): ?>
                        <p class="error-message"><?php echo $errors['user_password'][0]; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                             <label><input type="checkbox" name="is_autologin" value="off" <?php if(!is_null($is_autologin))print"checked"; ?>/> Remember me</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                         <button type="submit" class="btn btn-default">アカウント作成</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>