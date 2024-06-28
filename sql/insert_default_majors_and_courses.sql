-- Use the existing database
USE UniMe;

-- Insert default Bachelor's majors
INSERT INTO majors (name, level) VALUES
                                     ('Computer Science', 'bachelor'),
                                     ('Business Administration', 'bachelor'),
                                     ('Mechanical Engineering', 'bachelor'),
                                     ('Biology', 'bachelor'),
                                     ('Physics', 'bachelor'),
                                     ('Chemistry', 'bachelor'),
                                     ('Electrical Engineering', 'bachelor'),
                                     ('Mathematics', 'bachelor'),
                                     ('History', 'bachelor'),
                                     ('Psychology', 'bachelor')
ON DUPLICATE KEY UPDATE name=name;

-- Insert default Master's majors
INSERT INTO majors (name, level) VALUES
                                     ('Data Science', 'master'),
                                     ('Business Analytics', 'master'),
                                     ('Advanced Mechanical Engineering', 'master'),
                                     ('Biotechnology', 'master'),
                                     ('Astrophysics', 'master'),
                                     ('Organic Chemistry', 'master'),
                                     ('Advanced Electrical Engineering', 'master'),
                                     ('Applied Mathematics', 'master'),
                                     ('Historical Studies', 'master'),
                                     ('Clinical Psychology', 'master')
ON DUPLICATE KEY UPDATE name=name;

