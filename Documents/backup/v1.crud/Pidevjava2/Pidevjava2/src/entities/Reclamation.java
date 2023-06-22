/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package entities;

import java.sql.Date;

/**
 *
 * @author macbook
 */
public class Reclamation {
    
    private int idR;
    private String typeREC;
    private String description;
    private Date date ;

    public Reclamation(String typeREC, String description, Date date) {
        this.typeREC = typeREC;
        this.description = description;
        this.date = date;
    }
    public Reclamation(int idR,String typeREC, String description, Date date) {
        this.idR=idR;
        this.typeREC = typeREC;
        this.description = description;
        this.date = date;
    }

    public int getId() {
        return idR;
    }

    public void setId(int idR) {
        this.idR = idR;
    }

    public String getType() {
        return typeREC;
    }

    public void setType(String typeREC) {
        this.typeREC = typeREC;
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
        return "reclamation{" + "idR=" + idR + ", typeREC=" + typeREC + ", description=" + description + ", date=" + date + '}';
    }
    
    
    
    
}
