<?php

    session_start();
    require_once 'connect.php';

    $login = $_POST['login'];
    $password = $_POST['password'];

    $error_fields = [];

    if($login === '') {
        $error_fields[] = 'login';
    }

    if($password === '') {
        $error_fields[] = 'password';
    }

    if (!empty($error_fields)) {

        $response = [
            "status" => false,
            "message" => "Заполните пустые поля",
            "type" => 1,
            "fields" => $error_fields,
        ];
        echo json_encode($response);
        die();
    }

    $password = md5($password);

    $check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$login' AND `password` = '$password'");
    if (mysqli_num_rows($check_user) > 0) {

        $user = mysqli_fetch_assoc($check_user);

        $_SESSION['user'] = [
            "id" => $user['id'],
            "organisation_name" => $user['organisation_name'],
            "login" => $user['login'],
        ];

        $response = [
            "status" => true, //Авторизация успешна
        ];
        echo json_encode($response);

    } else {

        $response = [
            "status" => false, //Авторизация провалена
            "message" => 'Неверный логин или пароль',
        ];
        echo json_encode($response);

    }
    ?>
