DROP TABLE IF EXISTS hospitals;

CREATE TABLE hospitals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    latitude DOUBLE,
    longitude DOUBLE
);
