import { MatTableDataSource } from '@angular/material';

import BaseClass from './BaseClass';
import Message from '@app/message';

import { debounceFunc } from '@app/helper/function';

export default abstract class BaseClassV2 extends BaseClass {
  public totalRows = 0;
  public pageState = {
    total: 0,
    page: 0,
    limit: 25,
    search: '',
    filterDeleted: false,
    sortBy: '',
    direction: 'asc'
  };

  private debounceTORef;

  ngOnInit() {
    this.getRequest();
  }

  dataFilter() {
    return this.data;
  }

  matSortChange({ active: sortBy, direction }) {
    if (this.pageState.total > this.pageState.limit) {
      Object.assign(this.pageState, { sortBy, direction });
      this.getRequest();
    }
  }

  getRequest() {
    const {
      search,
      page,
      limit,
      filterDeleted: deleted,
      sortBy,
      direction
    } = this.pageState;
    const params = Object.entries({
      search: search.replace(/\s+/g, ' '),
      page,
      limit,
      deleted: Number(deleted),
      sortBy,
      direction: direction || 'asc'
    })
      .map(([key, value]) => `${key}=${value}`)
      .join('&');

    return this.http
      .get(`${this.BaseUrl}/?${params}`)
      .then(({ data, total }) => {
        this.data = data;
        this.totalRows = total;
        Object.assign(this.pageState, { total });

        this.showList();
      });
  }

  applyFilter(filterValue: string) {
    const search = filterValue.trim().toLowerCase();

    // apply rxjs
    // filter search

    if (this.pageState.search !== search) {
      this.debounceTORef = debounceFunc(
        () => {
          Object.assign(this.pageState, {
            page: 0,
            search
          });
          this.getRequest();
        },
        this.debounceTORef,
        500
      );
    }
  }

  showList() {
    this.dataSource = new MatTableDataSource(this.data);
    this.dataSource.sort = this.sort;
  }

  onPageChange({ pageIndex: page, pageSize: limit }) {
    Object.assign(this.pageState, { page, limit });
    this.getRequest();
  }

  showDeleted({ checked }) {
    this.filterDeleted = checked;

    this.pageState.filterDeleted = checked;
    this.pageState.page = 0;

    this.getRequest();
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

        this.showList();
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

        this.showList();
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
          .then(() => {
            this.data.splice(index, 1);

            this.showList();

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
          .then(() => {
            this.data.splice(index, 1);

            this.showList();

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
