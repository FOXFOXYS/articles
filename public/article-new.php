<?php

// activation du système d'autoloading de Composer
require __DIR__.'/../vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../templates');

// instanciation du moteur de template
$twig = new \Twig\Environment($loader, [
    // activation du mode debug
    'debug' => true,
    // activation du mode de variables strictes
    'strict_variables' => true,
]);

// chargement de l'extension Twig_Extension_Debug
$twig->addExtension(new \Twig\Extension\DebugExtension());

$formData = [
    'name' => '',
    'description' => '',
    'price' => '',
    'quantity' => '',
];

$errors = [];
$messages = [];

if ($_POST) {

    // renvoie des donner post au formulaire
    if (isset($_POST['name'])) {
        $formData['name'] = $_POST['name'];
    }
    if (isset($_POST['description'])) {
        $formData['description'] = $_POST['description'];
    }
    if (isset($_POST['price'])) {
        $formData['price'] = $_POST['price'];
    }
    if (isset($_POST['quantity'])) {
        $formData['quantity'] = $_POST['quantity'];
    }

    // verification du champ name
    if (!isset($_POST['name']) || empty($_POST['name'])) {
        $errors['name'] = true;
        $messages['name'] = "Aucun Nom definit !";
    } elseif (strlen($_POST['name']) < 2 || strlen($_POST['name']) > 100 ) {
        $errors['name'] = true;
        $messages['name'] = "Vous devez mettre entre 2 et 100 caractères";
    }

    // verification du champ description 
    if (strpos($_POST['description'], '<') !== false || strpos($_POST['description'], '>') !== false ) {
        $errors['description'] = true;
        $messages['description'] = "caractéres interdit : < & >";
    }

    // verification du champ  price 
    if (!isset($_POST['price']) || empty($_POST['price'])) {
        $errors['price'] = true;
        $messages['price'] = "Aucun Prix definit !";
    } elseif (!is_numeric($_POST['price'])) {
        $errors['price'] = true;
        $messages['price'] = "Ce n'est pas un chiffre !";
    }

    // verification du champ  quantity
    if (!isset($_POST['quantity']) || empty($_POST['quantity'])) {
        $errors['quantity'] = true;
        $messages['quantity'] = "Aucune Quantité definit !";
    } elseif (!is_int(0 + $_POST['quantity']) || !is_numeric($_POST['quantity'])) {
        $errors['quantity'] = true;
        $messages['quantity'] = "Ce n'est pas un nombre entier !";
    } elseif ($_POST['quantity'] <= 0 ) {
        $errors['quantity'] = true;
        $messages['quantity'] = "Quantité invalide !";
    }

    // verification errors global
    if (!$errors) {
        // redirection
        $url = 'articles.php';
        header("Location: {$url}", true, 302);
        exit();
    }
}

// affichage du rendu d'un template
echo $twig->render('article-new.html.twig', [
    // transmission de données au template
    'formData' => $formData,
    'errors' => $errors,
    'messages' => $messages,
]);