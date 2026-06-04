-- Создание таблицы заявок
CREATE TABLE applications (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    full_name VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    birth_date DATE NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    biography TEXT,
    contract_agreed TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

-- Создание таблицы языков программирования
CREATE TABLE programming_languages (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    PRIMARY KEY (id)
);

-- Создание таблицы связи
CREATE TABLE application_languages (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    application_id INT(10) UNSIGNED NOT NULL,
    language_id INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
    FOREIGN KEY (language_id) REFERENCES programming_languages(id)
);

-- Вставка языков программирования
INSERT INTO programming_languages (name) VALUES 
('Pascal'), ('C'), ('C++'), ('JavaScript'), ('PHP'),
('Python'), ('Java'), ('Haskell'), ('Clojure'),
('Prolog'), ('Scala'), ('Go');