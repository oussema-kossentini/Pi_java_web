/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package services;

import com.sun.istack.internal.logging.Logger;
import entities.Personne;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;
import java.util.logging.Level;

import utils.MyDB;

/**
 *
 * @author macbook
 */
public class PersonneService implements IPersonne<Personne> {

    Connection connexion;
    Statement stm;

    public PersonneService() {
        connexion = MyDB.getInstance().getConnexion();
    }

    @Override
    public void ajouterp(Personne p) throws SQLException {
        String req = "INSERT INTO `personne` (`nom`, `prenom`) VALUES ( '"
                + p.getNom() + "', '" + p.getPrenom() + "') ";
        stm = connexion.createStatement();
        stm.executeUpdate(req);

    }
    public void modifierp(Personne p) throws SQLException {
        String req = "UPDATE `personne` SET nom = ? , prenom = ? where id = ? ";
        //stm = connexion.createStatement();
        //stm.executeUpdate(req);
        PreparedStatement k=connexion.prepareStatement(req);
k.setString(1, p.getNom() );
k.setString(2, p.getPrenom() );
k.setInt(3, p.getId() );
k.executeUpdate();
    }
    public void modifierUser() throws SQLException {
        System.out.println("choisir l id de le user que vous voulez la modifier");
        Scanner sc= new Scanner(System.in);
        int id = sc.nextInt();
        sc.nextLine();
        System.out.println("tapez le numero du champ a modifier \n 1 : nom \n 2 : prenom  ");
        int num_champ = sc.nextInt();
        sc.nextLine();
        switch (num_champ) {
    case 1:{
       
            System.out.println("saisir votre nouveau nom");
        String nv_nom = sc.nextLine();
        String req = "UPDATE personne SET nom = ? WHERE id = ?";
         PreparedStatement ps = connexion.prepareStatement(req);
         ps.setString(1, nv_nom);
         ps.setInt(2,id );
        int a= ps.executeUpdate();
        break;}
    case 2:{      
           System.out.println("saisir votre nouveau prenom");
        String nv_prenom = sc.nextLine();
        String req = "UPDATE personne SET prenom = ? WHERE id = ?";
         PreparedStatement ps = connexion.prepareStatement(req);
         ps.setString(1, nv_prenom);
         ps.setInt(2,id );
        int a= ps.executeUpdate();
        break;}
        }
    }
    public void deletep(Personne p) throws SQLException {
  Scanner sc= new Scanner(System.in);
  int id=sc.nextInt();
    String query="DELETE FROM `personne` WHERE `id` =  "+id;
    stm = connexion.createStatement();
    stm.executeUpdate(query);
    }
    public void ajouterProduitPs(Personne p) throws SQLException {
        String req = "INSERT INTO `personne` (`nom`, `prenom`) "
                + "VALUES ( ?, ?) ";
        PreparedStatement ps = connexion.prepareStatement(req);
        ps.setString(1, p.getNom());
        ps.setString(2, p.getPrenom());
        ps.executeUpdate();
    }

    @Override
    public List<Personne> afficherpersonne() throws SQLException {
        List<Personne> presonnes = new ArrayList<>();
        String req = "select * from personne";
        stm = connexion.createStatement();
        //ensemble de resultat
        ResultSet rst = stm.executeQuery(req);

        while (rst.next()) {
            Personne p = new Personne(rst.getInt("id"),//or rst.getInt(1)
                    rst.getString("nom"),
                    rst.getString("prenom"));
            presonnes.add(p);
        }
        return presonnes;
    }

    public Object afficheListeP() {
        throw new UnsupportedOperationException("Not supported yet."); // Generated from nbfs://nbhost/SystemFileSystem/Templates/Classes/Code/GeneratedMethodBody
    }

   
}