-- Insert default Bachelor's courses with credits
INSERT INTO courses (name, major_id, professor_id, credits, level) VALUES
-- Courses for Computer Science
('Introduction to Programming', (SELECT id FROM majors WHERE name = 'Computer Science' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Data Structures', (SELECT id FROM majors WHERE name = 'Computer Science' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Algorithms', (SELECT id FROM majors WHERE name = 'Computer Science' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Operating Systems', (SELECT id FROM majors WHERE name = 'Computer Science' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Database Systems', (SELECT id FROM majors WHERE name = 'Computer Science' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Computer Networks', (SELECT id FROM majors WHERE name = 'Computer Science' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Software Engineering', (SELECT id FROM majors WHERE name = 'Computer Science' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Artificial Intelligence', (SELECT id FROM majors WHERE name = 'Computer Science' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Web Development', (SELECT id FROM majors WHERE name = 'Computer Science' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Cybersecurity', (SELECT id FROM majors WHERE name = 'Computer Science' AND level = 'bachelor'), NULL, 3, 'bachelor'),

-- Courses for Business Administration
('Introduction to Business', (SELECT id FROM majors WHERE name = 'Business Administration' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Microeconomics', (SELECT id FROM majors WHERE name = 'Business Administration' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Macroeconomics', (SELECT id FROM majors WHERE name = 'Business Administration' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Financial Accounting', (SELECT id FROM majors WHERE name = 'Business Administration' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Managerial Accounting', (SELECT id FROM majors WHERE name = 'Business Administration' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Business Law', (SELECT id FROM majors WHERE name = 'Business Administration' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Marketing Principles', (SELECT id FROM majors WHERE name = 'Business Administration' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Operations Management', (SELECT id FROM majors WHERE name = 'Business Administration' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Business Ethics', (SELECT id FROM majors WHERE name = 'Business Administration' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Strategic Management', (SELECT id FROM majors WHERE name = 'Business Administration' AND level = 'bachelor'), NULL, 3, 'bachelor'),

-- Courses for Mechanical Engineering
('Statics', (SELECT id FROM majors WHERE name = 'Mechanical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Dynamics', (SELECT id FROM majors WHERE name = 'Mechanical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Mechanics of Materials', (SELECT id FROM majors WHERE name = 'Mechanical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Thermodynamics', (SELECT id FROM majors WHERE name = 'Mechanical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Fluid Mechanics', (SELECT id FROM majors WHERE name = 'Mechanical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Heat Transfer', (SELECT id FROM majors WHERE name = 'Mechanical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Machine Design', (SELECT id FROM majors WHERE name = 'Mechanical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Control Systems', (SELECT id FROM majors WHERE name = 'Mechanical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Manufacturing Processes', (SELECT id FROM majors WHERE name = 'Mechanical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Engineering Materials', (SELECT id FROM majors WHERE name = 'Mechanical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),

-- Courses for Biology
('Cell Biology', (SELECT id FROM majors WHERE name = 'Biology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Genetics', (SELECT id FROM majors WHERE name = 'Biology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Microbiology', (SELECT id FROM majors WHERE name = 'Biology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Evolution', (SELECT id FROM majors WHERE name = 'Biology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Biochemistry', (SELECT id FROM majors WHERE name = 'Biology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Ecology', (SELECT id FROM majors WHERE name = 'Biology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Plant Biology', (SELECT id FROM majors WHERE name = 'Biology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Animal Behavior', (SELECT id FROM majors WHERE name = 'Biology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Physiology', (SELECT id FROM majors WHERE name = 'Biology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Molecular Biology', (SELECT id FROM majors WHERE name = 'Biology' AND level = 'bachelor'), NULL, 3, 'bachelor'),

-- Courses for Physics
('Classical Mechanics', (SELECT id FROM majors WHERE name = 'Physics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Electromagnetism', (SELECT id FROM majors WHERE name = 'Physics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Thermodynamics and Statistical Mechanics', (SELECT id FROM majors WHERE name = 'Physics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Quantum Mechanics', (SELECT id FROM majors WHERE name = 'Physics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Optics', (SELECT id FROM majors WHERE name = 'Physics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Modern Physics', (SELECT id FROM majors WHERE name = 'Physics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Computational Physics', (SELECT id FROM majors WHERE name = 'Physics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Nuclear Physics', (SELECT id FROM majors WHERE name = 'Physics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Solid State Physics', (SELECT id FROM majors WHERE name = 'Physics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('General Relativity', (SELECT id FROM majors WHERE name = 'Physics' AND level = 'bachelor'), NULL, 3, 'bachelor'),

-- Courses for Chemistry
('General Chemistry', (SELECT id FROM majors WHERE name = 'Chemistry' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Organic Chemistry', (SELECT id FROM majors WHERE name = 'Chemistry' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Inorganic Chemistry', (SELECT id FROM majors WHERE name = 'Chemistry' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Physical Chemistry', (SELECT id FROM majors WHERE name = 'Chemistry' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Analytical Chemistry', (SELECT id FROM majors WHERE name = 'Chemistry' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Environmental Chemistry', (SELECT id FROM majors WHERE name = 'Chemistry' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Materials Chemistry', (SELECT id FROM majors WHERE name = 'Chemistry' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Theoretical Chemistry', (SELECT id FROM majors WHERE name = 'Chemistry' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Industrial Chemistry', (SELECT id FROM majors WHERE name = 'Chemistry' AND level = 'bachelor'), NULL, 3, 'bachelor'),

-- Courses for Electrical Engineering
('Circuit Analysis', (SELECT id FROM majors WHERE name = 'Electrical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Electronics', (SELECT id FROM majors WHERE name = 'Electrical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Digital Logic Design', (SELECT id FROM majors WHERE name = 'Electrical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Signals and Systems', (SELECT id FROM majors WHERE name = 'Electrical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Electromagnetics', (SELECT id FROM majors WHERE name = 'Electrical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Control Systems', (SELECT id FROM majors WHERE name = 'Electrical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Power Systems', (SELECT id FROM majors WHERE name = 'Electrical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Communication Systems', (SELECT id FROM majors WHERE name = 'Electrical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Microprocessors', (SELECT id FROM majors WHERE name = 'Electrical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Embedded Systems', (SELECT id FROM majors WHERE name = 'Electrical Engineering' AND level = 'bachelor'), NULL, 3, 'bachelor'),

-- Courses for Mathematics
('Calculus I', (SELECT id FROM majors WHERE name = 'Mathematics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Calculus II', (SELECT id FROM majors WHERE name = 'Mathematics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Linear Algebra', (SELECT id FROM majors WHERE name = 'Mathematics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Differential Equations', (SELECT id FROM majors WHERE name = 'Mathematics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Abstract Algebra', (SELECT id FROM majors WHERE name = 'Mathematics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Real Analysis', (SELECT id FROM majors WHERE name = 'Mathematics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Complex Analysis', (SELECT id FROM majors WHERE name = 'Mathematics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Numerical Analysis', (SELECT id FROM majors WHERE name = 'Mathematics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Discrete Mathematics', (SELECT id FROM majors WHERE name = 'Mathematics' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Probability and Statistics', (SELECT id FROM majors WHERE name = 'Mathematics' AND level = 'bachelor'), NULL, 3, 'bachelor'),

-- Courses for History
('World History I', (SELECT id FROM majors WHERE name = 'History' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('World History II', (SELECT id FROM majors WHERE name = 'History' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('American History', (SELECT id FROM majors WHERE name = 'History' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('European History', (SELECT id FROM majors WHERE name = 'History' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Asian History', (SELECT id FROM majors WHERE name = 'History' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('African History', (SELECT id FROM majors WHERE name = 'History' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('History of the Middle East', (SELECT id FROM majors WHERE name = 'History' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Latin American History', (SELECT id FROM majors WHERE name = 'History' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Historiography', (SELECT id FROM majors WHERE name = 'History' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Ancient Civilizations', (SELECT id FROM majors WHERE name = 'History' AND level = 'bachelor'), NULL, 3, 'bachelor'),

-- Courses for Psychology
('Introduction to Psychology', (SELECT id FROM majors WHERE name = 'Psychology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Developmental Psychology', (SELECT id FROM majors WHERE name = 'Psychology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Cognitive Psychology', (SELECT id FROM majors WHERE name = 'Psychology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Social Psychology', (SELECT id FROM majors WHERE name = 'Psychology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Abnormal Psychology', (SELECT id FROM majors WHERE name = 'Psychology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Clinical Psychology', (SELECT id FROM majors WHERE name = 'Psychology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Biopsychology', (SELECT id FROM majors WHERE name = 'Psychology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Psychological Statistics', (SELECT id FROM majors WHERE name = 'Psychology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Personality Psychology', (SELECT id FROM majors WHERE name = 'Psychology' AND level = 'bachelor'), NULL, 3, 'bachelor'),
('Industrial-Organizational Psychology', (SELECT id FROM majors WHERE name = 'Psychology' AND level = 'bachelor'), NULL, 3, 'bachelor');

-- Insert default Master's courses with credits
INSERT INTO courses (name, major_id, professor_id, credits, level) VALUES
-- Courses for Data Science
('Machine Learning', (SELECT id FROM majors WHERE name = 'Data Science' AND level = 'master'), NULL, 4, 'master'),
('Data Mining', (SELECT id FROM majors WHERE name = 'Data Science' AND level = 'master'), NULL, 4, 'master'),
('Big Data Analytics', (SELECT id FROM majors WHERE name = 'Data Science' AND level = 'master'), NULL, 4, 'master'),
('Statistical Learning', (SELECT id FROM majors WHERE name = 'Data Science' AND level = 'master'), NULL, 4, 'master'),
('Data Visualization', (SELECT id FROM majors WHERE name = 'Data Science' AND level = 'master'), NULL, 4, 'master'),
('Natural Language Processing', (SELECT id FROM majors WHERE name = 'Data Science' AND level = 'master'), NULL, 4, 'master'),
('Deep Learning', (SELECT id FROM majors WHERE name = 'Data Science' AND level = 'master'), NULL, 4, 'master'),
('Data Ethics', (SELECT id FROM majors WHERE name = 'Data Science' AND level = 'master'), NULL, 4, 'master'),
('Time Series Analysis', (SELECT id FROM majors WHERE name = 'Data Science' AND level = 'master'), NULL, 4, 'master'),
('Advanced Data Analytics', (SELECT id FROM majors WHERE name = 'Data Science' AND level = 'master'), NULL, 4, 'master'),

-- Courses for Business Analytics
('Advanced Business Analytics', (SELECT id FROM majors WHERE name = 'Business Analytics' AND level = 'master'), NULL, 4, 'master'),
('Predictive Analytics', (SELECT id FROM majors WHERE name = 'Business Analytics' AND level = 'master'), NULL, 4, 'master'),
('Data-Driven Decision Making', (SELECT id FROM majors WHERE name = 'Business Analytics' AND level = 'master'), NULL, 4, 'master'),
('Marketing Analytics', (SELECT id FROM majors WHERE name = 'Business Analytics' AND level = 'master'), NULL, 4, 'master'),
('Financial Analytics', (SELECT id FROM majors WHERE name = 'Business Analytics' AND level = 'master'), NULL, 4, 'master'),
('Customer Analytics', (SELECT id FROM majors WHERE name = 'Business Analytics' AND level = 'master'), NULL, 4, 'master'),
('Operational Analytics', (SELECT id FROM majors WHERE name = 'Business Analytics' AND level = 'master'), NULL, 4, 'master'),
('Strategic Analytics', (SELECT id FROM majors WHERE name = 'Business Analytics' AND level = 'master'), NULL, 4, 'master'),
('Analytics for Competitive Advantage', (SELECT id FROM majors WHERE name = 'Business Analytics' AND level = 'master'), NULL, 4, 'master'),
('Ethical and Legal Issues in Business Analytics', (SELECT id FROM majors WHERE name = 'Business Analytics' AND level = 'master'), NULL, 4, 'master'),

-- Courses for Mechanical Engineering (Master's)
('Advanced Thermodynamics', (SELECT id FROM majors WHERE name = 'Advanced Mechanical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Advanced Fluid Mechanics', (SELECT id FROM majors WHERE name = 'Advanced Mechanical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Computational Fluid Dynamics', (SELECT id FROM majors WHERE name = 'Advanced Mechanical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Advanced Heat Transfer', (SELECT id FROM majors WHERE name = 'Advanced Mechanical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Finite Element Analysis', (SELECT id FROM majors WHERE name = 'Advanced Mechanical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Robotics and Automation', (SELECT id FROM majors WHERE name = 'Advanced Mechanical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Advanced Materials Science', (SELECT id FROM majors WHERE name = 'Advanced Mechanical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Advanced Control Systems', (SELECT id FROM majors WHERE name = 'Advanced Mechanical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Mechatronics', (SELECT id FROM majors WHERE name = 'Advanced Mechanical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Renewable Energy Systems', (SELECT id FROM majors WHERE name = 'Advanced Mechanical Engineering' AND level = 'master'), NULL, 4, 'master'),

-- Courses for Biotechnology
('Genomics and Proteomics', (SELECT id FROM majors WHERE name = 'Biotechnology' AND level = 'master'), NULL, 4, 'master'),
('Bioinformatics', (SELECT id FROM majors WHERE name = 'Biotechnology' AND level = 'master'), NULL, 4, 'master'),
('Molecular Biotechnology', (SELECT id FROM majors WHERE name = 'Biotechnology' AND level = 'master'), NULL, 4, 'master'),
('Bioprocess Engineering', (SELECT id FROM majors WHERE name = 'Biotechnology' AND level = 'master'), NULL, 4, 'master'),
('Biopharmaceuticals', (SELECT id FROM majors WHERE name = 'Biotechnology' AND level = 'master'), NULL, 4, 'master'),
('Regenerative Medicine', (SELECT id FROM majors WHERE name = 'Biotechnology' AND level = 'master'), NULL, 4, 'master'),
('Plant Biotechnology', (SELECT id FROM majors WHERE name = 'Biotechnology' AND level = 'master'), NULL, 4, 'master'),
('Biotechnology and Society', (SELECT id FROM majors WHERE name = 'Biotechnology' AND level = 'master'), NULL, 4, 'master'),
('Environmental Biotechnology', (SELECT id FROM majors WHERE name = 'Biotechnology' AND level = 'master'), NULL, 4, 'master'),
('Microbial Biotechnology', (SELECT id FROM majors WHERE name = 'Biotechnology' AND level = 'master'), NULL, 4, 'master'),

-- Courses for Astrophysics
('Advanced Astrophysics', (SELECT id FROM majors WHERE name = 'Astrophysics' AND level = 'master'), NULL, 4, 'master'),
('Cosmology', (SELECT id FROM majors WHERE name = 'Astrophysics' AND level = 'master'), NULL, 4, 'master'),
('Stellar Structure and Evolution', (SELECT id FROM majors WHERE name = 'Astrophysics' AND level = 'master'), NULL, 4, 'master'),
('Galactic Dynamics', (SELECT id FROM majors WHERE name = 'Astrophysics' AND level = 'master'), NULL, 4, 'master'),
('High-Energy Astrophysics', (SELECT id FROM majors WHERE name = 'Astrophysics' AND level = 'master'), NULL, 4, 'master'),
('Planetary Science', (SELECT id FROM majors WHERE name = 'Astrophysics' AND level = 'master'), NULL, 4, 'master'),
('Observational Techniques', (SELECT id FROM majors WHERE name = 'Astrophysics' AND level = 'master'), NULL, 4, 'master'),
('Interstellar Medium', (SELECT id FROM majors WHERE name = 'Astrophysics' AND level = 'master'), NULL, 4, 'master'),
('Relativistic Astrophysics', (SELECT id FROM majors WHERE name = 'Astrophysics' AND level = 'master'), NULL, 4, 'master'),
('Exoplanets', (SELECT id FROM majors WHERE name = 'Astrophysics' AND level = 'master'), NULL, 4, 'master'),

-- Courses for Organic Chemistry
('Advanced Organic Synthesis', (SELECT id FROM majors WHERE name = 'Organic Chemistry' AND level = 'master'), NULL, 4, 'master'),
('Physical Organic Chemistry', (SELECT id FROM majors WHERE name = 'Organic Chemistry' AND level = 'master'), NULL, 4, 'master'),
('Organic Reaction Mechanisms', (SELECT id FROM majors WHERE name = 'Organic Chemistry' AND level = 'master'), NULL, 4, 'master'),
('Organometallic Chemistry', (SELECT id FROM majors WHERE name = 'Organic Chemistry' AND level = 'master'), NULL, 4, 'master'),
('Stereochemistry', (SELECT id FROM majors WHERE name = 'Organic Chemistry' AND level = 'master'), NULL, 4, 'master'),
('Natural Product Chemistry', (SELECT id FROM majors WHERE name = 'Organic Chemistry' AND level = 'master'), NULL, 4, 'master'),
('Supramolecular Chemistry', (SELECT id FROM majors WHERE name = 'Organic Chemistry' AND level = 'master'), NULL, 4, 'master'),
('Heterocyclic Chemistry', (SELECT id FROM majors WHERE name = 'Organic Chemistry' AND level = 'master'), NULL, 4, 'master'),
('Bioorganic Chemistry', (SELECT id FROM majors WHERE name = 'Organic Chemistry' AND level = 'master'), NULL, 4, 'master'),
('Computational Organic Chemistry', (SELECT id FROM majors WHERE name = 'Organic Chemistry' AND level = 'master'), NULL, 4, 'master'),

-- Courses for Electrical Engineering (Master's)
('Advanced Circuit Design', (SELECT id FROM majors WHERE name = 'Advanced Electrical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Advanced Digital Signal Processing', (SELECT id FROM majors WHERE name = 'Advanced Electrical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Power Electronics', (SELECT id FROM majors WHERE name = 'Advanced Electrical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Advanced Communication Systems', (SELECT id FROM majors WHERE name = 'Advanced Electrical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Embedded Systems Design', (SELECT id FROM majors WHERE name = 'Advanced Electrical Engineering' AND level = 'master'), NULL, 4, 'master'),
('RF Circuit Design', (SELECT id FROM majors WHERE name = 'Advanced Electrical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Photonic Systems', (SELECT id FROM majors WHERE name = 'Advanced Electrical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Advanced Control Engineering', (SELECT id FROM majors WHERE name = 'Advanced Electrical Engineering' AND level = 'master'), NULL, 4, 'master'),
('Smart Grid Technologies', (SELECT id FROM majors WHERE name = 'Advanced Electrical Engineering' AND level = 'master'), NULL, 4, 'master'),
('VLSI Design', (SELECT id FROM majors WHERE name = 'Advanced Electrical Engineering' AND level = 'master'), NULL, 4, 'master'),

-- Courses for Applied Mathematics
('Advanced Real Analysis', (SELECT id FROM majors WHERE name = 'Applied Mathematics' AND level = 'master'), NULL, 4, 'master'),
('Partial Differential Equations', (SELECT id FROM majors WHERE name = 'Applied Mathematics' AND level = 'master'), NULL, 4, 'master'),
('Stochastic Processes', (SELECT id FROM majors WHERE name = 'Applied Mathematics' AND level = 'master'), NULL, 4, 'master'),
('Advanced Numerical Analysis', (SELECT id FROM majors WHERE name = 'Applied Mathematics' AND level = 'master'), NULL, 4, 'master'),
('Mathematical Modelling', (SELECT id FROM majors WHERE name = 'Applied Mathematics' AND level = 'master'), NULL, 4, 'master'),
('Optimization Theory', (SELECT id FROM majors WHERE name = 'Applied Mathematics' AND level = 'master'), NULL, 4, 'master'),
('Dynamical Systems', (SELECT id FROM majors WHERE name = 'Applied Mathematics' AND level = 'master'), NULL, 4, 'master'),
('Graph Theory', (SELECT id FROM majors WHERE name = 'Applied Mathematics' AND level = 'master'), NULL, 4, 'master'),
('Applied Functional Analysis', (SELECT id FROM majors WHERE name = 'Applied Mathematics' AND level = 'master'), NULL, 4, 'master'),
('Advanced Probability', (SELECT id FROM majors WHERE name = 'Applied Mathematics' AND level = 'master'), NULL, 4, 'master'),

-- Courses for Historical Studies
('Historical Research Methods', (SELECT id FROM majors WHERE name = 'Historical Studies' AND level = 'master'), NULL, 4, 'master'),
('Advanced Historiography', (SELECT id FROM majors WHERE name = 'Historical Studies' AND level = 'master'), NULL, 4, 'master'),
('Medieval History', (SELECT id FROM majors WHERE name = 'Historical Studies' AND level = 'master'), NULL, 4, 'master'),
('Modern European History', (SELECT id FROM majors WHERE name = 'Historical Studies' AND level = 'master'), NULL, 4, 'master'),
('American History since 1900', (SELECT id FROM majors WHERE name = 'Historical Studies' AND level = 'master'), NULL, 4, 'master'),
('History of Colonialism', (SELECT id FROM majors WHERE name = 'Historical Studies' AND level = 'master'), NULL, 4, 'master'),
('Cultural History', (SELECT id FROM majors WHERE name = 'Historical Studies' AND level = 'master'), NULL, 4, 'master'),
('Economic History', (SELECT id FROM majors WHERE name = 'Historical Studies' AND level = 'master'), NULL, 4, 'master'),
('History of Science and Technology', (SELECT id FROM majors WHERE name = 'Historical Studies' AND level = 'master'), NULL, 4, 'master'),
('World History Seminar', (SELECT id FROM majors WHERE name = 'Historical Studies' AND level = 'master'), NULL, 4, 'master'),

-- Courses for Clinical Psychology
('Advanced Clinical Psychology', (SELECT id FROM majors WHERE name = 'Clinical Psychology' AND level = 'master'), NULL, 4, 'master'),
('Psychopathology', (SELECT id FROM majors WHERE name = 'Clinical Psychology' AND level = 'master'), NULL, 4, 'master'),
('Clinical Neuropsychology', (SELECT id FROM majors WHERE name = 'Clinical Psychology' AND level = 'master'), NULL, 4, 'master'),
('Cognitive Behavioral Therapy', (SELECT id FROM majors WHERE name = 'Clinical Psychology' AND level = 'master'), NULL, 4, 'master'),
('Psychological Assessment', (SELECT id FROM majors WHERE name = 'Clinical Psychology' AND level = 'master'), NULL, 4, 'master'),
('Health Psychology', (SELECT id FROM majors WHERE name = 'Clinical Psychology' AND level = 'master'), NULL, 4, 'master'),
('Family Therapy', (SELECT id FROM majors WHERE name = 'Clinical Psychology' AND level = 'master'), NULL, 4, 'master'),
('Trauma and Crisis Intervention', (SELECT id FROM majors WHERE name = 'Clinical Psychology' AND level = 'master'), NULL, 4, 'master'),
('Addiction Studies', (SELECT id FROM majors WHERE name = 'Clinical Psychology' AND level = 'master'), NULL, 4, 'master'),
('Clinical Research Methods', (SELECT id FROM majors WHERE name = 'Clinical Psychology' AND level = 'master'), NULL, 4, 'master');
