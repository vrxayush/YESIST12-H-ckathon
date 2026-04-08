DROP TABLE IF EXISTS hospitals;

CREATE TABLE hospitals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    latitude DOUBLE,
    longitude DOUBLE
);

INSERT INTO hospitals (name, latitude, longitude) VALUES
('BM Shah Hospital', 21.2097, 81.3601),
('Hi-Tech Hospital Bhilai', 21.216022, 81.322815),
('Sparsh Hospital', 21.20559, 81.35913),
('Vayam Hospital', 21.20429, 81.35913),
('SBS Hospital', 21.2062, 81.3644),
('Mittal Hospital', 21.2164, 81.3226),
('Sector 9 Hospital', 21.1888, 81.3151),

('Aarogya Hospital Durg', 21.2052, 81.3094),
('Jawahar Lal Nehru Hospital', 21.1558, 81.3564),
('Chandulal', 21.2003, 81.3226),
('Gangotri Hospital', 21.1892, 81.2821),
('District Hospital Durg', 21.1900, 81.2840),
('Maa Chandika Hospital', 21.1875, 81.2790),
('Vardhman Hospital', 21.1915, 81.2860),
('RR Hospital', 21.1925, 81.2875);
