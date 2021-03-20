import { Injectable } from '@angular/core';
import {
  CanActivate,
  ActivatedRouteSnapshot,
  RouterStateSnapshot,
  Router
} from '@angular/router';
import { Observable } from 'rxjs';

import { CookieService } from 'ngx-cookie-service';

const ALLOWED_TYPES = ['CASHIER'];

@Injectable({
  providedIn: 'root'
})
export class UserGuard implements CanActivate {
  constructor(private cookieService: CookieService, private router: Router) {}

  canActivate(
    next: ActivatedRouteSnapshot,
    state: RouterStateSnapshot
  ): Observable<boolean> | Promise<boolean> | boolean {
    const type = this.cookieService.get('type');

    if (ALLOWED_TYPES.includes(type)) {
      return true;
    }

    this.router.navigate(['admin']);

    return false;
  }
}
