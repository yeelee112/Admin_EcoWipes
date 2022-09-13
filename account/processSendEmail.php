<?php 
    $checkSecure = 0;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    require_once '../vendor/PHPMailer/PHPMailer/src/Exception.php';
    require_once '../vendor/PHPMailer/PHPMailer/src/PHPMailer.php';
    require_once '../vendor/PHPMailer/PHPMailer/src/SMTP.php';

    require_once '../vendor/autoload.php';
    require_once '../DataProvider.php';
    
    // $dotenv->required(['DB_SERVER', 'DB_DATABASE', 'DB_USER', 'DB_PASS']);
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $dotenv->required(['USER_APP_GMAIL', 'PASSWORD_APP_GMAIL']);
    $mysqli = DataProvider::getConnection();

    if(isset($_GET['email'])){
        $email = mysqli_real_escape_string($mysqli, $_GET["email"]);
        $checkSecure++;
    }

    $sqlFindEmail = "select * from account_admin where admin_email = '$email'";
    $listFindEmail = DataProvider::execQuery($sqlFindEmail);
    $numRowFindEmail = mysqli_num_rows($listFindEmail);

    if($numRowFindEmail > 0){
        if(!empty($email) && $checkSecure > 0){
            $actual_link  = $_SERVER['HTTP_HOST'];
        
            $token = bin2hex(random_bytes(50));

            $sql = "update account_admin set token = '$token' where admin_email = '$email'";

            DataProvider::execQuery($sql);
            $mail = new PHPMailer();                                  // Passing `true` enables exceptions
                try {
                //Server settings
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;

                $mail->Username   = $_ENV['USER_APP_GMAIL'];                     //SMTP username
                $mail->Password   = $_ENV['PASSWORD_APP_GMAIL'];                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           //Enable implicit TLS encryption
                $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom($_ENV['USER_APP_GMAIL'], 'Admin Thế giới khăn ướt');
                $mail->addAddress($email);     //Add a recipient             //Name is optional
                $mail->addReplyTo('digital@ecowipes.com.vn');


                //Attachments
                // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
                $mail->CharSet = 'UTF-8';
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Tài khoản Admin đặt lại mật khẩu';
                $mail->Body    = '<!DOCTYPE html>
                        <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
                            xmlns:o="urn:schemas-microsoft-com:office:office">
                        
                        <head>
                            <meta charset="utf-8"> <!-- utf-8 works for most cases -->
                            <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn"t be necessary -->
                            <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
                            <meta name="x-apple-disable-message-reformatting"> <!-- Disable auto-scale in iOS 10 Mail entirely -->
                            <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->
                        
                            <link rel="preconnect" href="https://fonts.googleapis.com">
                            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                            <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap"
                                rel="stylesheet">
                            <style>
                                html,body{margin:0 auto !important;padding:0 !important;height:100% !important;width:100% !important;background:#f1f1f1;}*{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;}td a{color:#72be44;}tr th{color:#503629;}div[style*="margin:16px 0"]{margin:0 !important;}table,td{mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;}table{border-spacing:0 !important;border-collapse:collapse !important;table-layout:fixed !important;margin:0 auto !important;}img{-ms-interpolation-mode:bicubic;}a{text-decoration:none;}*[x-apple-data-detectors],.unstyle-auto-detected-links *,.aBn{border-bottom:0 !important;cursor:default !important;color:inherit !important;text-decoration:none !important;font-size:inherit !important;font-family:inherit !important;font-weight:inherit !important;line-height:inherit !important;}.a6S{display:none !important;opacity:0.01 !important;}.im{color:inherit !important;}img.g-img+div{display:none !important;}@media only screen and (min-device-width:320px) and (max-device-width:374px){u~div .email-container{min-width:320px !important;}}@media only screen and (min-device-width:375px) and (max-device-width:413px){u~div .email-container{min-width:375px !important;}}@media only screen and (min-device-width:414px){u~div .email-container{min-width:414px !important;}}
                            </style>
                            <style>
                                .primary{background:#0d0cb5;}.bg_white{background:#ffffff;}.bg_light{background:#fafafa;}.bg_black{background:#000000;}.bg_dark{background:rgba(0, 0, 0, .8);}.email-section{padding:0 2.5em 2.5em 2.5em;}.btn{padding:5px 15px;display:inline-block;}.btn.btn-primary{border-radius:5px;background:#0d0cb5;color:#ffffff;}.btn.btn-white{border-radius:5px;background:#ffffff;color:#000000;}.btn.btn-white-outline{border-radius:5px;background:transparent;border:1px solid #fff;color:#fff;}h1,h2,h3,h4,h5,h6{font-family:"Open Sans", sans-serif;color:#000000;margin-top:0;}body{font-family:"Open Sans", sans-serif;font-weight:400;font-size:15px;line-height:1.8;color:#503629;}a{color:#0d0cb5;}table{}.logo h1{margin:0;}.logo h1 a{color:#000000;font-size:20px;font-weight:700;text-transform:uppercase;font-family:"Open Sans", sans-serif;}.navigation{padding:0;}.navigation li{list-style:none;display:inline-block;;margin-left:5px;font-size:13px;font-weight:500;}.navigation li a{color:rgba(0, 0, 0, .4);}.hero{position:relative;z-index:0;}.hero .overlay{position:absolute;top:0;left:0;right:0;bottom:0;content:"";width:100%;background:#000000;z-index:-1;opacity:.3;}.hero .icon{}.hero .icon a{display:block;width:60px;margin:0 auto;}.hero .text{color:rgba(255, 255, 255, .8);}.hero .text h2{color:#ffffff;font-size:30px;margin-bottom:0;}.heading-section{}.heading-section h2{color:#000000;font-size:20px;margin-top:0;line-height:1.4;font-weight:700;text-transform:uppercase;}.heading-section .subheading{margin-bottom:20px !important;display:inline-block;font-size:13px;text-transform:uppercase;letter-spacing:2px;color:rgba(0, 0, 0, .4);position:relative;}.heading-section .subheading::after{position:absolute;left:0;right:0;bottom:-10px;content:"";width:100%;height:2px;background:#0d0cb5;margin:0 auto;}.heading-section-white{color:rgba(255, 255, 255, .8);}.heading-section-white h2{line-height:1;padding-bottom:0;}.heading-section-white h2{color:#ffffff;}.heading-section-white .subheading{margin-bottom:0;display:inline-block;font-size:13px;text-transform:uppercase;letter-spacing:2px;color:rgba(255, 255, 255, .4);}.icon{text-align:center;}.icon img{}.services{background:rgba(0, 0, 0, .03);}.text-services{padding:10px 10px 0;text-align:center;}.text-services h3{font-size:16px;font-weight:600;}.services-list{padding:0;margin:0 0 20px 0;width:100%;float:left;}.services-list img{float:left;}.services-list .text{width:calc(100% - 60px);float:right;}.services-list h3{margin-top:0;margin-bottom:0;}.services-list p{margin:0;}.text-services .meta{text-transform:uppercase;font-size:14px;}.text-testimony .name{margin:0;}.text-testimony .position{color:rgba(0, 0, 0, .3);}.img{width:100%;height:auto;position:relative;}.img .icon{position:absolute;top:50%;left:0;right:0;bottom:0;margin-top:-25px;}.img .icon a{display:block;width:60px;position:absolute;top:0;left:50%;margin-left:-25px;}.counter{width:100%;position:relative;z-index:0;}.counter .overlay{position:absolute;top:0;left:0;right:0;bottom:0;content:"";width:100%;background:#000000;z-index:-1;opacity:.3;}.counter-text{text-align:center;}.counter-text .num{display:block;color:#ffffff;font-size:34px;font-weight:700;}.counter-text .name{display:block;color:rgba(255, 255, 255, .9);font-size:13px;}.footer{color:rgba(255, 255, 255, .5);}.footer .heading{color:#ffffff;font-size:20px;}.footer ul{margin:0;padding:0;}.footer ul li{list-style:none;margin-bottom:10px;}.footer ul li a{color:rgba(255, 255, 255, 1);}@media screen and (max-width:500px){.icon{text-align:left;}.text-services{padding-left:0;padding-right:20px;text-align:left;}}
                            </style>
                        </head>
                        
                        <body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly;">
                            <center style="width: 100%; background-color: #f1f1f1;">
                                <div
                                    style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
                                </div>
                                <div style="max-width: 600px; margin: 0 auto;" class="email-container">
                                    <!-- BEGIN BODY -->
                                    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                        style="margin: auto;">
                                        <tr>
                                            <td valign="top" class="bg_white" style="padding: 1em 2.5em;">
                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td width="35%" class="logo" style="text-align: left;">
                                                            <a href="https://ecowipes.com.vn" target="_blank">
                                                                <img src="https://lh3.googleusercontent.com/pw/AM-JKLUuW5gfWXGdddiS4lFXYAdq6EyUlvtyLbC4eU9Ibaez98bJCvydMeOub7RilP-BIsEbgsjyBnJ2KvFNTSM_zsQG2qtLHQomZ5YTuXCwQnZTUH_bhB_88g39AnZ_VIBn7BDA4OGUajbzrJCLID8pQKg9=w596-h165-no"
                                                                    style="width:100%">
                                                            </a>
                                                        </td>
                                                        <td width="35%"></td>
                                                        <td width="30%" class="logo" style="text-align: right;">
                                                            <img src="https://lh3.googleusercontent.com/pw/AM-JKLU2uRd7ObrDgPK7Yr1BI-C8DxrQoqsE0Hg64zftkZ0Y6agsBEw2NO5rTyqG1ndsdskjhInHYZYKYR0A-qfLOGZYUnoyYzEVHBSot4eE_hcQDBiwAsLG8J0le7bXHeDGynH09eDTeXxMqnzrnYSXlPGJ=w473-h316-no"
                                                                style="width:100%">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr><!-- end tr -->
                        
                                        <tr>
                                            <td class="bg_white">
                                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                    <tr>
                                                        <td class="bg_white email-section">
                                                            <div class="heading-section" style="text-align: center; padding: 0 30px;">
                                                                <h1 style="color:#503629;margin-bottom: 0;">Đặt lại mật khẩu</h1>
                                                                <p style="margin:0;font-size: 0.8rem;font-weight:600;padding-bottom:20px">Nhấn vào nút bên dưới để đặt lại mật khẩu: </p>
                                                                <a href="'.$actual_link.'/account/new-password?email='.$email.'&token='.$token.'" style="background-color:#72be44;color:white;padding:10px 20px;border-radius:5px;font-weight:500;">Thiết lập lại mật khẩu</a>
                                                                <p style="padding-top:20px">Nếu bạn không yêu cầu, hãy bỏ qua email này.</p>
                                                            </div>
                                                        </td>
                                                    </tr><!-- end: tr -->
                                                </table>
                                            </td>
                                        </tr><!-- end:tr -->
                                        <!-- 1 Column Text + Button : END -->
                                    </table>
                                    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                        style="margin: auto;">
                                        <img width="100%" style="background-color: white; padding-top:30px" src="https://lh3.googleusercontent.com/pw/AM-JKLUUIG08ry3MK2YGgiT4SQQuF0ZF1nQFW9_QHGh_6J2JCKDUVUbuGEi4bNz7CKuIwbX-B07sIaXOAhMQlzq6BuGChrmLiSKWKQBI97kdf2AP1dlGIZRj0RVtTsa5_WHpCNy6p-W6eki_vUal6TxL0qsr=w1560-h282-no">
                                    </table>
                                </div>
                            </center>
                        </body>
                        </html>';
                $mail->send();
                echo true;
            } catch (Exception $e) {
                header('Location: /');
                // echo '<script>alert("1");window.history.back();</script>';
                echo false;

            }
        }
    }
    else{
        echo false;
    }

?>
