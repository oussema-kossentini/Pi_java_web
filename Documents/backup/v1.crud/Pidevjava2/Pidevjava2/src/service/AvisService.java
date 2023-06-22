
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package service;

//import com.sun.istack.internal.logging.Logger;
import entities.Avis;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;
//import java.util.logging.Level;

import utils.MyDB;

/**
 *
 * @author ousse
 */
public class AvisService {
    Connection connexion;
    Statement stm;

    public AvisService() {
        connexion = MyDB.getInstance().getConnexion();
    }

    public void ajouteA(Avis r) throws SQLException {
        String req = "INSERT INTO `avis` (`nom`, `prenom`,`Description`, `date`) VALUES ( '"
                + r.getNom() + "', '" +r.getPrenom() + "', '" + r.getDescription() + "', '" + r.getDate() + "') ";
        stm = connexion.createStatement();
        stm.executeUpdate(req);

    }
    public void modifierp(Avis r) throws SQLException {
        String req = "UPDATE `avis` SET nom = ? ,prenom = ?, Description = ? , Date = ? where id_avis = ? ";
        //stm = connexion.createStatement();
        //stm.executeUpdate(req);
        PreparedStatement k=connexion.prepareStatement(req);
k.setString(1, r.getNom() );
k.setString(2, r.getPrenom() );
k.setString(3, r.getDescription() );
k.setDate(4, r.getDate() );
k.setInt(5, r.getId() );
k.executeUpdate();
    }
    public void modifierUserAvis() throws SQLException {
        System.out.println("choisir l id de le user que vous voulez la modifier");
        Scanner sc= new Scanner(System.in);
        int id_avis = sc.nextInt();
        sc.nextLine();
        System.out.println("tapez le numero du champ a modifier \n 1 nom \n 2 : prenom \n 2 : description  ");
        int num_champ = sc.nextInt();
        sc.nextLine();
        switch (num_champ) {
    case 1:{
       
            System.out.println("saisir votre nouveau nom");
        String nv_nom = sc.nextLine();
        String req = "UPDATE avis SET nom = ? WHERE id_avis = ?";
         PreparedStatement ps = connexion.prepareStatement(req);
         ps.setString(1, nv_nom);
         ps.setInt(2,id_avis );
        int a= ps.executeUpdate();
        break;}
    case 2:{      
           System.out.println("saisir votre nouveau prenom");
        String nv_prenom = sc.nextLine();
        String req = "UPDATE avis SET prenom = ? WHERE id_avis = ?";
         PreparedStatement ps = connexion.prepareStatement(req);
         ps.setString(1, nv_prenom);
         ps.setInt(2,id_avis );
        int a= ps.executeUpdate();
        break;}
        
    case 3:{      
           System.out.println("saisir votre nouveau description");
        String nv_description = sc.nextLine();
        String req = "UPDATE avis SET description = ? WHERE id_avis = ?";
         PreparedStatement ps = connexion.prepareStatement(req);
         ps.setString(1, nv_description);
         ps.setInt(2,id_avis );
        int a= ps.executeUpdate();
        break;}
        }
        }
     
    public void deletep(Avis r) throws SQLException {
  Scanner sc= new Scanner(System.in);
  int id_avis=sc.nextInt();
    String query="DELETE FROM `avis` WHERE `id_avis` =  "+id_avis;
    stm = connexion.createStatement();
    stm.executeUpdate(query);
    }
    public void ajouterProduitPs(Avis r) throws SQLException {
        String req = "INSERT INTO `avis` (`nom`,`prenom`,`Description`,`date`) "
         + "VALUES ( ?,?,?,?) ";
        PreparedStatement ps = connexion.prepareStatement(req);
        ps.setString(1, r.getNom());
        ps.setString(2, r.getPrenom());
        ps.setString(3, r.getDescription());
        ps.setDate(4, r.getDate());
        ps.executeUpdate();
    }

    public List<Avis> afficherAvis() throws SQLException {
        List<Avis> aviss = new ArrayList<>();
        String req = "select * from avis";
        stm = connexion.createStatement();
        //ensemble de resultat
        ResultSet rst = stm.executeQuery(req);

        while (rst.next()) {
            Avis r = new Avis (rst.getInt("id_avis"),//or rst.getInt(1)
                    rst.getString("nom"),
                    rst.getString("prenom"),
                    rst.getString("Description"),
                    rst.getDate("date"));
            aviss.add(r);
        }
        return aviss;
    }

    //private void getDate(String date) {
      //  throw new UnsupportedOperationException("Not supported yet."); // Generated from nbfs://nbhost/SystemFileSystem/Templates/Classes/Code/GeneratedMethodBody
    //}

   
}
