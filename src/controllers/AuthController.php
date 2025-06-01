<?php


// La fonction pour afficherr la page home
function login() 
{
    $errorMessage = ''; // Variable pour stocker le message d'erreur

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require '../src/config/database.php';
        $email = $_POST['email'];
        $password = $_POST['password']; // Pas de hash ici, on compare l'entrée utilisateur

        // Requête pour récupérer l'utilisateur par email
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            header('Location: ?route=home');
            exit;
        } else {
            $errorMessage = "Email ou mot de passe incorrect."; // Message d'erreur
        }
    }

    // Inclure la vue avec le message d'erreur
    include __DIR__ . '/../views/login.php';
}


function register () 
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require '../src/config/database.php';
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
        if ($stmt->execute([$username, $email, $password, 'customer'])) {
            header('Location: ?route=login');
            exit;
        } else {
            echo "Erreur lors de l'inscription.";
        }
    }
    include __DIR__ . '/../views/register.php'; 
}



function logout () 
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();
        session_destroy();
        header('Location: ?route=login');
        exit;
    }
    include __DIR__ . '/../views/home.php'; 
}



