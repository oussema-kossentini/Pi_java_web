/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Interface.java to edit this template
 */
package service;

import java.sql.SQLException;
import java.util.List;


/**
 *
 * @author ousse
 * @param <T>
 
 */
public interface IAvis <T>{
    public void ajouteA(T r) throws SQLException;
    public void deletep(T r) throws SQLException;
    public void modifierp(T r) throws SQLException;
    public void modifierUserAvis() throws SQLException;
 public List<T> afficherAvis() throws SQLException;
}
