import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { UntypedFormControl, Validators, UntypedFormGroup } from '@angular/forms';
import { Title } from '@angular/platform-browser';
import { AuthenticationService } from 'src/app/core/services/auth.service';
import { NotificationService } from 'src/app/core/services/notification.service';
import {MatSnackBar} from "@angular/material/snack-bar";
import * as bcrypt from "bcryptjs";
import {map} from "rxjs/operators";
import {UtilisateurService} from "../../users/service/utilisateur.service";
import {Utilisateur} from "../../create-user/utilisateur";

@Component({
    selector: 'app-login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
    email: string = "";
    password: string = "";
    message: string = "";
    public loginAttempts: number = 0;
    public maxLoginAttempts: number = 3;

    loginForm!: UntypedFormGroup;
    loading!: boolean;
   // error: string;

    constructor(private router: Router,
        private titleService: Title,
        private notificationService: NotificationService,
        private authService: AuthenticationService, private snackBar: MatSnackBar,
                private  userS: UtilisateurService) {
    }

    ngOnInit() {
        this.titleService.setTitle('angular-material-template - Login');
        this.authService.logout();
        this.createForm();
    }

    private createForm() {
        const savedUserEmail = localStorage.getItem('savedUserEmail');
        this.loginForm = new UntypedFormGroup({
            email: new UntypedFormControl(savedUserEmail, [Validators.required, Validators.email]),
            password: new UntypedFormControl('', Validators.required),
            rememberMe: new UntypedFormControl(savedUserEmail !== null)
        });
        this.loginForm.get('email')?.valueChanges
            .subscribe(val => {
                this.email = val;
            });
        this.loginForm.get('password')?.valueChanges
            .subscribe(val => {
                this.password = val;
            });
    }
 public authenticate(){
     console.log("logging in "+this.userS.getNbTentative(this.email));
     sessionStorage.removeItem("app.token");
     if(this.userS.getNbTentative(this.email)>3){
         this.notificationService.openSnackBar("Nombre maximal de tentatives excédé");
     }
     else{
     this.authService.login(this.email,this.password).subscribe((response) => {
             sessionStorage.setItem("currentUser", this.email);
             let tokenStr = response.token;
             sessionStorage.setItem("app.token", tokenStr);
             console.log(tokenStr)
       this.userS.getUtilisateurByEmail(this.email).subscribe(response=> {
           sessionStorage.setItem("currentUserName", (<string>response.nom) + (<string>response.prenom) );
           sessionStorage.setItem("app.role", <string>((response != null && response != undefined) ? response.role : "AdminITVermeg") );
                 this.router.navigateByUrl("''")
         })},
    error => {
    this.notificationService.openSnackBar("Veuillez vérifier votre email/mot de passe!");
    this.loading = false;
    this.userS.updateTentative(this.email);
})}

 }


    resetPassword() {
        this.router.navigate(['/auth/password-reset-request']);
    }
    isUserLoggedIn() {
        let user = sessionStorage.getItem("username");
        console.log(!(user === null));
        return !(user === null);
    }

    logOut() {
        sessionStorage.removeItem("username");
    }
}
