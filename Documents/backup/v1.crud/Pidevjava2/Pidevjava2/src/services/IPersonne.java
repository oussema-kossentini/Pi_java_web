/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package services;

import java.sql.SQLException;
import java.util.List;

/**
 *
 * @author macbook
 */
public interface IPersonne<T> {
    
    public void ajouterp(T p) throws SQLException;
    public void deletep(T p) throws SQLException;
    public void modifierp(T p) throws SQLException;
    public void modifierUser() throws SQLException;
    public List<T> afficherpersonne() throws SQLException;
    
    
}
