<?php 

require "./bibliotecas/PHPMailer/Exception.php";
require "./bibliotecas/PHPMailer/OAuth.php";
require "./bibliotecas/PHPMailer/PHPMailer.php";
require "./bibliotecas/PHPMailer/POP3.php";
require "./bibliotecas/PHPMailer/SMTP.php";
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



/////////


class Mensagem {
    
    private $para = null;
    private $assunto = null;
    private $mensagem = null;
    
    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        return $this->$atributo = $valor;
    }

    public function mensagemValida(){
        if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
            
            return false;
        
        }

        return true;
    }
}

$mensagem = new Mensagem();
$mensagem->__set('para', $_POST['para']);
$mensagem->__set('assunto', $_POST['assunto']);
$mensagem->__set('mensagem', $_POST['mensagem']);


if(!$mensagem->mensagemValida()){
    echo 'a mensagem não é válida';
    die;
} 


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);


try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                  //Enable verbose debug output
    $mail->isSMTP();                                        //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                   //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                               //Enable SMTP authentication
    $mail->Username   = 'praticando.dev@gmail.com';         //SMTP username
    $mail->Password   = 'dev12341234';                      //SMTP password
    $mail->SMTPSecure = 'tls';                              //Enable implicit TLS encryption
    $mail->Port       = 587;                                //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`


    //Recipients
    $mail->setFrom('praticando.dev@gmail.com', 'Deyvd - Operação Teste');
    $mail->addAddress('praticando.dev@gmail.com', 'Deyvd - Operação Teste Recebido');     //Add a recipient
    $mail->addAddress($mensagem->__get('para'));     //Add a recipient
    // $mail->addReplyTo('praticando.dev@gmail.com', 'Re>');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Sua mensagem foi enviada';

} catch (Exception $e) {
    echo "Não foi possível enviar este email! Por favor, tente novamente mais tarde";
    echo  "Detalhes do erro: {$mail->ErrorInfo}";
}




?>