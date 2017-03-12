<?php
class Realemail
{
    /*
     * Author - https://twixed.ru/2010/11/14/112
     * Скрипт в принципе работает как надо, но не всегда определяет поддельный емейл.
     * Например для mail.ru может любой адрес принять
     * С яндексом работает отлично
     * Ну а так, тестируйте, пишите на sazanof.ru
     * */
    public function sWrite($socket, $data, $echo = false)
    {
        // отображаем отправляемую команду, если это требуется
        if ($echo) echo $data;
        // отправляем команду в сокет
        fputs($socket, $data);
        // получаем первый байт ответа от сервера
        $answer = fread($socket, 1);
        // узнаем информацию о состоянии потока
        $remains = socket_get_status($socket);
        // и получаем оставшиеся байты ответа от сервера
        if ($remains-- > 0) $answer .= fread($socket, $remains['unread_bytes']);
        // функция возвращает ответ от сервера на переданную команду
        return $answer;
    }
    public function checkEmail($email){
        // получаем данные об MX-записи домена, указанного в email
        $mx = dns_get_record(end(explode("@", $email)), DNS_MX);
        $mx = $mx[0]['target'];
        // открываем сокет и создаем поток
        $socket = fsockopen($mx, 25, $errno, $errstr, 10);
        if (!$socket)
        {
            echo "$errstr ($errno)\n";
        }

        else{
            // отправляем пустую строку, чтобы получить приветствие сервера
            $this->sWrite($socket, "");
            // представляемся сами
            $this->sWrite($socket, "EHLO example.com\r\n");
            $this->sWrite($socket, "MAIL FROM: dummy@example.com\r\n");
            // запрашиваем разрешение на отправку письма адресату
            $response = $this->sWrite($socket, "RCPT TO: $email\r\n");
            //echo $response;
            // закрываем соединение
            $this->sWrite($socket, "QUIT\r\n");
            fclose($socket);
            // ниже идет простейшая обработка полученного ответа
            //echo "\nCheck report:\n";
            if (substr_count($response, "550") > 0) {
                $out = "Required email address does not exist.\n\n";
            }
            else if (substr_count($response, "250") > 0) {
                if (substr_count($response, "OK") > 0) {
                    //echo "  Required email address exists.\n\n";
                    $out = 1;
                }
                else {
                    // echo "  Email address accepted but it looks like the server is working as a relay host.\n\n";
                    $out = 2;
                }
            }
            // временный фикс для 503 gmail
            else if (substr_count($response, "503") > 0) {
                $out = 2;
            }
            else {
                $out = "  Required email address existence was not recovered. Last response:\n  ---\n$response  ---\n\n";
            }
        }
        return $out;
    }

}


?>
