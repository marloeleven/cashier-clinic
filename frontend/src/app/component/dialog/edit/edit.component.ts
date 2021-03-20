import {
  Component,
  OnInit,
  Inject,
  ViewChild,
  AfterViewInit
} from '@angular/core';
import {
  MatDialogRef,
  MAT_DIALOG_DATA,
  MatDialogContainer
} from '@angular/material';
import { DynamicElement } from './../../../helper/dynamic.component.helper';
import { InputComponent } from '../../elements/input/input.component';

@Component({
  selector: 'app-edit',
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.css']
})
export class EditComponent implements OnInit {
  public title: string;

  @ViewChild('elements')
  elementsTemplate;

  constructor(
    public dialogRef: MatDialogRef<EditComponent>,
    @Inject(MAT_DIALOG_DATA) public data,
    private dynamicElement: DynamicElement
  ) {}

  ngOnInit() {}

  onNoClick(): void {
    this.dialogRef.close();
  }

  ngAfterViewInit() {
    this.dynamicElement.append(MatDialogContainer, InputComponent);
  }
}
