-- Cleanup
DROP TABLE IF EXISTS Help;
DROP TABLE IF EXISTS Report;
DROP TABLE IF EXISTS HelpRequests;
DROP TABLE IF EXISTS Volunteer;
DROP TABLE IF EXISTS InNeed;
DROP TABLE IF EXISTS defUser;
DROP TABLE IF EXISTS Admin;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Address;

-- creation
CREATE TABLE Address (
                         idAdr INT AUTO_INCREMENT PRIMARY KEY,
                         city VARCHAR(100) NOT NULL,
                         postalCode VARCHAR(20) NOT NULL,
                         street VARCHAR(255) NOT NULL,
                         homeN VARCHAR(10) NOT NULL
);

CREATE TABLE User (
                      user_id INT AUTO_INCREMENT PRIMARY KEY,
                      email VARCHAR(150) UNIQUE NOT NULL,
                      photo VARCHAR(255),
                      nickname VARCHAR(50),
                      pwdHash VARCHAR(255) NOT NULL,
                      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE Admin (
                       user_id INT PRIMARY KEY,
                       FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE
);

CREATE TABLE defUser (
                         user_id INT PRIMARY KEY,
                         fName VARCHAR(100) NOT NULL,
                         sName VARCHAR(100) NOT NULL,
                         tel VARCHAR(14),
                         idAdr INT NOT NULL,
                         FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
                         FOREIGN KEY (idAdr) REFERENCES Address(idAdr)
);

CREATE TABLE InNeed (
                        user_id INT PRIMARY KEY,
                        FOREIGN KEY (user_id) REFERENCES defUser(user_id) ON DELETE CASCADE
);

CREATE TABLE Volunteer (
                           user_id INT PRIMARY KEY,
                           FOREIGN KEY (user_id) REFERENCES defUser(user_id) ON DELETE CASCADE
);

CREATE TABLE HelpRequests (
                              idR INT AUTO_INCREMENT PRIMARY KEY,
                              hType VARCHAR(50),
                              content TEXT,
                              status VARCHAR(20) DEFAULT 'open',
                              idAdr INT NOT NULL,
                              inNeed_id INT NOT NULL,
                              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                              FOREIGN KEY (idAdr) REFERENCES Address(idAdr),
                              FOREIGN KEY (inNeed_id) REFERENCES InNeed(user_id)
);

CREATE TABLE Help (
                      volunteer_id INT,
                      idR INT,
                      firstMessage TEXT,
                      assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      PRIMARY KEY (volunteer_id, idR),
                      FOREIGN KEY (volunteer_id) REFERENCES Volunteer(user_id),
                      FOREIGN KEY (idR) REFERENCES HelpRequests(idR)
);

CREATE TABLE Report (
                        idRep INT AUTO_INCREMENT PRIMARY KEY,
                        status VARCHAR(20),
                        idR INT NOT NULL,
                        creator_id INT NOT NULL,
                        admin_id INT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        FOREIGN KEY (idR) REFERENCES HelpRequests(idR),
                        FOREIGN KEY (creator_id) REFERENCES defUser(user_id),
                        FOREIGN KEY (admin_id) REFERENCES Admin(user_id)
);