<?php

/*
    コントローラで各種変数をセットする。
    メール本文のテンプレートは /views/mail/*.php に保存
*/

class SendMail
{
    protected $url;
    protected $from_name;
    protected $from;
    protected $to;
    protected $subject;
    protected $template;
    protected $vars;

    public function __construct()
    {
        //メール文字エンコード初期設定
        require_once (dirname(__FILE__).'/../lib/mail/jphpmailer/jphpmailer.php');
        mb_language("japanese");           //言語(日本語)
        mb_internal_encoding("UTF-8");     //内部エンコーディング(UTF-8)

        $this->url          = 'http://taskdiary.8705.co';
        $this->from         = 'info@8705.co';
        $this->from_name    = 'TaskDiary Aap by 8705';
    }

    public function setFrom($from)
    {
        $this->from = $from;
    }

    public function setTo($to)
    {
        $this->to = $to;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function setVars($vars = array())
    {
        $this->vars = $vars;
    }

    public function send()
    {
        $body       = $this->makeMailBody($this->template, $this->vars);
        if(!$body) {
            echo 'ボディなっしんぐ！';
            //例外なげる
        }

        $mail = new JPHPMailer();          //JPHPMailerのインスタンス生成
        $mail->addTo($this->to);                 //宛先(To)をセット
        $mail->setFrom($this->from,$this->from_name);   //差出人(From/From名)をセット
        $mail->setSubject($this->subject);       //件名(Subject)をセット
        $mail->setBody($body);             //本文(Body)をセット

        if (!$mail->send()){
            //例外投げる
            echo("Failed to send mail. Error:".$mail->getErrorMessage());
        }else{
            echo("Send mail OK.");
        }
        exit;
    }

    protected function makeMailBody($template, $vars = array())
    {
        $template_file = dirname(__FILE__).'/../views/mail/' . $template . '.php';
        if(!is_readable($template_file)) {
            echo 'テンプレートがない';exit;
            return false;
        }

        extract($vars);

        ob_start();
        ob_implicit_flush(false);
        require $template_file;
        $body = ob_get_clean();

        return $body;
    }

    protected function clearSettings()
    {
        //同時に複数のメールを送る事が有る場合、変数を初期化するメソッド
    }

    public function sendAuthenticateMail($user_name, $to, $key)
    {
        //$keyをgetタグ付きURLを載せてメール送信
        $subject = "{$user_name}さん、アカウントを確認して下さい";
        $body ="
            アカウントの確認を完了させて下さい。\n
            以下のリンクをクリックして下さい。\n
            {$this->url}/confirm/{$user_name}/{$key}\n
            \n\n
            登録していないのに関わらずこのメッセージを受け取った場合は、無視が一番！
        ";

        $this->sendMail($to, $subject, $body);
    }

    public function sendAuthenticateDoneMail($user_name, $to)
    {
        $subject = "アカウントの認証が完了しました - ToDo Aap (PYNS CREATE)";
        $body = "
            {$user_name}さん\n
            有難うございます。\n
            あなたのアカウントの認証が完了しました。\n
            よかったですね！\n
            これで思う存分にサイトの機能が使えますよ！\n\n

            サイトはこちら！ {$this->url}\n
            \n\n
            登録していないのに関わらずこのメッセージを受け取った場合は、無視が一番！
        ";

        $this->sendMail($to, $subject, $body);
    }
}