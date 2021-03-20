import { Component } from '@angular/core';
import { MatDialog } from '@angular/material';
import { HttpService } from '@app/service/http.service';
import { UserService } from '@app/service/user.service';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import { DeleteComponent } from '@app/component/dialog/delete/delete.component';
import { FormComponent } from './dialog/form/form.component';
import BaseClass from '@app/component/BaseClass';

@Component({
  selector: 'app-lookup',
  templateUrl: './lookup.component.html',
  styleUrls: ['./lookup.component.css']
})
export class LookupComponent extends BaseClass {
  displayedColumns: string[] = [
    'id',
    'type',
    'name',
    'amount',
    'details',
    'index'
  ];

  protected FormComponent = FormComponent;
  protected DeleteComponent = DeleteComponent;
  protected FormTitle = 'Lookup';
  protected BaseUrl = 'lookup';

  constructor(
    protected http: HttpService,
    protected userService: UserService,
    protected snackbarHelper: SnackbarHelper,
    protected dialog: MatDialog
  ) {
    super(http, snackbarHelper, dialog);
  }

  ngOnInit() {
    if (this.userService.isAdmin()) {
      this.displayedColumns.push('action');
    }

    this.http.get('lookup').then(data => {
      this.data = data;

      this.showDeleted({ checked: false });
    });
  }
}
