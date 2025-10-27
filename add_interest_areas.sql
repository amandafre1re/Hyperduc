USE Voluntec;

-- Create interest_area table
CREATE TABLE IF NOT EXISTS interest_area (
  id_interest_area INT AUTO_INCREMENT PRIMARY KEY,
  name_area VARCHAR(50) NOT NULL UNIQUE,
  icon VARCHAR(50) NULL
);

-- Create interest_area_skill mapping table
CREATE TABLE IF NOT EXISTS interest_area_skill (
  id_interest_area INT NOT NULL,
  id_skill INT NOT NULL,
  PRIMARY KEY (id_interest_area, id_skill),
  FOREIGN KEY (id_interest_area) REFERENCES interest_area(id_interest_area) ON DELETE CASCADE,
  FOREIGN KEY (id_skill) REFERENCES skill(id_skill) ON DELETE CASCADE
);

-- Insert interest areas
INSERT INTO interest_area (name_area, icon) VALUES 
('Design', 'palette'),
('Development', 'code'),
('Data Intelligence', 'graph-up'),
('Business Management', 'briefcase')
ON DUPLICATE KEY UPDATE name_area=name_area;

-- Add sample skills (only if they don't exist)
INSERT IGNORE INTO skill (name_skill, skill_type) VALUES
-- Design skills
('UI/UX Design', 'hard'),
('Figma', 'hard'),
('Photoshop', 'hard'),
('Adobe Illustrator', 'hard'),
('Wireframing', 'hard'),
('Prototyping', 'hard'),
('User Research', 'hard'),
('Design Systems', 'hard'),
-- Development skills
('JavaScript', 'hard'),
('PHP', 'hard'),
('Python', 'hard'),
('Java', 'hard'),
('React', 'hard'),
('Node.js', 'hard'),
('Git', 'hard'),
('SQL', 'hard'),
('HTML/CSS', 'hard'),
('Mobile Development', 'hard'),
-- Data Intelligence skills
('Data Analysis', 'hard'),
('Machine Learning', 'hard'),
('SQL', 'hard'),
('Python', 'hard'),
('Statistics', 'hard'),
('Data Visualization', 'hard'),
('Excel', 'hard'),
('Power BI', 'hard'),
-- Business Management skills
('Communication', 'soft'),
('Teamwork', 'soft'),
('Leadership', 'soft'),
('Project Management', 'soft'),
('Strategic Planning', 'soft'),
('Negotiation', 'soft'),
('Problem Solving', 'soft'),
('Time Management', 'soft');

-- Link skills to interest areas
-- Design skills (id_interest_area = 1)
INSERT INTO interest_area_skill (id_interest_area, id_skill) 
SELECT 1, id_skill FROM skill WHERE name_skill IN (
  'UI/UX Design', 'Figma', 'Photoshop', 'Adobe Illustrator', 
  'Wireframing', 'Prototyping', 'User Research', 'Design Systems'
)
ON DUPLICATE KEY UPDATE id_interest_area=id_interest_area;

-- Development skills (id_interest_area = 2)
INSERT INTO interest_area_skill (id_interest_area, id_skill) 
SELECT 2, id_skill FROM skill WHERE name_skill IN (
  'JavaScript', 'PHP', 'Python', 'Java', 'React', 'Node.js', 
  'Git', 'SQL', 'HTML/CSS', 'Mobile Development'
)
ON DUPLICATE KEY UPDATE id_interest_area=id_interest_area;

-- Data Intelligence skills (id_interest_area = 3)
INSERT INTO interest_area_skill (id_interest_area, id_skill) 
SELECT 3, id_skill FROM skill WHERE name_skill IN (
  'Data Analysis', 'Machine Learning', 'SQL', 'Python', 
  'Statistics', 'Data Visualization', 'Excel', 'Power BI'
)
ON DUPLICATE KEY UPDATE id_interest_area=id_interest_area;

-- Business Management skills (id_interest_area = 4)
INSERT INTO interest_area_skill (id_interest_area, id_skill) 
SELECT 4, id_skill FROM skill WHERE name_skill IN (
  'Communication', 'Teamwork', 'Leadership', 'Project Management',
  'Strategic Planning', 'Negotiation', 'Problem Solving', 'Time Management'
)
ON DUPLICATE KEY UPDATE id_interest_area=id_interest_area;
