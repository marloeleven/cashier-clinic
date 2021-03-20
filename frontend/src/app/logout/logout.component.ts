import { Component, OnInit } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { Router } from '@angular/router';
import { deleteAllCookies } from '@app/helper/function';
@Component({
  selector: 'app-logout',
  templateUrl: './logout.component.html',
  styleUrls: ['./logout.component.css']
})
export class LogoutComponent implements OnInit {
  constructor(private router: Router, private cookieService: CookieService) {}

  ngOnInit() {
    this.cookieService.deleteAll();
    this.cookieService.deleteAll('/');
    this.cookieService.deleteAll('../');
    deleteAllCookies();
    this.router.navigate(['/login']);
  }
}
