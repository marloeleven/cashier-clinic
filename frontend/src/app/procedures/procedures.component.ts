import { Component } from '@angular/core';
import { MatDialog } from '@angular/material';
import { HttpService } from '@app/service/http.service';
import { UserService } from '@app/service/user.service';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import { DeleteComponent } from '@app/component/dialog/delete/delete.component';
import { FormComponent } from './dialog/form/form.component';
import { PROCEDURE_TYPES } from '@app/variables';
import { Format } from '@app/helper/format';
import BaseClass from '@app/component/BaseClass';

const mapSearchable = item => {
  item.procedure_type = item.procedure_type_category.procedure_type;
  item.category = item.procedure_type_category.name;
  return item;
};

@Component({
  selector: 'app-procedures',
  templateUrl: './procedures.component.html',
  styleUrls: ['./procedures.component.css']
})
export class ProceduresComponent extends BaseClass {
  displayedColumns: string[] = [
    'id',
    'procedure_type',
    'category',
    'name',
    'amount',
    'details',
    'sort'
  ];

  protected FormComponent = FormComponent;
  protected DeleteComponent = DeleteComponent;
  protected FormTitle = 'Procedure';
  protected BaseUrl = 'procedure';

  public proceduresArray = PROCEDURE_TYPES;
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

    this.http.get('procedure').then(data => {
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
