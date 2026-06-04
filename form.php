<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Анкета разработчика</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .form-content { padding: 30px; }
        .form-group { margin-bottom: 20px; }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .required:after { content: " *"; color: red; }
        input[type="text"],
        input[type="tel"],
        input[type="email"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
        }
        textarea { min-height: 100px; resize: vertical; }
        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 5px;
        }
        .radio-group label {
            display: inline-flex;
            align-items: center;
            font-weight: normal;
        }
        .radio-group input { width: auto; margin-right: 5px; }
        select[multiple] { height: 150px; }
        .checkbox-group { margin: 20px 0; }
        .checkbox-group label {
            display: inline-flex;
            align-items: center;
            font-weight: normal;
        }
        .checkbox-group input { width: auto; margin-right: 10px; }
        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }
        .submit-btn:hover { transform: translateY(-2px); }
        .error-message {
            background: #fee;
            color: #c33;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
        }
        .success-message {
            background: #efe;
            color: #3c3;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #3c3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Анкета разработчика</h1>
            <p>Заполните форму для регистрации</p>
        </div>
        <div class="form-content">
            <?php if (isset($_GET['save']) && $_GET['save'] == 1): ?>
                <div class="success-message">
                    ✅ Спасибо! Ваши данные успешно сохранены.
                </div>
            <?php endif; ?>
            
            <form action="index.php" method="POST">
                <div class="form-group">
                    <label class="required">ФИО</label>
                    <input type="text" name="fio" value="<?= htmlspecialchars($_POST['fio'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label class="required">Телефон</label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label class="required">E-mail</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label class="required">Дата рождения</label>
                    <input type="date" name="birth_date" value="<?= htmlspecialchars($_POST['birth_date'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label class="required">Пол</label>
                    <div class="radio-group">
                        <label><input type="radio" name="gender" value="male" <?= (($_POST['gender'] ?? '') == 'male') ? 'checked' : '' ?>> Мужской</label>
                        <label><input type="radio" name="gender" value="female" <?= (($_POST['gender'] ?? '') == 'female') ? 'checked' : '' ?>> Женский</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="required">Любимые языки программирования</label>
                    <select name="languages[]" multiple>
                        <option value="Pascal">Pascal</option>
                        <option value="C">C</option>
                        <option value="C++">C++</option>
                        <option value="JavaScript">JavaScript</option>
                        <option value="PHP">PHP</option>
                        <option value="Python">Python</option>
                        <option value="Java">Java</option>
                        <option value="Haskell">Haskell</option>
                        <option value="Clojure">Clojure</option>
                        <option value="Prolog">Prolog</option>
                        <option value="Scala">Scala</option>
                        <option value="Go">Go</option>
                    </select>
                    <small>Удерживайте Ctrl для множественного выбора</small>
                </div>
                
                <div class="form-group">
                    <label>Биография</label>
                    <textarea name="biography"><?= htmlspecialchars($_POST['biography'] ?? '') ?></textarea>
                </div>
                
                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" name="contract" value="1" <?= (isset($_POST['contract']) && $_POST['contract'] == '1') ? 'checked' : '' ?>>
                        Я ознакомлен(а) с контрактом и согласен(на) с условиями
                    </label>
                </div>
                
                <input type="submit" class="submit-btn" value="Сохранить">
            </form>
        </div>
    </div>
</body>
</html>