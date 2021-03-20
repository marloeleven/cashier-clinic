import { Injectable } from '@angular/core';
import { HttpService } from '@app/service/http.service';
import Message from '@app/message';

@Injectable({
  providedIn: 'root'
})
export class SaveService {
  constructor(private http: HttpService) {}

  save({
    requestType,
    id,
    data,
    dialogRef,
    snackbarHelper,
    target,
    updateURL,
    createURL
  }) {
    const required = Array.from(target.querySelectorAll('[required]')).map(
      (el: HTMLElement) => el.getAttribute('name')
    );

    if (required.map(key => data[key]).includes('')) {
      snackbarHelper.show(Message.required, 'Close', {
        duration: 3000
      });
      return;
    }

    if (requestType === 'edit') {
      Object.assign(data, { id });

      this.http
        .post(updateURL, data)
        .then(() => {
          dialogRef.close(data);
          snackbarHelper.show(Message.updated, 'Close', {
            duration: 5000
          });
        })
        .catch(({ _body }) => {
          const { errors } = JSON.parse(_body);

          snackbarHelper.show(errors[0], 'Close', {
            duration: 5000
          });
        });

      return;
    }

    this.http
      .post(createURL, data)
      .then(res => {
        dialogRef.close(Object.assign(data, res));
        snackbarHelper.show(Message.created, 'Close', {
          duration: 5000
        });
      })
      .catch(({ _body }) => {
        const { errors } = JSON.parse(_body);

        snackbarHelper.show(errors[0], 'Close', {
          duration: 5000
        });
      });
  }
}
