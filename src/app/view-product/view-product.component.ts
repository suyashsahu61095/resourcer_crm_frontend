import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { first } from 'rxjs/operators';

import { User } from '@app/_models';
import { UserService, AuthenticationService } from '@app/_services';

import { FormBuilder, FormGroup, Validators } from "@angular/forms";

import Swal from "sweetalert2";

import {Location} from '@angular/common';

import { AuthGuard } from "@app/_helpers";
declare var $: any;

@Component({
  selector: 'app-view-product',
  templateUrl: './view-product.component.html',
  styleUrls: ['./view-product.component.less']
})
export class ViewProductComponent implements OnInit {
  
  productForm: FormGroup;
  
  filecat: string[] = [];
  
  fileToUpload: File = null;
  filemultiUpload: File = null;
  currentUser: User;
  loading = false;
  loadingData = false;
  submitted = false;
  returnUrl: string;
  error = '';
  info: string;
  productInfo:any;
  status:any;
  editId:any;
  editimgUrl:any = '';
  categories:any;
  
  filesmulti:any;
constructor(
  private route: ActivatedRoute,
  private router: Router,
  private authenticationService: AuthenticationService,
  private userService: UserService,
  private formBuilder: FormBuilder,
  private _location: Location
) { 
  this.currentUser = this.authenticationService.currentUserValue;
}
backClicked() {
  this._location.back();
}
ngOnInit() {
      this.loadingData = true;
      this.loading = true;
      this.loadingData = true;
      this.editId = this.route.snapshot.paramMap.get('id');
      this.userService.getProductinfo(this.editId).pipe(first()).subscribe(data => {
          this.loading = false;
          this.loadingData = false;
          this.productInfo = data.product;
          this.productForm = this.formBuilder.group({
            product_name: this.productInfo.product_name,
            project_id: this.productInfo.project_id,
            product_id: this.productInfo.product_id,
            description: this.productInfo.description,
            category: this.productInfo.category,
            building_part: this.productInfo.building_part,
            unit: this.productInfo.unit,
            unitqnt: this.productInfo.unitqnt,
            quantity: this.productInfo.quantity,
            length: this.productInfo.length,
            width: this.productInfo.width,
            height: this.productInfo.height,
            production_year: this.productInfo.production_year,
            location_building: this.productInfo.location_building,
            brand_name: this.productInfo.brand_name,
            documentation: this.productInfo.documentation,
            product_info: this.productInfo.product_info,
            color: this.productInfo.color,
            hazardous: this.productInfo.hazardous,
            evaluvation: this.productInfo.evaluvation,
            precondition: this.productInfo.precondition,
            reuse: this.productInfo.reuse,
            recommendation: this.productInfo.recommendation,
            price_new_product: this.productInfo.price_new_product,
            status: this.productInfo.status,
            price_used_product: this.productInfo.price_used_product,
            price_sold_product: this.productInfo.price_sold_product,
          });
         
          if(this.productInfo.product_image != undefined){
            this.editimgUrl = data.image_base_path+'/'+this.productInfo.product_image;
          }
      });

      this.userService.getProductCategories().pipe(first()).subscribe(data => {
        this.loading = false;
        this.loadingData = false;
        this.categories = data.categories;
      });

      // get return url from route parameters or default to '/'
      this.returnUrl = this.route.snapshot.queryParams['returnUrl'] || '/';
      this.productForm = this.formBuilder.group({
        product_name: ["", Validators.required],
        project_id: this.route.snapshot.queryParams["param_id"]
          ? this.route.snapshot.queryParams["param_id"]
          : ["", Validators.required],
        description: [""],
        status: [""],
        category: [""],
        building_part: [""],
        unit: [""],
        unitqnt: [""],
        quantity: ["", [Validators.max(999999999), Validators.min(0)]],
        length: ["", [Validators.max(999999999), Validators.min(0)]],
        width: ["", [Validators.max(999999999), Validators.min(0)]],
        height: ["", [Validators.max(999999999), Validators.min(0)]],
        production_year: [""],
        location_building: [""],
        brand_name: [""],
        documentation: [""],
        product_info: [""],
        color: [""],
        hazardous: [""],
        evaluvation: [""],
        precondition: [""],
        reuse: [""],
        recommendation: [""],
        price_new_product: ["", [Validators.max(999999999), Validators.min(0)]],
        price_used_product: ["", [Validators.max(999999999), Validators.min(0)]],
        price_sold_product: ["", [Validators.max(999999999), Validators.min(0)]],
      });
    }
 
clonefun(){

  this.submitted = true;

  // stop here if form is invalid
  if (this.productForm.invalid) {
    console.log("invalid",this.productForm.value);
    return;
  }

  this.loading = true;
  this.loadingData = true;
  console.log(this.productForm.value); 
  //return false;
  console.log(JSON.stringify(this.productForm.value));
  //this.formData.append('data', JSON.stringify(this.customerForm.value));
  console.log(this.filemultiUpload);
  var formData = new FormData();
  if (this.filesmulti) {
    for (var i = 0; i < this.filesmulti.length; i++) {
      formData.append("imagemultiFile[]", this.filesmulti[i]);
      formData.append("filecategory[]", this.filecat[i]);
    }
  }
  if (this.fileToUpload) {
    formData.set("imageFile", this.fileToUpload, this.fileToUpload.name);
  }
  formData.append(
    "project_id",
    this.productForm.controls["project_id"].value
  );
  formData.append(
    "description",
    this.productForm.controls["description"].value
  );
  formData.append(
    "product_name",
    this.productForm.controls["product_name"].value
  );
  formData.append("category", this.productForm.controls["category"].value);
  formData.append(
    "building_part",
    this.productForm.controls["building_part"].value
  );
  formData.append("unit", this.productForm.controls["unit"].value);
  formData.append("unitqnt", this.productForm.controls["unitqnt"].value);
  formData.append("quantity", this.productForm.controls["quantity"].value);
  formData.append("height", this.productForm.controls["height"].value);
  formData.append("width", this.productForm.controls["width"].value);
  formData.append("length", this.productForm.controls["length"].value);
  formData.append("production_year",this.productForm.controls["production_year"].value);
  formData.append(
    "location_building",
    this.productForm.controls["location_building"].value
  );
  formData.append(
    "brand_name",
    this.productForm.controls["brand_name"].value
  );
  formData.append(
    "documentation",
    this.productForm.controls["documentation"].value
  );
  formData.append("color", this.productForm.controls["color"].value);
  formData.append("hazardous", this.productForm.controls["hazardous"].value);
  formData.append(
    "product_info",
    this.productForm.controls["product_info"].value
  );
  formData.append(
    "evaluvation",
    this.productForm.controls["evaluvation"].value
  );
  formData.append(
    "precondition",
    this.productForm.controls["precondition"].value
  );
  formData.append("reuse", this.productForm.controls["reuse"].value);
  formData.append(
    "recommendation",
    this.productForm.controls["recommendation"].value
  );
  formData.append(
    "price_new_product",
    this.productForm.controls["price_new_product"].value
  );
  formData.append("status", this.productForm.controls["status"].value);
  formData.append(
    "price_used_product",
    this.productForm.controls["price_used_product"].value
  );
  formData.append(
    "price_sold_product",
    this.productForm.controls["price_sold_product"].value
  );

  console.log(formData);

  this.userService
    .addProduct(formData)
    .pipe(first())
    .subscribe(
      (data) => {
        this.loading = false;
        this.loadingData = false;
        if (data.status == "1") {
          AuthGuard.blocked=false;
          this.router.navigate(["/edit-product/",data.products]);
          Swal.fire(data.project_id, "Product Cloned Sucessfully", "success");
          this.info = data.message;
        } else {
          this.error = data.message;
        }
       
      },
      (error) => {
        this.error = error;
        this.loading = false;
        this.loadingData = false;
      }
    );



}
}
