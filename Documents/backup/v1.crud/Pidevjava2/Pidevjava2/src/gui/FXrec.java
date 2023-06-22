/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Main.java to edit this template
 */
package gui;

/**
 *
 * @author user
 */

import javafx.application.Application; 
         import javafx.fxml.FXMLLoader; 
         import javafx.stage.Stage; 
         import javafx.scene.Parent; 
         import javafx.scene.Scene; 
         import javafx.scene.layout.BorderPane; 

public class FXrec extends Application { 
            @Override
               public void start(Stage stage) throws Exception { 
                  Parent root = 
                  FXMLLoader.load(getClass().getResource("recfxm.fxml")); 
                  Scene scene = new Scene(root); 
                  stage.setScene(scene); 
                  stage.setTitle("hello"); 
                  stage.show(); 
             } 
public static void main(String[] args) { 
                  launch(args); 
             } 
        }
