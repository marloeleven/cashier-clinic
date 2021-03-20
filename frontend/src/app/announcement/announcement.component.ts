import { Component } from '@angular/core';
import { MatDialog } from '@angular/material';
import { HttpService } from '@app/service/http.service';
import { DeleteComponent } from '@app/component/dialog/delete/delete.component';
import { FormComponent } from './form/form.component';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import { Format } from '@app/helper/format';
import BaseClass from '@app/component/BaseClass';

@Component({
  selector: 'app-announcement',
  templateUrl: './announcement.component.html',
  styleUrls: ['./announcement.component.css']
})
export class AnnouncementComponent extends BaseClass {
  displayedColumns: string[] = ['id', 'body', 'title', 'created_at', 'action'];

  protected FormComponent = FormComponent;
  protected DeleteComponent = DeleteComponent;
  protected FormTitle = 'Announcement';
  protected BaseUrl = 'announcement';

  public Format = Format;

  constructor(
    protected http: HttpService,
    protected snackbarHelper: SnackbarHelper,
    protected dialog: MatDialog
  ) {
    super(http, snackbarHelper, dialog);
  }

  ngOnInit() {
    this.http.get('announcement').then(data => {
      this.data = data;

      this.showDeleted({ checked: false });
    });
  }
}
