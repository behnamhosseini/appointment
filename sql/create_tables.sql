CREATE DATABASE IF NOT EXISTS appointment_scheduler;

USE appointment_scheduler;

CREATE TABLE IF NOT EXISTS appointments (
                                            id INT AUTO_INCREMENT PRIMARY KEY,
                                            user_id INT NOT NULL,
                                            date DATE NOT NULL,
                                            start_time CHAR(5) NOT NULL,
                                            end_time CHAR(5) NOT NULL,
                                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_date_start_time ON appointments (date, start_time);
CREATE INDEX idx_user_id ON appointments (user_id);
