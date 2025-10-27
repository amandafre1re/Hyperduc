CREATE DATABASE Voluntec;
USE Voluntec;

-- Tabela de usuários
CREATE TABLE user (
  id_user INT NOT NULL AUTO_INCREMENT,
  name_user VARCHAR(100) NOT NULL,
  email_user VARCHAR(100) NOT NULL,
  password_user VARCHAR(100) NOT NULL,
  birth_date DATE NOT NULL,
  city VARCHAR(50) NOT NULL,
  uf VARCHAR(100) NOT NULL,
  country VARCHAR(100) NOT NULL,
  is_moderator TINYINT NOT NULL,  -- Indica se o usuário é moderador (1 = sim, 0 = não)
  PRIMARY KEY (id_user)
);

-- Tabela de habilidades
CREATE TABLE skill (
  id_skill INT NOT NULL AUTO_INCREMENT,
  name_skill VARCHAR(50) NOT NULL,
  skill_type ENUM('soft', 'hard') NOT NULL,
  PRIMARY KEY (id_skill)
);

-- Tabela de relação usuário-habilidade
CREATE TABLE user_skill (
  id_user_skill INT NOT NULL AUTO_INCREMENT,
  id_user INT NOT NULL,
  id_skill INT NOT NULL,
  PRIMARY KEY (id_user_skill),
  FOREIGN KEY (id_user) REFERENCES user(id_user),  -- Corrigido nome da tabela
  FOREIGN KEY (id_skill) REFERENCES skill(id_skill)  -- Corrigido nome da tabela
);

-- Tabela de tarefas
CREATE TABLE task (
  id_task INT NOT NULL AUTO_INCREMENT,
  title_task VARCHAR(45) NOT NULL,
  PRIMARY KEY (id_task)
);

-- Tabela de relação tarefa-habilidade
CREATE TABLE task_skill (
  id_task_skill INT NOT NULL AUTO_INCREMENT,
  id_task INT NOT NULL,
  id_skill INT NOT NULL,
  PRIMARY KEY (id_task_skill),
  FOREIGN KEY (id_task) REFERENCES task(id_task),
  FOREIGN KEY (id_skill) REFERENCES skill(id_skill)
);

-- Tabela de projetos (ideia -> em_desenvolvimento -> finalizado)
CREATE TABLE project (
  id_project INT NOT NULL AUTO_INCREMENT,  -- Corrigido nome da tabela
  nm_project VARCHAR(45) NOT NULL,  -- Corrigido nome da coluna
  desc_project LONGTEXT NOT NULL,  -- Corrigido nome da coluna
  creation_date DATETIME NOT NULL,
  id_user INT NOT NULL,
  status ENUM('idea', 'ongoing', 'concluded') DEFAULT 'idea',  -- Corrigido valor padrão para 'idea'
  start_date DATETIME NULL,
  end_date DATETIME NULL,
  PRIMARY KEY (id_project),  -- Corrigido nome da tabela
  FOREIGN KEY (id_user) REFERENCES user(id_user)
);

-- Tabela da equipe de voluntários do projeto
CREATE TABLE project_volunteer (
  id_project_volunteer INT NOT NULL AUTO_INCREMENT,
  id_project INT NOT NULL,
  id_user INT NOT NULL,
  is_manager TINYINT NOT NULL,  -- 1 para gerente, 0 para membro
  PRIMARY KEY (id_project_volunteer),
  FOREIGN KEY (id_project) REFERENCES project(id_project),
  FOREIGN KEY (id_user) REFERENCES user(id_user)
);

-- Tabela com habilidades requisitadas para o projeto
CREATE TABLE project_skill (
  id_project_skill INT NOT NULL AUTO_INCREMENT,
  id_project INT NOT NULL,
  id_skill INT NOT NULL,
  PRIMARY KEY (id_project_skill),
  FOREIGN KEY (id_project) REFERENCES project(id_project),
  FOREIGN KEY (id_skill) REFERENCES skill(id_skill)
);

-- Tabela de atribuição de tarefas
CREATE TABLE task_assignment (
  id_task_assignment INT NOT NULL AUTO_INCREMENT,
  id_task INT NOT NULL,
  id_user INT NOT NULL,
  id_project INT NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  assignment_status VARCHAR(45) NOT NULL,
  PRIMARY KEY (id_task_assignment),
  FOREIGN KEY (id_user) REFERENCES user(id_user),
  FOREIGN KEY (id_task) REFERENCES task(id_task),
  FOREIGN KEY (id_project) REFERENCES project(id_project)
);

-- Tabela de avaliação de desempenho
CREATE TABLE performance_assessment (
  id_assessment INT NOT NULL AUTO_INCREMENT,
  id_user INT NOT NULL,
  id_project INT NOT NULL,
  grade INT NOT NULL,  -- A nota será de 1 a 5
  comment LONGTEXT NULL,
  status ENUM('pending', 'evaluated') NOT NULL DEFAULT 'pending',  -- Status da avaliação
  PRIMARY KEY (id_assessment),
  FOREIGN KEY (id_user) REFERENCES user(id_user),
  FOREIGN KEY (id_project) REFERENCES project(id_project),
  CONSTRAINT grade_check CHECK (grade BETWEEN 1 AND 5)  -- Restrição para a nota de 1 a 5
);

-- Tabela de solicitação de participação no projeto
CREATE TABLE project_participation_request (
  id_project_participation_request INT NOT NULL AUTO_INCREMENT,
  id_project INT NOT NULL,
  id_user INT NOT NULL,
  request_status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',  -- Status em inglês
  data_request TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data de solicitação
  data_response TIMESTAMP NULL,  -- Data de resposta (aprovada ou rejeitada)
  PRIMARY KEY (id_project_participation_request),
  FOREIGN KEY (id_project) REFERENCES project(id_project),
  FOREIGN KEY (id_user) REFERENCES user(id_user)
);