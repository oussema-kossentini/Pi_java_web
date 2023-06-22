/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/javafx/FXMLController.java to edit this template
 */
package gui;

import java.net.URL;
import java.util.ResourceBundle;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.LoadException;
import javafx.fxml.Initializable;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TextField;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import entities.Reclamation;
import service.IReclamation;
import service.ReclamationService;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;
import java.util.logging.Level;
import java.net.URL;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.ResourceBundle;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Label;
import javafx.fxml.FXML;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TextField;
import java.sql.*;
import java.util.logging.Level;
import java.util.logging.Logger;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.scene.control.Alert;
import javafx.scene.control.TableRow;
import utils.MyDB;
import java.sql.Date;
import javafx.scene.control.DatePicker;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.input.DragEvent;
import javafx.scene.input.MouseDragEvent;
import javafx.scene.input.MouseEvent;
/**
 * FXML Controller class
 *
 * @author user
 */
public class RecfxmController implements Initializable{

    @FXML
    private TextField type;
   
    @FXML
    private TextField description;
    @FXML
    private DatePicker date1;
    @FXML
    private TableColumn<Reclamation, String> TABtype;
    @FXML
    private TableColumn<Reclamation, Timestamp> TABdate;
    @FXML
    private TableColumn<Reclamation, String> TABdescription;
    @FXML
    private TableColumn<Reclamation,Integer> id;
    @FXML
    private TableView<Reclamation> TABrec;
    @FXML
    private TextField deletefield;
//private ObservationList<Reclamation> listU = FXCollections.ObservableArrayList();
  ObservableList<Reclamation> listU = FXCollections.observableArrayList();
    @FXML
    private TextField date2;
    @FXML
    private Button update;
    /**
     * Initializes the controller class.
     * @param url
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
       // TABrec();
       
              ObservableList<Reclamation> listU = FXCollections.observableArrayList();
        TABtype.setCellValueFactory(new PropertyValueFactory<Reclamation,String>("typeREC"));
        TABdate.setCellValueFactory(new PropertyValueFactory<Reclamation,Timestamp>("date"));
        TABdescription.setCellValueFactory(new PropertyValueFactory<Reclamation,String>("description"));
        id.setCellValueFactory(new PropertyValueFactory<Reclamation,Integer>("idR"));
        
        //ObservableList<Reclamation> listU = FXCollections.observableArrayList();
ReclamationService ps=new ReclamationService();

        try {      
            ps.afficherReclamation().forEach(r->{listU.add(r);});
        } catch (SQLException ex) {
            Logger.getLogger(RecfxmController.class.getName()).log(Level.SEVERE, null, ex);
        }
        TABrec.setItems(listU);
        // TODO
    }    
    //
    Connection con;
    PreparedStatement pst;
    int myIndex;
    int id8;
    ///
    
    
     public void Connect()
    {
        try {
            Class.forName("com.mysql.jdbc.Driver");
            con = DriverManager.getConnection("jdbc:mysql://localhost/reclamation","root","");
        } catch (ClassNotFoundException ex) {
          
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }
            
    void ajouter(ActionEvent event) throws SQLException {
      
        /*
              // DatePicker picker = new DatePicker();
           
         
             //Date date = new Date(14-11-2022);
        ReclamationService ps = new ReclamationService() {} ;
       
         
 
        Reclamation r=new Reclamation();
        ps.ajouterp(r);
       
        Alert alert=new Alert(Alert.AlertType.INFORMATION);
        alert.setTitle("Success");
        alert.setContentText("User is added successfully!");
        alert.show();
       
        System.out.println("Utilisateur ajoutée");
    }

        ReclamationService ps = new ReclamationService() {} ;
        Reclamation u=new Reclamation(type.getText(),description.getText(),Date.valueOf(date1.getValue()) );
        ps.ajouterp(u);
        clean();
        Alert alert=new Alert(Alert.AlertType.INFORMATION);
        alert.setTitle("mabrouuk");
        alert.setContentText("reclamation is added successfully!");
        alert.show();
       
        System.out.println("reclamation ajoutée");
        
        */
        
    }
    
    
    

    @FXML
    private void supprimer(ActionEvent event) throws SQLException {
        ReclamationService sr = new ReclamationService();

        int id = Integer.parseInt(deletefield.getText());
        sr.supprimerR(id);
         System.out.print("utilisateur supprime ");
    }
