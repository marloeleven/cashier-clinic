import { Component, OnInit } from '@angular/core';
import { UserService } from '@app/service/user.service';
import { CookieService } from 'ngx-cookie-service';

const sidebarMinimize = 'sidebar-minimize';

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.css']
})
export class SidebarComponent implements OnInit {
  public userType: string;
  public name: string;

  public minimize = 0;

  constructor(
    public userService: UserService,
    private cookieService: CookieService
  ) {}

  ngOnInit() {
    this.userType = this.userService.type();

    this.name = this.userService.displayName();

    this.minimize = +this.cookieService.get(sidebarMinimize) || 0;

    this.updateSidebar();
  }

  toggleSidebar() {
    this.minimize = +!this.minimize;
    this.cookieService.set(sidebarMinimize, `${this.minimize}`);

    this.updateSidebar();
  }

  updateSidebar() {
    if (this.minimize) {
      document.body.classList.add('minimize');
      return;
    }
    document.body.classList.remove('minimize');
  }
}
