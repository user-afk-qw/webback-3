<?php
header('Content-Type: text/html; charset=UTF-8');

// Параметры подключения к БД (ЗАМЕНИТЕ НА ВАШИ)
$db_user = 'u82292';     // Ваш логин с префиксом u
$db_pass = 'ВАШ_ПАРОЛЬ'; // Ваш пароль
$db_name = 'u82292';     // Имя вашей БД

$errors = [];

try {
    $db = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch(PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. ФИО
    $fio = trim($_POST['fio'] ?? '');
    if (empty($fio)) {
        $errors['fio'] = 'ФИО обязательно для заполнения';
    } elseif (mb_strlen($fio) > 150) {
        $errors['fio'] = 'ФИО не должно превышать 150 символов';
    } elseif (!preg_match('/^[а-яА-ЯёЁa-zA-Z\s\-]+$/u', $fio)) {
        $errors['fio'] = 'ФИО может содержать только буквы, пробелы и дефисы';
    }
    
    // 2. Телефон
    $phone = trim($_POST['phone'] ?? '');
    if (empty($phone)) {
        $errors['phone'] = 'Телефон обязателен для заполнения';
    } elseif (!preg_match('/^[\+\d\s\(\)\-]{10,20}$/', $phone)) {
        $errors['phone'] = 'Неверный формат телефона';
    }
    
    // 3. Email
    $email = trim($_POST['email'] ?? '');
    if (empty($email)) {
        $errors['email'] = 'E-mail обязателен для заполнения';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Неверный формат e-mail';
    }
    
    // 4. Дата рождения
    $birth_date = $_POST['birth_date'] ?? '';
    if (empty($birth_date)) {
        $errors['birth_date'] = 'Дата рождения обязательна';
    } else {
        $date = DateTime::createFromFormat('Y-m-d', $birth_date);
        if (!$date || $date->format('Y-m-d') !== $birth_date) {
            $errors['birth_date'] = 'Неверный формат даты';
        } else {
            $age = date_diff($date, new DateTime())->y;
            if ($age < 18 || $age > 120) {
                $errors['birth_date'] = 'Возраст должен быть от 18 до 120 лет';
            }
        }
    }
    
    // 5. Пол
    $gender = $_POST['gender'] ?? '';
    if (empty($gender)) {
        $errors['gender'] = 'Укажите пол';
    } elseif (!in_array($gender, ['male', 'female'])) {
        $errors['gender'] = 'Неверное значение пола';
    }
    
    // 6. Языки программирования
    $languages = $_POST['languages'] ?? [];
    $valid_languages = ['Pascal', 'C', 'C++', 'JavaScript', 'PHP', 'Python', 'Java', 'Haskell', 'Clojure', 'Prolog', 'Scala', 'Go'];
    
    if (empty($languages)) {
        $errors['languages'] = 'Выберите хотя бы один язык программирования';
    } else {
        foreach ($languages as $lang) {
            if (!in_array($lang, $valid_languages)) {
                $errors['languages'] = 'Выбран недопустимый язык программирования';
                break;
            }
        }
    }
    
    // 7. Биография
    $biography = trim($_POST['biography'] ?? '');
    if (mb_strlen($biography) > 5000) {
        $errors['biography'] = 'Биография не должна превышать 5000 символов';
    }
    
    // 8. Контракт
    $contract = isset($_POST['contract']) && $_POST['contract'] == '1';
    if (!$contract) {
        $errors['contract'] = 'Необходимо подтвердить ознакомление с контрактом';
    }
    
    // Сохранение в БД
    if (empty($errors)) {
        try {
            $db->beginTransaction();
            
            // Вставка в applications
            $stmt = $db->prepare("
                INSERT INTO applications (full_name, phone, email, birth_date, gender, biography, contract_agreed)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$fio, $phone, $email, $birth_date, $gender, $biography, (int)$contract]);
            $app_id = $db->lastInsertId();
            
            // Вставка языков
            $lang_stmt = $db->prepare("SELECT id FROM programming_languages WHERE name = ?");
            $insert_lang = $db->prepare("INSERT INTO application_languages (application_id, language_id) VALUES (?, ?)");
            
            foreach ($languages as $lang_name) {
                $lang_stmt->execute([$lang_name]);
                $lang = $lang_stmt->fetch();
                if ($lang) {
                    $insert_lang->execute([$app_id, $lang['id']]);
                }
            }
            
            $db->commit();
            header('Location: form.php?save=1');
            exit();
            
        } catch(PDOException $e) {
            $db->rollBack();
            $errors['database'] = 'Ошибка БД: ' . $e->getMessage();
        }
    }
}

// Если есть ошибки - показываем форму с ошибками
if (!empty($errors)) {
    include('form.php');
}
?>