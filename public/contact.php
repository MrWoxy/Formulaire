<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

// activation du système d'autoloading de Composer
require __DIR__.'/formulaire/vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new FilesystemLoader(__DIR__.'/public/templates');

// instanciation du moteur de template
$twig = new Environment($loader, [
    // activation du mode debug
    'debug' => true,
    // activation du mode de variables strictes
    'strict_variables' => true,
]);
$twig->addExtension(new DebugExtension());

dump($_POST);

$formData = [
    'email' => '',
    'subject' => '',
];

$errors = [];

if ($_POST) {

    if (isset($_POST['email'])){
        $formData['email'] = $_POST['email'];
    }
    if (isset($_POST['subject'])){
        $formData['subject'] = $_POST['subject'];
    }
    $maxLength = 190;
    $minLengthSubject = 3;
    $maxLengthSubject = 190;

    if (empty($_POST['email'])) {
        $errors['email'] = 'merci de renseigner ce champ';
    } elseif (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
        $errors['email'] = "merci de renseigner un email valide dont la longueur est comprise entre {$minLengthSubject} et {$maxLengthSubject}";
    }

    if (empty($_POST['subject'])) {
        $errors['subject'] = 'merci de renseigner ce champ';
    }
    elseif (strlen($_POST['subject']) < 3 || strlen($_POST['subject']) > 190) {
        $errors['subject'] = "Merci de renseigner un sujet dont la longueur est comprise entre {$minLengthSubject} et {$maxLengthSubject} inclus";
    }

    $minLengthMessage = 3;
    $maxLengthMessage = 1000;

    if (empty($_POST['text'])) {
        $errors['text'] = 'merci de renseigner ce champ';
    }
    elseif (strlen($_POST['message']) < 3 || strlen($_POST['message']) > 1000) {
        $errors['message'] = "Merci de renseigner un message dont la longueur est comprise entre {$minLengthMessage} et {$maxLengthMessage} inclus";
    }


    // si il n'y a pas d'erreur, on redirige l'utilsateur vers la page d'accueil
    if (!$errors){
        $url = '/';
        header("Location: {$url}", true, 302);
    }
}

// affichage du rendu d'un template
echo $twig->render('contact.html.twig', [
    'errors' => $errors,
    'formData' => $formData,
]);