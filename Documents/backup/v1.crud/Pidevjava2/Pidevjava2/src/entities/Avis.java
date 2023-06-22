/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package entities;
//import java.sql.Date;

import java.sql.Date;
/**
 *
 * @author ousse
 */

public class Avis {
    
    private int id_avis;
    private String nom;
    private String prenom;
   
    private String description;
    private Date date ;

    public Avis(String nom,String prenom ,String description, Date date) {
        this.nom = nom;
        this.prenom = prenom;
        this.description = description;
        this.date = date;
        
    }
    public Avis(int id_avis,String nom,String prenom, String description, Date date) {
        this.id_avis=id_avis;
        this.nom = nom;
        this.prenom = prenom;
        this.description = description;
        this.date = date;
    }
public String getPrenom() {
        return prenom;
    }

    public void setPrenom(String prenom) {
        this.prenom = prenom;
    }

    
    
    public int getId() {
        return id_avis;
    }

    public void setId(int id_avis) {
        this.id_avis = id_avis;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }
    
    public Date getDate() {
        return date;
    }

    public void setDate(Date date) {
        this.date = date;
    }

    @Override
    public String toString() {
        return "avis{" + "id_avis=" + id_avis + ", nom=" + nom + ",prenom=" + prenom + ", description=" + description + ", date=" + date + '}';
    }
    
    
    
    
}
