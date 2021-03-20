import { Component, OnInit, ViewChild } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { MatButton } from '@angular/material';
import { CookieService } from 'ngx-cookie-service';
import { HttpService } from '@app/service/http.service';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import Message from '@app/message';

interface Input {
  value: string;
  error: boolean;
}

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  public username: Input = {
    value: '',
    error: false
  };

  public password: Input = {
    value: '',
    error: false
  };

  private returnUrl: string;

  @ViewChild('submit') submit : MatButton;

  constructor(
    private http: HttpService,
    private router: Router,
    private route: ActivatedRoute,
    private cookieService: CookieService,
    private snackbarHelper: SnackbarHelper
  ) {}

  ngOnInit() {
    this.returnUrl = this.route.snapshot.queryParams['returnUrl'] || '/';

    if (this.cookieService.get('token')) {
      this.router.navigate(['/']);
    }
  }

  login(event) {
    event.preventDefault();

    const { target } = event;
    const username = target.elements.username.value;
    const password = target.elements.password.value;

    if (!username.length || !password.length) {
      return;
    }

    this.submit.disabled = true;

    this.http
      .post('login', {
        username,
        password
      })
      .then(
        data => {
          this.cookieService.set('token', data.token);
          this.cookieService.set('type', data.type);
          this.cookieService.set('data', JSON.stringify(data));

          this.snackbarHelper.show(Message.loginSuccess, 'close', { duration: 2000 });

          setTimeout(() => {
            if (this.returnUrl.length) {
              this.router.navigate([this.returnUrl]);
              return;
            }


            this.router.navigate(['dashboard'])
          }, 2000);
        },
        ({ _body }) => {
          const { message, errors } = JSON.parse(_body);

          this.snackbarHelper.show(message, 'close', { duration: 2000 });

          setTimeout(() => this.submit.disabled = false, 2000);

          if (message.indexOf('Username') !== -1) {
            this.username.error = true;
            return;
          }

          this.password.error = true;
        }
      );
  }
}
