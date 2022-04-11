<?php

function verify_email() {
    $to = 'j.cost@cadicto.nl';
    $subject = 'Thank you for your purchase';
    
    $headers = array(
        "MIME-Version" => "1.0",
        "Content-Type" => "text/html; charset=UTF-8",
        "From" => "support@jeltecost.nl",
        "Replay-To" => "support@jeltecost.nl",
    );
    
    
    $message = file_get_contents('template.php');
    $message2 = str_replace('{{title_subject}}', 'Verificatie', $message);

    $message3 = str_replace('{{body_title}}', "Verificatie voor Jelte's eCloud", $message2);
    $message4 = str_replace('{{body_content}}', 'Bedankt voor het aanmelden bij Jeltes eCloud. We zijn heel blij dat u heeft geregistreerd bij onze Cloud. Druk op Confirm Account om ur account te verifiëren', $message3);
    $message5 = str_replace('{{body_content2}}', 'Als de link niet werkt graag via deze link verifiëren', $message4);

    $message6 = str_replace('{{button_text}}', 'Verify nu!', $message5);
    $message7 = str_replace('{{button_link}}', 'http://jeltecost.nl', $message6);

    $send = mail($to, $subject, $message7, $headers);
    echo ($send ? 'Mail sent' : 'Mail failed');
}

verify_email();


