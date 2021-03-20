import { Injectable } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { CASHIER, USER_TYPES } from '@app/variables';

@Injectable({
  providedIn: 'root'
})
export class UserService {
  constructor(private cookieService: CookieService) {}

  get(data) {
    return this.cookieService.get(data) || '';
  }

  isAdmin() {
    return this.get('type') !== 'CASHIER';
  }

  type() {
    return USER_TYPES[this.get('type')];
  }

  displayName() {
    const { first_name, middle_name, last_name } = JSON.parse(this.get('data'));
    const middle_initial = middle_name.length
      ? middle_name.charAt(0) + '.'
      : '';
    return `${first_name} ${middle_initial} ${last_name}`;
  }
}
