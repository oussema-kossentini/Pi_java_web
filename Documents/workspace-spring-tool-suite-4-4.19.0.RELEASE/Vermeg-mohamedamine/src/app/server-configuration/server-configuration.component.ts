import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-server-configuration',
  templateUrl: './server-configuration.component.html',
  styleUrls: ['./server-configuration.component.css']
})
export class ServerConfigurationComponent implements OnInit {
  serverConfiguration!: FormGroup;

  constructor(private formBuilder: FormBuilder) { }

  ngOnInit() {
    this.serverConfiguration = this.formBuilder.group({
      hostname: ['', Validators.required],
      username: ['', Validators.required],
      port: ['', Validators.required],
      status: ['', Validators.required]
    });
  }

  submitForm() {
    if (this.serverConfiguration.valid) {
      // Handle form submission
      console.log(this.serverConfiguration.value);
    } else {
      // Form is invalid, display validation errors
      const errors = this.serverConfiguration.errors;
      for (const error in errors) {
        if (errors.hasOwnProperty(error)) {
          console.log(`${error}: ${errors[error]}`);
        }
      }
    }
  }
}
