/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Main.java to edit this template
 */
package pidevjava2;

   import javafx.application.Application; 
         import javafx.fxml.FXMLLoader; 
         import javafx.stage.Stage; 
         import javafx.scene.Parent; 
         import javafx.scene.Scene; 
         import javafx.scene.layout.BorderPane; 

public class testf extends Application { 
            @Override
               public void start(Stage stage) throws Exception { 
                  Parent root = 
                  FXMLLoader.load(getClass().getResource("gui/reclamationFXML.fxml")); 
                  Scene scene = new Scene(root); 
                  stage.setScene(scene); 
                  stage.setTitle("hello"); 
                  stage.show(); 
             } 
public static void main(String[] args) { 
                  launch(args); 
             } 
        }
