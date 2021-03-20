import { Component, OnInit } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit {
  public type: string;

  constructor(private cookieService: CookieService) {}

  ngOnInit() {
    this.type = this.cookieService.get('type');
  }
}
