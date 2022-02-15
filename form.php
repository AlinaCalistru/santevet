<?php
/**
 * Supplementary json_encode in case php version is < 5.2 (taken from http://gr.php.net/json_encode)
 */
if (!function_exists('json_encode'))
{
    function json_encode($a=false)
    {
        if (is_null($a)) return 'null';
        if ($a === false) return 'false';
        if ($a === true) return 'true';
        if (is_scalar($a))
        {
            if (is_float($a))
            {
                // Always use "." for floats.
                return floatval(str_replace(",", ".", strval($a)));
            }

            if (is_string($a))
            {
                static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
                return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
            }
            else
                return $a;
        }
        $isList = true;
        for ($i = 0, reset($a); $i < count($a); $i++, next($a))
        {
            if (key($a) !== $i)
            {
                $isList = false;
                break;
            }
        }
        $result = array();
        if ($isList)
        {
            foreach ($a as $v) $result[] = json_encode($v);
            return '[' . join(',', $result) . ']';
        }
        else
        {
            foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
            return '{' . join(',', $result) . '}';
        }
    }
}

$errors = array();
if (isset($_POST['submit-me'])) {

    if ($_POST['name'] != "") {
        $_POST['name'] = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        if ($_POST['name'] == "") {
           array_push($errors, 'Va rugam sa introduceti un nume de contact valid.');
        }
    } else {
        array_push($errors, 'Va rugam sa introduceti un nume de contact.');
    }

    if ($_POST['email'] != "") {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "$email <strong>NU</strong> este o adresa de email valida.");
        }
    } else {
        array_push($errors, 'Va rugam sa introduceti o adresa de email');
    }

    if ($_POST['phone'] != "") {
        $phone = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
        if (!filter_var($phone, FILTER_SANITIZE_NUMBER_INT)) {
            array_push($errors, "$phone <strong>NU</strong> este un numar de telefon valid.");
        }
    }
//    NOT REQUIRED
//    else {
//        array_push($errors, 'Va rugam sa introduceti un numar de telefon.');
//    }

    if ($_POST['message'] != "") {
        $_POST['message'] = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
        if ($_POST['message'] == "") {
            array_push($errors, 'Va rugam sa introduceti un mesaj valid.');
        }
    } else {
        array_push($errors, 'Va rugam sa introduceti un mesaj.');
    }

    if (!$errors) {
        $mail_to = 'info@santevet.ro';
        $subject = 'SanteVet.ro - Formular de contact';
        $message  = 'From: ' . $_POST['name'] . "\n";
        $message .= 'Email: ' . $_POST['email'] . "\n";
        $message .= 'phone: ' . $_POST['phone'] . "\n";
        $message .= "Message:\n" . $_POST['message'] . "\n\n";
        if (!mail($mail_to, $subject, $message, "From: $mail_to\r\n")){
            echo json_encode(array(
                "message" => array("Mesajul nu a putut fi trimis, va rugam incercati mai tarziu!"),
                "status" => "failed")
            );
        } else {
            echo json_encode(array("message" => array("Va multumim pentru mesajul trimis!"), "status" => "success"));
        }
    } else {
        echo json_encode(array("message" => $errors, "status" => "failed"));
    }
} else {
    echo json_encode(array("message" => array("Nothing to see here!"), "status" => "failed"));
}
