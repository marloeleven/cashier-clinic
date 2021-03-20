import { Component, OnInit, ViewChild } from '@angular/core';
import { UserService } from '@app/service/user.service';
import { HttpService } from '@app/service/http.service';
import { DEFAULT_URL, PROCEDURE_TYPES } from '@app/variables';
import { Format } from '@app/helper/format';
import { MatDatepicker, MatOption } from '@angular/material';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import Message from '@app/message';

const date = new Date();
const y = date.getFullYear();
const m = date.getMonth();

const getKeys = Obj => Object.keys(Obj);

@Component({
  selector: 'app-report',
  templateUrl: './report.component.html',
  styleUrls: ['./report.component.css'],
})
export class ReportComponent implements OnInit {
  public Format = Format;
  public PROCEDURE_TYPES = PROCEDURE_TYPES;
  public PROCEDURE_TYPES_KEYS = Object.keys(PROCEDURE_TYPES);

  public initial = {
    cashier_id: 'all',
    from: Format.date(new Date(y, m, 1)),
    to: Format.date(new Date(y, m + 1, 0)),
  };

  public proceduresArray = [];
  public selectedProcedures = [];
  public categorizedProcedures = {};

  public getKeys = getKeys;

  public cashiers = [{ key: 'all', value: 'All' }];

  @ViewChild('from') from: MatDatepicker<string>;
  @ViewChild('to') to: MatDatepicker<string>;
  @ViewChild('cashier') cashier_id: MatOption;

  constructor(
    private http: HttpService,
    private snackbarHelper: SnackbarHelper,
    public userService: UserService
  ) {}

  ngOnInit() {
    this.http.get('procedure/category/forlisting').then(data => {
      this.proceduresArray = data;
    });

    this.http.get('user/cashiers').then(data => {
      data.forEach(cashier => {
        this.cashiers.push({
          key: cashier.id,
          value: cashier.full_name,
        });
      });

      if (this.userService.get('type') === 'CASHIER') {
        const id = JSON.parse(this.userService.get('data')).id;
        this.initial.cashier_id = id;
        this.cashier_id.value = id;
      }
    });
  }

  private updateCategorizeSelected() {
    const selectedProceduresId = this.selectedProcedures.map(({ id }) => id);
    this.categorizedProcedures = this.PROCEDURE_TYPES_KEYS.reduce(
      (obj, key) => {
        const category = this.proceduresArray[key];

        if (category) {
          category.filter(sub => {
            const subSelected = sub.procedures.filter(({ id }) =>
              selectedProceduresId.includes(id)
            );

            if (subSelected.length) {
              if (obj[key] === undefined) {
                obj[key] = [];
              }

              var array = obj[key];

              array.push(Object.assign({}, sub, { procedures: subSelected }));

              return true;
            }

            return false;
          });
        }

        return obj;
      },
      {}
    );
  }

  private addSelect(data) {
    this.selectedProcedures.push(data);

    this.updateCategorizeSelected();
  }

  private removeSelected(data) {
    const index = this.selectedProcedures.findIndex(
      procedure => procedure.id === data.id
    );

    if (index !== -1) {
      this.selectedProcedures.splice(index, 1);
    }

    this.updateCategorizeSelected();
  }

  change(event, data) {
    if (event.target.checked) {
      this.addSelect(data);
      return;
    }

    this.removeSelected(data);
  }

  checkAll(event, data) {
    const checked = event.target.checked;
    data.forEach(input => {
      if (checked) {
        !this.isSelected(input.id) && this.addSelect(input);
        return;
      }

      this.removeSelected(input);
    });
    Array.from(
      event.target.closest('mat-expansion-panel').querySelectorAll('input')
    ).forEach((input: HTMLInputElement) => (input.checked = checked));
  }

  isSelected(id) {
    return this.selectedProcedures.find(procedure => procedure.id === id);
  }

  export() {
    if (!this.selectedProcedures.length) {
      this.snackbarHelper.show(Message.procedureRequired, 'Close', {
        duration: 5000,
      });
      return;
    }

    if (this.from._selected > this.to._selected) {
      this.snackbarHelper.show(Message.range_incorrect, 'Close', {
        duration: 5000,
      });
      return;
    }

    const from = Format.date(this.from._selected);
    const to = Format.date(this.to._selected);

    const ids = this.selectedProcedures.map(({ id }) => id);

    window.open(
      `${DEFAULT_URL}report/excel/${
        this.cashier_id.value
      }/${from}/${to}/${ids}`,
      '_blank'
    );
  }
}
