import { Injectable } from '@angular/core';
import { MatSnackBar } from '@angular/material';


@Injectable({
  providedIn: 'root'
})
export class SnackbarHelper {

  constructor(public snackBar: MatSnackBar) {}

  show(message: string = 'Server Error, Please try again later', action: string = 'Close', options = { duration: 5000 }) {
    const newOptions = Object.assign({
      duration: 5000
    }, options);

    return this.snackBar.open(message, action, newOptions);
  }
}