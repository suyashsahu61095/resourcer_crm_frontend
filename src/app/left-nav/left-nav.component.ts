import { Component, OnInit } from "@angular/core";
import { Router, ActivatedRoute } from "@angular/router";
import { first } from "rxjs/operators";
import Swal from "sweetalert2";
import { User } from "@app/_models";
import { UserService, AuthenticationService } from "@app/_services";
import { Location } from "@angular/common";
declare var $: any;

@Component({
  selector: "app-left-nav",
  templateUrl: "./left-nav.component.html",
  styleUrls: ["./left-nav.component.less"],
})
export class LeftNavComponent implements OnInit {
  currentUser: User;
  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private authenticationService: AuthenticationService,
    private userService: UserService,
    private location: Location
  ) {
    this.currentUser = this.authenticationService.currentUserValue;
  }

  ngOnInit() {}
  onNavigation(nextUrl) {
    console.log(nextUrl);
    if (location.pathname === nextUrl) location.reload();
  }


  toggleheader(){
    console.log("hdhhd");
    if($('#toggle-btn').hasClass('active')){
      $('#toggle-btn').removeClass('active')
    } else{
      $('#toggle-btn').addClass('active')
    }
    $('.side-navbar').toggleClass('shrinked');
    $('.content-inner').toggleClass('active');
      
      if ($(window).outerWidth() > 1183) {
          if ($('#toggle-btn').hasClass('active')) {
              $('.navbar-header .brand-small').hide();
              $('.navbar-header .brand-big').show();
          } else {
              $('.navbar-header .brand-small').show();
              $('.navbar-header .brand-big').hide();
          }
      }

       if ($(window).outerWidth() < 1183) {
           $('.navbar-header .brand-small').show();
      }
  }
}
