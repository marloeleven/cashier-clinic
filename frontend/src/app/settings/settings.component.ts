import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material';
import { HttpService } from '@app/service/http.service';
import { DeleteComponent } from '@app/component/dialog/delete/delete.component';
import { SnackbarHelper } from '@app/helper/snackbar.helper';

@Component({
  selector: 'app-settings',
  templateUrl: './settings.component.html',
  styleUrls: ['./settings.component.css']
})
export class SettingsComponent implements OnInit {
  constructor(
    private http: HttpService,
    private snackbarHelper: SnackbarHelper,
    public dialog: MatDialog
  ) {}

  ngOnInit() {}

  formSubmit(event) {
    event.preventDefault();

    const elements = event.target.elements;

    const password = elements.password.value;
    const newPassword = elements.new.value;
    const confirm = elements.confirm.value;

    let error = false;
    let errorMessage = '';

    if (!(password.length && newPassword.length && confirm.length)) {
      error = true;
      errorMessage = 'Please Complete all fields';
    } else {
      if (newPassword !== confirm) {
        error = true;
        errorMessage = 'New Password and Confrim Password do not match';
      }

      if (newPassword.length < 7) {
        error = true;
        errorMessage = 'New Password length must be greater than 7';
      }
    }

    if (error) {
      this.snackbarHelper.show(errorMessage, 'Close', {
        duration: 4000
      });
      return;
    }

    const dialogRef = this.dialog.open(DeleteComponent, {
      data: {
        data: {},
        title: 'Password Update'
      }
    });

    dialogRef.afterClosed().subscribe(res => {
      if (res) {
        this.http
          .post(`password`, { password, newPassword })
          .then(() => {
            this.snackbarHelper.show(
              'Password Updated Successfully!',
              'Close',
              {
                duration: 2000
              }
            );

            event.target.reset();
            elements.password.focus();
          })
          .catch(e => {
            this.snackbarHelper.show('Incorrect Password', 'close', {
              duration: 2000
            });
          });
      }
    });
  }
}
