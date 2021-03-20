import { ViewChild, OnInit } from '@angular/core';
import {
  MatSort,
  MatTableDataSource,
  MatPaginator,
  MatDialog
} from '@angular/material';
import { HttpService } from '@app/service/http.service';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import Message from '@app/message';

export default abstract class BaseClass implements OnInit {
  public dataSource = new MatTableDataSource([]);

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;

  public data = [];
  public filterDeleted = false;

  protected FormComponent: any;
  protected DeleteComponent: any;
  protected FormTitle: string;
  protected BaseUrl: string;

  constructor(
    protected http: HttpService,
    protected snackbarHelper: SnackbarHelper,
    protected dialog: MatDialog
  ) {}

  ngOnInit() {}

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();

    if (this.dataSource.paginator) {
      this.dataSource.paginator.firstPage();
    }
  }

  dataFilter(checked) {
    return this.data.filter(item => !!item.disabled === checked);
  }

  showDeleted({ checked }) {
    this.dataSource = new MatTableDataSource(this.dataFilter(checked));
    this.dataSource.sort = this.sort;

    if (this.filterDeleted !== checked) {
      this.paginator.pageIndex = 0;
    }

    this.dataSource.paginator = this.paginator;

    this.filterDeleted = checked;
  }

  newDialog() {
    const dialogRef = this.dialog.open(this.FormComponent, {
      data: {
        title: this.FormTitle,
        type: 'new',
        data: {}
      }
    });

    dialogRef.afterClosed().subscribe(data => {
      if (data) {
        this.data.push(data);

        this.showDeleted({ checked: this.filterDeleted });
        return;
      }
    });
  }

  editDialog(data) {
    const index = this.data.findIndex(({ id }) => id == data.id);

    const dialogRef = this.dialog.open(this.FormComponent, {
      data: {
        title: this.FormTitle,
        type: 'edit',
        data
      }
    });

    dialogRef.afterClosed().subscribe(data => {
      if (data) {
        Object.assign(this.data[index], data);
        this.showDeleted({ checked: this.filterDeleted });
        return;
      }
    });
  }

  deleteDialog(data) {
    const index = this.data.findIndex(({ id }) => id == data.id);

    const dialogRef = this.dialog.open(this.DeleteComponent, {
      data: {
        data,
        display: data.name
      }
    });

    dialogRef.afterClosed().subscribe(res => {
      if (res) {
        this.http
          .delete(`${this.BaseUrl}/${data.id}`)
          .then(result => {
            Object.assign(this.data[index], result);

            this.showDeleted({ checked: this.filterDeleted });

            this.snackbarHelper.show(Message.deleted, 'Close', {
              duration: 2000
            });
          })
          .catch(e => {
            this.snackbarHelper.show();
          });
      }
    });
  }

  restoreDialog(data) {
    const index = this.data.findIndex(({ id }) => id == data.id);

    const dialogRef = this.dialog.open(this.DeleteComponent, {
      data: {
        title: 'Restore Record',
        data,
        display: data.name
      }
    });

    dialogRef.afterClosed().subscribe(res => {
      if (res) {
        this.http
          .post(`${this.BaseUrl}/restore/${data.id}`, { disabled: 0 })
          .then(result => {
            Object.assign(this.data[index], result);

            this.showDeleted({ checked: this.filterDeleted });

            this.snackbarHelper.show(Message.restore, 'Close', {
              duration: 2000
            });
          })
          .catch(e => {
            this.snackbarHelper.show();
          });
      }
    });
  }
}
