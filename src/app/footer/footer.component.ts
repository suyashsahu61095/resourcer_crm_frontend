import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { first } from 'rxjs/operators';
import Swal from 'sweetalert2';
import {Location, LocationStrategy, PathLocationStrategy} from '@angular/common';
import { Router } from '@angular/router';
import { DeviceDetectorService, DeviceInfo } from 'ngx-device-detector';

import { UserService, AuthenticationService } from '@app/_services';
declare var $: any;

@Component({
  selector: 'app-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.less'],
  providers: [Location, {provide: LocationStrategy, useClass: PathLocationStrategy}],
})
export class FooterComponent implements OnInit {
issueForm: FormGroup;
  loading = false;
  loadingData = false;
  submitted = false;
  returnUrl: string;
  error = '';
  info: string;
  public href: string = "";
  browser:any;
  location: Location;
  device_name: any ;
  img:any;
  fileToUpload: File = null;
  usermail:any;
  imgURL: any = "";
  currentUser:any;
  constructor(
    private authenticationService: AuthenticationService,
    private userService: UserService, private router: Router,private formBuilder: FormBuilder,location: Location,private deviceDetectorService: DeviceDetectorService) { 
      this.currentUser = this.authenticationService.currentUserValue;
    }

  ngOnInit() {
    this.issueForm = this.formBuilder.group({
      issue: [ "", [Validators.required, ],],
    });
    this.browser = this.myBrowser();
    this.href=window.location.href;
    this.usermail=  this.currentUser.email;
    console.log("usermail",  this.usermail);
    console.log( window.location.href);
  }

  myBrowser() { 
    if((navigator.userAgent.indexOf("Opera") || navigator.userAgent.indexOf('OPR')) != -1 ) {
        return 'Opera';
    }else if(navigator.userAgent.indexOf("Chrome") != -1 ){
        return 'Chrome';
    }else if(navigator.userAgent.indexOf("Safari") != -1){
        return 'Safari';
    }else if(navigator.userAgent.indexOf("Firefox") != -1 ) {
         return 'Firefox';
    }else if((navigator.userAgent.indexOf("MSIE") != -1 ) || (!!document.DOCUMENT_NODE == true )){
      return 'IE'; 
    } else {
       return 'unknown';
    }
}

preview(files) {
  console.log(files);
  if (files.length === 0) return;

  var mimeType = files[0].type;
  console.log(files[0].size);


  var reader = new FileReader();
  this.fileToUpload = files[0];
  reader.readAsDataURL(files[0]);
  reader.onload = (_event) => {
    this.imgURL = reader.result;
  };
}
  onSubmit() {
    this.loading = true;
  
    this.submitted = true;
    ($("#report") as any).modal("hide");
console.log("called")
console.log(this.fileToUpload);
var formData = new FormData();
    // stop here if form is invalid
    if (this.issueForm.invalid) {
        return;
    }
    if (this.fileToUpload) {
      formData.set("imageFile", this.fileToUpload, this.fileToUpload.name);
    }


    formData.append("issue", this.issueForm.controls["issue"].value);
    formData.append("page",   this.href);
    formData.append("browser",this.browser);
    formData.append("usermail",this.usermail);
 console.log(formData);
    this.loading = true;
    this.loadingData = true;
    this.userService.issueMail(formData)
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

open(){
  ($("#report") as any).modal("show");
}
get f() {
  return this.issueForm.controls;
}

}
