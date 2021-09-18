
/*DROP TABLE IF EXISTS Achete ;
DROP TABLE IF EXISTS Commentaire ;
DROP TABLE IF EXISTS Ecoute ;
DROP TABLE IF EXISTS est_un ;
DROP TABLE IF EXISTS Objet ;
DROP TABLE IF EXISTS Radio ;
DROP TABLE IF EXISTS TypeObjet;
DROP TABLE IF EXISTS Utilisateur;
DROP TABLE IF EXISTS Role ;
DROP TABLE IF EXISTS Mood;*/


CREATE TABLE Utilisateur(
        idUtilisateur Int  Auto_increment  NOT NULL ,
        pseudo        Varchar (50) NOT NULL ,
        mdpUti        Char (50) NOT NULL ,
        mail          Varchar (50) NOT NULL ,
        sexe          Varchar (30) NOT NULL ,
        niveauUti     Int DEFAULT (1) NOT NULL ,
        experience    Int DEFAULT (0) NOT NULL ,
        humeur        Int (50)  ,
        moodPoints    Int NOT NULL ,
        photoProfil   Varchar(255),
        banniere      Varchar(255)
	,CONSTRAINT Utilisateur_PK PRIMARY KEY (idUtilisateur)
)ENGINE=INNODB;




CREATE TABLE Radio(
        idRadio       Int  Auto_increment  NOT NULL ,
        nomRadio      Varchar (30) NOT NULL ,
        categorie     Varchar (30) NOT NULL ,
        enLigne       tinyint NOT NULL ,
        photoRadio    Varchar(255) NOT NULL ,
        description   Text NOT NULL ,
        url           Varchar(255) NOT NULL ,
	CONSTRAINT Radio_PK PRIMARY KEY (idRadio)
)ENGINE=InnoDB;




CREATE TABLE Commentaire(
        idCommentaire Int  Auto_increment  NOT NULL ,
        nbSignalement Int NOT NULL ,
        contenu       Text NOT NULL ,
        date          Time NOT NULL ,
        idRadio       Int NOT NULL ,
        idUtilisateur Int NOT NULL
	,CONSTRAINT Commentaire_PK PRIMARY KEY (idCommentaire)

	,CONSTRAINT Commentaire_Radio_FK FOREIGN KEY (idRadio) REFERENCES Radio(idRadio)
	,CONSTRAINT Commentaire_Utilisateur0_FK FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(idUtilisateur)
)ENGINE=InnoDB;




CREATE TABLE Role(
        idRole  Int  Auto_increment  NOT NULL ,
        nomRole Varchar (50) NOT NULL
	,CONSTRAINT Role_PK PRIMARY KEY (idRole)
)ENGINE=InnoDB;



CREATE TABLE TypeObjet(
        idType  Int  Auto_increment  NOT NULL ,
        nomType Varchar (50) NOT NULL
	,CONSTRAINT TypeObjet_PK PRIMARY KEY (idType)
)ENGINE=InnoDB;




CREATE TABLE Objet(
        idObjet       Int  Auto_increment  NOT NULL ,
        nomObjet      Varchar (30) NOT NULL ,
        prixObjet     Int NOT NULL ,
        photoObjet    Mediumblob NOT NULL ,
        disponibilite tinyint NOT NULL ,
        niveauObj     Int NOT NULL ,
        idType        Int NOT NULL
	,CONSTRAINT Objet_PK PRIMARY KEY (idObjet)

	,CONSTRAINT Objet_TypeObjet_FK FOREIGN KEY (idType) REFERENCES TypeObjet(idType)
)ENGINE=InnoDB;



CREATE TABLE Ecoute(
        idUtilisateur  Int NOT NULL ,
        idRadio        Int NOT NULL ,
        derniereEcoute Datetime NOT NULL ,
        dureeEcoute    Int NOT NULL
	,CONSTRAINT Ecoute_PK PRIMARY KEY (idUtilisateur,idRadio)

	,CONSTRAINT Ecoute_Utilisateur_FK FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(idUtilisateur)
	,CONSTRAINT Ecoute_Radio0_FK FOREIGN KEY (idRadio) REFERENCES Radio(idRadio)
)ENGINE=InnoDB;




CREATE TABLE Achete(
        idUtilisateur Int NOT NULL ,
        idObjet       Int NOT NULL
	,CONSTRAINT Achete_PK PRIMARY KEY (idUtilisateur,idObjet)

	,CONSTRAINT Achete_Utilisateur_FK FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(idUtilisateur)
	,CONSTRAINT Achete_Objet0_FK FOREIGN KEY (idObjet) REFERENCES Objet(idObjet)
)ENGINE=InnoDB;



CREATE TABLE est_un(
        idUtilisateur Int NOT NULL ,
        idRole        Int NOT NULL
	,CONSTRAINT est_un_PK PRIMARY KEY (idUtilisateur,idRole)

	,CONSTRAINT est_un_Utilisateur_FK FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(idUtilisateur)
	,CONSTRAINT est_un_Role0_FK FOREIGN KEY (idRole) REFERENCES Role(idRole)
)ENGINE=InnoDB;




CREATE TABLE favoris(
        idRadio       Int NOT NULL ,
        idUtilisateur Int NOT NULL
	,CONSTRAINT favoris_PK PRIMARY KEY (idRadio,idUtilisateur)

	,CONSTRAINT favoris_Radio_FK FOREIGN KEY (idRadio) REFERENCES Radio(idRadio)
	,CONSTRAINT favoris_Utilisateur0_FK FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(idUtilisateur)
)ENGINE=InnoDB;




CREATE TABLE Mood(
        idMood       Int Auto_increment NOT NULL ,
        idRadio      Int NOT NULL,
        nomMood      Varchar NOT NULL,
        couleur      Varchar NOT NULL

	,CONSTRAINT Mood_PK PRIMARY KEY (idMood)

	,CONSTRAINT Mood_Radio_FK FOREIGN KEY (idRadio) REFERENCES Radio(idRadio)
   ,CONSTRAINT Mood_Utilisateur_FK FOREIGN KEY (idMood) REFERENCES Utilisateur(humeur)
)ENGINE=InnoDB;
