CREATE DATABASE mydeal;

USE mydeal;

CREATE TABLE users(
	id INT NOT NULL AUTO_INCREMENT,
    date_reg DATETIME DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(30) NOT NULL,
    pass VARCHAR(60) NOT NULL,
    
    PRIMARY KEY(id)
);
CREATE INDEX user_id_index ON users(id);
CREATE INDEX user_email_index ON users(email);



CREATE TABLE project(
	id INT NOT NULL AUTO_INCREMENT,
    project_name VARCHAR(50) NOT NULL,
    author_id INT,
    
    PRIMARY KEY(id),
    FOREIGN KEY(author_id) REFERENCES users(id)
);
CREATE INDEX project_id_index ON project(id);
CREATE INDEX project_author_id_index ON project(author_id);

CREATE TABLE task(
	id INT NOT NULL AUTO_INCREMENT,
    date_create DATETIME DEFAULT CURRENT_TIMESTAMP,
    task_status INT(1) NOT NULL DEFAULT 0,
    task_name VARCHAR(100) NOT NULL,
    file TEXT,
    date_end DATE,
    author_id INT NOT NULL,
    project_id INT NOT NULL,
    
    PRIMARY KEY(id),
    FOREIGN KEY(author_id) REFERENCES users(id),
    FOREIGN KEY(project_id) REFERENCES project(id)
);
CREATE INDEX task_id_index ON task(id);
CREATE INDEX date_end_index ON task(date_end);
CREATE INDEX task_author_id_index ON task(author_id);
CREATE INDEX task_project_id_index ON task(project_id);

CREATE FULLTEXT INDEX search_task_name ON task(task_name);

-- SELECT * FROM task WHERE MATCH (task_name) AGAINST ('зада*' IN BOOLEAN MODE)

