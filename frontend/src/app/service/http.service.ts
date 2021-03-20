import { Injectable, ErrorHandler } from '@angular/core';
import { Http, Headers } from '@angular/http';
import { CookieService } from 'ngx-cookie-service';
import { map, catchError } from 'rxjs/operators';
import { DEFAULT_URL } from '@app/variables';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class HttpService implements ErrorHandler {
  constructor(
    private http: Http,
    private cookieService: CookieService,
    private router: Router
  ) {}

  generateHeaders() {
    const headers = new Headers();
    const token = this.cookieService.get('token');
    if (token) {
      headers.append('token', token);
    }

    return headers;
  }

  get(url) {
    return this.http
      .get(`${DEFAULT_URL}${url}`, {
        headers: this.generateHeaders()
      })
      .pipe(
        map(res => res.json()),
        catchError(err => this.handleError(err))
      )
      .toPromise();
  }

  post(url, data) {
    return this.http
      .post(`${DEFAULT_URL}${url}`, data, {
        headers: this.generateHeaders()
      })
      .pipe(
        map(res => res.json()),
        catchError(err => this.handleError(err))
      )
      .toPromise();
  }

  delete(url) {
    return this.http
      .delete(`${DEFAULT_URL}${url}`, {
        headers: this.generateHeaders()
      })
      .pipe(
        map(res => res.json()),
        catchError(err => this.handleError(err))
      )
      .toPromise();
  }

  handleError(error) {
    if (error.status === 401) {
      this.router.navigate(['/logout']);
      return error;
    }
    throw error;
  }
}
