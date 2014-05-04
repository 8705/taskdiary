<?php

/*
    基本のメールを送る機能はsendMail()で実装している
    用途に合わせて、タイトル等カスタマイズしたものを専用のメール送信関数として実装し、
    その中でsendMail()を呼び出す
*/

class Sendmail
{
    protected $url;
    protected $from;
    protected $from_name;

    public function __construct()
    {
        //メール文字エンコード初期設定
        require_once (dirname(__FILE__).'/jphpmailer/jphpmailer.php');
        mb_language("japanese");           //言語(日本語)
        mb_internal_encoding("UTF-8");     //内部エンコーディング(UTF-8)

        $this->url          = 'http://mytodo.pyns.jp';
        $this->from         = 'info@pyns.jp';
        $this->from_name    = 'ToDo Aap PYNS CREATE';
    }

    protected function sendMail($to, $subject, $body)
    {
        $from       = $this->from;      //差出人
        $fromname   = $this->from_name;      //差し出し人名
        // $attachfile = "./file.zip";        //添付ファイルパス

        $mail = new JPHPMailer();          //JPHPMailerのインスタンス生成
        $mail->addTo($to);                 //宛先(To)をセット
        $mail->setFrom($from,$fromname);   //差出人(From/From名)をセット
        $mail->setSubject($subject);       //件名(Subject)をセット
        $mail->setBody($body);             //本文(Body)をセット
        // $mail->addAttachment($attachfile); //添付ファイル追加

        if (!$mail->send()){
            echo("Failed to send mail. Error:".$mail->getErrorMessage());
        }else{
            echo("Send mail OK.");
        }
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