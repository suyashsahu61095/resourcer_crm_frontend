import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { first } from 'rxjs/operators';
import Swal from 'sweetalert2';

import { UserService, AuthenticationService } from '@app/_services';
declare var $: any;

@Component({
  selector: 'app-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.less']
})
export class FooterComponent implements OnInit {
issueForm: FormGroup;
  loading = false;
  loadingData = false;
  submitted = false;
  returnUrl: string;
  error = '';
  info: string;
  constructor(
    private authenticationService: AuthenticationService,
    private userService: UserService) { }

  ngOnInit() {
  }

  onSubmit() {
    this.submitted = true;

    // stop here if form is invalid
    if (this.issueForm.invalid) {
        return;
    }


    this.loading = true;
    this.loadingData = true;
    this.userService.issueMail(this.issueForm.value)
        .pipe(first())
        .subscribe(
            data => {
                this.loading = false;
                this.loadingData = false;
                if(data.status == '1') {
                  Swal.fire('', data.message, 'success');
                }
            },
            error => {
                Swal.fire('', "Invalid Input", 'error');
                this.error = error;
                this.loading = false;
                this.loadingData = false;
            });
}
}
