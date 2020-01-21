USE mydeal;

-- В таблицу users добавляем данные пользователей
INSERT INTO users (date_reg, email, name, pass) 
	VALUES 
    ('2020-01-01 12:00:11', 'meow@gmail.com', 'Константин', 'qwerty'),
    ('2020-01-02 11:12:23', 'cat@gmail.com', 'Егор', '666666'),
    ('2020-01-03 15:23:42', 'kitty@gmail.com', 'Валерий', '123456'),
    ('2020-01-04 14:32:15', 'nyan@gmail.com', 'Анатолий', '654321'),
    ('2020-01-05 13:42:52', 'SOBAKAAGRESSOR666@gmail.com', 'Сашка', 'q1w2e3r4t5y6');


-- В таблицу project добавляем список проектов
INSERT INTO project (project_name) 
	VALUES 
    ('Входящие'),
    ('Учеба'),
    ('Работа'),
    ('Домашние дела'),
    ('Авто');


-- В таблицу task добавляем список дел
INSERT INTO task (author_id, date_create, date_end, project_id, task_name, task_status) 
	VALUES 
    (1, '2019-01-01', '2019-12-01', 3, 'Собеседование в IT компании', 0),
    (1, '2019-01-01', '2020-02-23', 3, 'Выполнить тестовое задание', 0),
    (1, '2019-01-01', '2019-12-21', 2, 'Сделать задание первого раздела', 1),
    (1, '2019-01-01', '2019-12-22', 1, 'Встреча с другом', 0),
    (1, '2019-01-01', NULL, 4, 'Купить корм для кота', 0),
    (1, '2019-01-01', NULL, 4, 'Заказать пиццу', 0);


-- Выберем всё из таблицы проектов
SELECT * from project;
-- Выберем всё из таблицы задач
SELECT * from task;
-- Выберем всё из таблицы пользователей
SELECT * from users;

INSERT INTO project (project_name, author_id) 
	VALUES 
    ('Тестовый проект №1 Константина', 1),
    ('Тестовый проект №2 Константина', 1);


-- Получить список из всех проектов для одного пользователя
SELECT users.name, project.project_name FROM users
RIGHT JOIN project ON users.id = project.author_id
WHERE users.id = 1 OR users.id IS NULL;

-- Получить список всех задач для одного проекта
SELECT task.task_name, project.project_name FROM task
INNER JOIN project ON project.id = task.project_id
WHERE task.project_id = 3;

-- Пометить задачу как выполненную
UPDATE task SET task_status = 1 WHERE task.id = 6;

-- Обновить название задачи по её идентификатору
UPDATE task SET task_name = 'Купить корм для кота и съесть его' WHERE task.id = 5;


-- Добавление новых проектов и задач для пользователя для занятия 6
INSERT INTO project (project_name, author_id) 
	VALUES 
    ('Планы на этот месяц', 5),
    ('Планы на следующий месяц', 5);

INSERT INTO task (author_id, date_create, date_end, project_id, task_name, task_status) 
	VALUES 
    (5, '2020-01-21', '2020-01-31', 8, 'Уволиться', 0),
    (5, '2020-01-21', '2020-02-01', 9, 'Начать стажировку', 0);


    
   
        

