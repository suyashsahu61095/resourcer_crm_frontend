import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { first } from 'rxjs/operators';
import Swal from 'sweetalert2';

import { User } from '@app/_models';
import { UserService, AuthenticationService } from '@app/_services';
declare var $: any;

@Component({
  selector: 'app-edit-client',
  templateUrl: './edit-client.component.html',
  styleUrls: ['./edit-client.component.less']
})
export class EditClientComponent implements OnInit {

    clientForm: FormGroup;
    loading = false;
    loadingData = false;
    submitted = false;
    returnUrl: string;
    error = '';
    info: string;
    client:any;
    editId:any;

  constructor(
    private formBuilder: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private authenticationService: AuthenticationService,
    private userService: UserService
  ) { }

  ngOnInit() {
        this.clientForm = this.formBuilder.group({
        name: ['', Validators.required],
        email: ['', Validators.required],
        description: ['', Validators.required]
    });

    this.loading = true;
    this.loadingData = true;
    this.editId = this.route.snapshot.paramMap.get('id');
    this.userService.getclientinfo(this.editId).pipe(first()).subscribe(data => {
        this.loading = false;
        this.loadingData = false;
        this.client = data.client;
        this.clientForm.setValue({
          name: this.client.name,
          email: this.client.email,
          description: this.client.description,
       });
    });
}

// convenience getter for easy access to form fields
get f() { return this.clientForm.controls; }

onSubmit() {
    this.submitted = true;

    // stop here if form is invalid
    if (this.clientForm.invalid) {
        return;
    }

    this.loading = true;
    this.loadingData = true;
    this.userService.editClient(this.clientForm.value, this.editId)
        .pipe(first())
        .subscribe(
            data => {
                this.loading = false;
                this.loadingData = false;
                if(data.status == '1') {
                  Swal.fire('', data.message, 'success');
                }
                this.router.navigate(['/clients']);
            },
            error => {
                this.error = error;
                this.loading = false;
                this.loadingData = false;
            });
}


}
