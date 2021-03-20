import { Component } from '@angular/core';
import { MatDialog } from '@angular/material';
import { HttpService } from '@app/service/http.service';
import { UserService } from '@app/service/user.service';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import { DeleteComponent } from '@app/component/dialog/delete/delete.component';
import { FormComponent } from './form/form.component';
import { PROCEDURE_TYPES, toDropdownArray, REPORT_TYPES } from '@app/variables';
import { Format } from '@app/helper/format';
import BaseClass from '@app/component/BaseClass';

@Component({
  selector: 'app-categories',
  templateUrl: './categories.component.html',
  styleUrls: ['./categories.component.css']
})
export class CategoriesComponent extends BaseClass {
  displayedColumns: string[] = [
    'id',
    'procedure_type',
    'name',
    'alias',
    'report_type',
    'index'
  ];

  protected FormComponent = FormComponent;
  protected DeleteComponent = DeleteComponent;
  protected FormTitle = 'Category';
  protected BaseUrl = 'procedure/category';

  public proceduresArray = toDropdownArray(PROCEDURE_TYPES);
  public PROCEDURE_TYPES = PROCEDURE_TYPES;
  public REPORT_TYPES = REPORT_TYPES;
  public Format = Format;

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

    this.http.get('procedure/category').then(data => {
      this.data = data;

      this.showDeleted({ checked: false });
    });
  }
}
