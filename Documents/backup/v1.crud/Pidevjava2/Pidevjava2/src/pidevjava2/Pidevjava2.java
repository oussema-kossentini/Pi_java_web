/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package pidevjava2;
import entities.Reclamation;
import entities.Avis;
import java.sql.Date;
import java.sql.SQLException;
import java.util.Scanner;
import java.util.logging.Level;
import java.util.logging.Logger;
import service.ReclamationService;
import service.AvisService;
/**
 *
 * @author ousse
 */
public class Pidevjava2 {
    /**
     * @param args the command lineou arguments
     */
    @SuppressWarnings("empty-statement")
    public static void main(String[] args) {
        // TODO code application logic here
        Date dateR = new Date(21/02/2020);
      Reclamation r = new Reclamation("Technique06","test15",dateR);
     //  Reclamation r1 = new Reclamation("Livraison","test2",dateR);
       //Avis a1 = new Avis("oussema","kossentini","testgood",dateR);
        //Reclamation p2 = new Reclamation("mahjoubi","oussema");
        //Reclamation p3 = new Reclamation("badreddine","ala");
        ReclamationService sp = new ReclamationService();
          AvisService sa = new AvisService(); 
        try {
           // sp.modifierp(new Reclamation("Technique","test1",dateR));
            //sp.modifierp(new Reclamation("Livraison","test2",dateR));
           // sa.modifierp(new Avis("oussema78","kossentini78","testgood78",dateR));
            System.out.println("modification avec succes");
           sp.ajouterp(r);
           //sa.ajouteA(a1);
           //sp.ajouterp(r2);
           //sp.ajouterp(p3);
           //System.out.println("ajout avec succes");
           //System.out.println(sp.afficherReclamation());
           
           //System.out.println("voulez vous supprimer quelqu'un ?");
           //Scanner sc= new Scanner(System.in);
            //String a=sc.nextLine();
            //String s1="oui";
            //if(a.equals(s1)){
              //  System.out.println("donner son id : ");
           //sp.deletep(r);
            //System.out.println("suppression avec succes");}
           // sp.modifierUser();
            sa.modifierUserAvis();
            
           
            
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
        
        try {
           // System.out.println(sp.afficherReclamation());
             System.out.println(sa.afficherAvis());
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
        
    }
}


