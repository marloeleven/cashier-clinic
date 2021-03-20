import { Component, OnInit, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';

@Component({
  selector: 'app-delete',
  templateUrl: './delete.component.html',
  styleUrls: ['./delete.component.css']
})
export class DeleteComponent implements OnInit {

  public title: string = 'Delete Record';

  constructor(
    public dialogRef: MatDialogRef<DeleteComponent>,
    @Inject(MAT_DIALOG_DATA) public data) {
      const multitple = !!data.multitple;

      if (multitple) {
        this.title += 's';
      }

      if (data.title) {
        this.title = data.title;
      }
    }

  ngOnInit() {
  }

  onNoClick(): void {
    this.dialogRef.close();
  }

}
