import { Component } from '@angular/core';
import { MatDialog } from '@angular/material';
import { HttpService } from '@app/service/http.service';
import { UserService } from '@app/service/user.service';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import { DeleteComponent } from '@app/component/dialog/delete/delete.component';
import { FormComponent } from './dialog/form/form.component';
import { USER_TYPES, GENDER_TYPES } from '@app/variables';
import BaseClass from '@app/component/BaseClass';

const mapSearchable = obj => {
  obj.FirstMiddleLastname = `${obj.first_name} ${obj.middle_name} ${
    obj.last_name
  }`;
  obj.firstLastMiddleName = `${obj.first_name} ${obj.last_name} ${
    obj.middle_name
  }`;
  obj.LastFirstMiddlenameReverse = `${obj.last_name} ${obj.first_name} ${
    obj.middle_name
  }`;
  return obj;
};

@Component({
  selector: 'app-user',
  templateUrl: './user.component.html',
  styleUrls: ['./user.component.css']
})
export class UserComponent extends BaseClass {
  displayedColumns: string[] = [
    'id',
    'username',
    'first_name',
    'middle_name',
    'last_name',
    'email',
    'type',
    'gender'
  ];

  protected FormComponent = FormComponent;
  protected DeleteComponent = DeleteComponent;
  protected FormTitle = 'User';
  protected BaseUrl = 'user';

  public USER_TYPES = USER_TYPES;
  public GENDER_TYPES = GENDER_TYPES;

  constructor(
    private userService: UserService,
    protected http: HttpService,
    protected snackbarHelper: SnackbarHelper,
    protected dialog: MatDialog
  ) {
    super(http, snackbarHelper, dialog);
  }

  ngOnInit() {
    if (this.userService.isAdmin()) {
      this.displayedColumns.push('action');
    }

    this.http.get('user').then(data => {
      this.data = data;

      this.showDeleted({ checked: false });
    });
  }

  dataFilter(checked) {
    return this.data
      .filter(item => !!item.disabled === checked)
      .map(mapSearchable);
  }
}
