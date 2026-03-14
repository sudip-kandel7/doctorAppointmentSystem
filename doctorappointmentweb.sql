
CREATE TABLE admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'Admin'
);


CREATE TABLE patient (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
);


CREATE TABLE doctor (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  specialization VARCHAR(100) NOT NULL,
  profile_pic VARCHAR(255),
  experience INT,
  available_from TIME,
  available_to TIME,
  available_days VARCHAR(100)
);


CREATE TABLE appointment (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  doctor_id INT NOT NULL,
  appointment_date DATE NOT NULL,
  appointment_time TIME NOT NULL,
  description TEXT,
  status VARCHAR(50) DEFAULT 'Pending',

  FOREIGN KEY (patient_id) REFERENCES patient(id) ON DELETE CASCADE,
  FOREIGN KEY (doctor_id) REFERENCES doctor(id) ON DELETE CASCADE
);


CREATE TABLE notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  appointment_id INT NOT NULL,
  user_type ENUM('patient','doctor') NOT NULL,
  message VARCHAR(255) NOT NULL,
  seen TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (appointment_id)
  REFERENCES appointment(id)
  ON DELETE CASCADE
);