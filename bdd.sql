
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
);




CREATE TABLE Radio(
        idRadio       Int  Auto_increment  NOT NULL ,
        nomRadio      Varchar (30) NOT NULL ,
        categorie     Varchar (30) NOT NULL ,
        enLigne       tinyint NOT NULL ,
        photoRadio    Varchar(255) NOT NULL ,
        description   Text NOT NULL ,
        url           Varchar(255) NOT NULL ,
	CONSTRAINT Radio_PK PRIMARY KEY (idRadio)
);




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
);




CREATE TABLE Role(
        idRole  Int  Auto_increment  NOT NULL ,
        nomRole Varchar (50) NOT NULL
	,CONSTRAINT Role_PK PRIMARY KEY (idRole)
);



CREATE TABLE TypeObjet(
        idType  Int  Auto_increment  NOT NULL ,
        nomType Varchar (50) NOT NULL
	,CONSTRAINT TypeObjet_PK PRIMARY KEY (idType)
);




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
);



CREATE TABLE Ecoute(
        idUtilisateur  Int NOT NULL ,
        idRadio        Int NOT NULL ,
        derniereEcoute Datetime NOT NULL ,
        dureeEcoute    Int NOT NULL
	,CONSTRAINT Ecoute_PK PRIMARY KEY (idUtilisateur,idRadio)

	,CONSTRAINT Ecoute_Utilisateur_FK FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(idUtilisateur)
	,CONSTRAINT Ecoute_Radio0_FK FOREIGN KEY (idRadio) REFERENCES Radio(idRadio)
);




CREATE TABLE Achete(
        idUtilisateur Int NOT NULL ,
        idObjet       Int NOT NULL
	,CONSTRAINT Achete_PK PRIMARY KEY (idUtilisateur,idObjet)

	,CONSTRAINT Achete_Utilisateur_FK FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(idUtilisateur)
	,CONSTRAINT Achete_Objet0_FK FOREIGN KEY (idObjet) REFERENCES Objet(idObjet)
);



CREATE TABLE est_un(
        idUtilisateur Int NOT NULL ,
        idRole        Int NOT NULL
	,CONSTRAINT est_un_PK PRIMARY KEY (idUtilisateur,idRole)

	,CONSTRAINT est_un_Utilisateur_FK FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(idUtilisateur)
	,CONSTRAINT est_un_Role0_FK FOREIGN KEY (idRole) REFERENCES Role(idRole)
);




CREATE TABLE favoris(
        idRadio       Int NOT NULL ,
        idUtilisateur Int NOT NULL
	,CONSTRAINT favoris_PK PRIMARY KEY (idRadio,idUtilisateur)

	,CONSTRAINT favoris_Radio_FK FOREIGN KEY (idRadio) REFERENCES Radio(idRadio)
	,CONSTRAINT favoris_Utilisateur0_FK FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(idUtilisateur)
);




CREATE TABLE Mood(
        idMood       Int Auto_increment NOT NULL ,
        idRadio      Int NOT NULL,
        nomMood      Varchar NOT NULL,
        couleur      Varchar NOT NULL

	,CONSTRAINT Mood_PK PRIMARY KEY (idMood)

	,CONSTRAINT Mood_Radio_FK FOREIGN KEY (idRadio) REFERENCES Radio(idRadio)
   ,CONSTRAINT Mood_Utilisateur_FK FOREIGN KEY (idMood) REFERENCES Utilisateur(humeur)
);