public ObservableList<Reclamation> getRec(List<Reclamation> l){
        ObservableList<Reclamation> listU = FXCollections.observableArrayList();
        for (int i =0; i<=l.size()-1; i++){
            listU.add(l.get(i));
        }
        return listU;
    }
  /*  private void afficher(ActionEvent event, ObservableList<Reclamation> Reclamation) throws SQLException {
        
      //  TABrec.setItems(Reclamation);
        
       ReclamationService ps = new ReclamationService() {} ;
      // ps.setId(rs.getString("id"));
        //TABtype.setCellValueFactory(new PropertyValueFactory<Reclamation,String>("typeREC"));
      id.setCellValueFactory(new PropertyValueFactory<Reclamation,Integer>("idR"));
            TABtype.setCellValueFactory(new PropertyValueFactory<Reclamation,String>("typeREC"));
        TABdate.setCellValueFactory(new PropertyValueFactory<Reclamation,Timestamp>("date"));
        TABdescription.setCellValueFactory(new PropertyValueFactory<Reclamation,String>("description"));
       

      
        
      TABrec.setItems(getRec(ps.afficherReclamation()));
      
      
      
      
              
    }
*/
    

    private void table() {
        throw new UnsupportedOperationException("Not supported yet."); // Generated from nbfs://nbhost/SystemFileSystem/Templates/Classes/Code/GeneratedMethodBody
    }

    @FXML
    private void ADD(ActionEvent event)throws SQLException  {
        
             ReclamationService ps = new ReclamationService() {} ;
        Reclamation u=new Reclamation(type.getText(),description.getText(),Date.valueOf(date1.getValue()) );
        ps.ajouterp(u);
      //  clean();
        Alert alert=new Alert(Alert.AlertType.INFORMATION);
        alert.setTitle("mabrouuk");
        alert.setContentText("reclamation is added successfully!");
        alert.show();
       
        System.out.println("reclamation ajoutée");
        cleanLabel();
        
        
    }

    private void TABrec() {
        throw new UnsupportedOperationException("Not supported yet."); // Generated from nbfs://nbhost/SystemFileSystem/Templates/Classes/Code/GeneratedMethodBody
        
        
    }

   private void afficherT (ActionEvent event , ObservableList<Reclamation> Reclamation)  throws SQLException {
        
      //  TABrec.setItems(Reclamation);
        
       ReclamationService ps = new ReclamationService() {} ;
        
      //  List<Reclamation> afficherReclamation = ps.afficherReclamation();
      // ps.setId(rs.getString("id"));
        //TABtype.setCellValueFactory(new PropertyValueFactory<Reclamation,String>("typeREC"));
      id.setCellValueFactory(new PropertyValueFactory<>("idR"));
            TABtype.setCellValueFactory(new PropertyValueFactory<>("typeREC"));
        TABdate.setCellValueFactory(new PropertyValueFactory<>("date"));
        TABdescription.setCellValueFactory(new PropertyValueFactory<>("description"));
       

      
        
      TABrec.setItems(getRec(ps.afficherReclamation()));
      
        
    }
    
    public void cleanLabel() {
 
    type.setText("");
    description.setText("");
 deletefield.setText("");
date1.setValue(null);
    }

    @FXML
    private void tenzel(MouseDragEvent event) {
     /*   
          // Reclamaion P=TABrec.
    Reclamation P = TABrec.getSelectionModel().getSelectedItem();
    deletefield.setText("" +P.getId());
   type.setText("" +P.getType());
  // date1.setValue("" +P.getDate());
   description.setText("" +P.getDescription());
    }

    */
    }

    @FXML
    private void tezl(DragEvent event) {
        Reclamation P = TABrec.getSelectionModel().getSelectedItem();
    deletefield.setText("" +P.getId());
   type.setText("" +P.getType());
   date2.setText("" +P.getDate());
   description.setText("" +P.getDescription());
        
    }

    @FXML
    private void tbt(MouseEvent event) {
         Reclamation P = TABrec.getSelectionModel().getSelectedItem();
    deletefield.setText("" +P.getId());
   type.setText("" +P.getType());
   date2.setText("" +P.getDate());
   description.setText("" +P.getDescription());
    }

   /* private void afficher(ActionEvent event,ObservableList<Reclamation> Reclamation) throws SQLException {
         ReclamationService ps = new ReclamationService() {} ;
      // ps.setId(rs.getString("id"));
        //TABtype.setCellValueFactory(new PropertyValueFactory<Reclamation,String>("typeREC"));
      id.setCellValueFactory(new PropertyValueFactory<Reclamation,Integer>("idR"));
            TABtype.setCellValueFactory(new PropertyValueFactory<Reclamation,String>("typeREC"));
        TABdate.setCellValueFactory(new PropertyValueFactory<Reclamation,Timestamp>("date"));
        TABdescription.setCellValueFactory(new PropertyValueFactory<Reclamation,String>("description"));
      TABrec.setItems(getRec(ps.afficherReclamation()));
    }
*/

    @FXML
    private void update(ActionEvent event) throws SQLException {
       
    ReclamationService ps = new ReclamationService() {} ;
    Reclamation k;
     int p =Integer.parseInt(deletefield.getText());
        k= new Reclamation(p,type.getText(),description.getText(),Date.valueOf(date1.getValue()));
   
    ps.modifierR(k);
     Alert alert = new Alert(Alert.AlertType.INFORMATION);
            alert.setTitle("Information Dialog");
            alert.setHeaderText(null);
            alert.setContentText(" modifier avec succès!");
            alert.showAndWait();
   
    cleanLabel();
    }


    @FXML
    private void modifier(ActionEvent event) throws SQLException {
        
        
         int p =Integer.parseInt(deletefield.getText());
    ReclamationService ps = new ReclamationService() {} ;
    Reclamation m;
        m = new Reclamation(p,type.getText(),description.getText(),Date.valueOf(date1.getValue()));
   
    ps.modifierp(m);
     Alert alert = new Alert(Alert.AlertType.INFORMATION);
            alert.setTitle("Information Dialog");
            alert.setHeaderText(null);
            alert.setContentText(" modifier avec succès!");
            alert.showAndWait();
   
    cleanLabel();
        
        
    }

    @FXML
    private void readd(ActionEvent event) throws SQLException {
         ReclamationService ps = new ReclamationService() {} ;
      // ps.setId(rs.getString("id"));
        //TABtype.setCellValueFactory(new PropertyValueFactory<Reclamation,String>("typeREC"));
      id.setCellValueFactory(new PropertyValueFactory<>("idR"));
            TABtype.setCellValueFactory(new PropertyValueFactory<>("typeREC"));
        TABdate.setCellValueFactory(new PropertyValueFactory<>("date"));
        TABdescription.setCellValueFactory(new PropertyValueFactory<>("description"));
      TABrec.setItems(getRec(ps.afficherReclamation()));
    }


}