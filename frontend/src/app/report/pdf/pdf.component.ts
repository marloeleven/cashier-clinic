import { Component, OnInit, Inject } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { HttpService } from '@app/service/http.service';
import { PROCEDURE_TYPES, toDropdownArray } from '@app/variables';
import { Format } from '@app/helper/format';
import * as jsPDF from 'jspdf';

@Component({
  selector: 'app-pdf',
  templateUrl: './pdf.component.html',
  styleUrls: ['./pdf.component.css'],
  providers: [
    { provide: 'Window',  useValue: window }
  ]
})
export class PdfComponent implements OnInit {
  public Format = Format;

  public recordId: number;

  public jsPDF = new jsPDF();
  
  public laboratoryArray = [];
  public radioArray = [];
  public physicalArray = [];
  public proceduresArray = toDropdownArray(PROCEDURE_TYPES);
  constructor(
    private route: ActivatedRoute,
    private http: HttpService,
    @Inject('Window') private window: Window,
  ) {
    this.recordId = this.route.snapshot.params.id;
    
    this.http.get(`patient/record/${this.recordId}/procedure`).then(data => {
      this.laboratoryArray = data.filter(({ procedure_type }) => procedure_type === 'LABORATORY');
      this.radioArray = data.filter(({ procedure_type }) => procedure_type === 'RADIOLOGY');
      this.physicalArray = data.filter(({ procedure_type }) => procedure_type === 'PHYSICAL_EXAMINATION');
    });
  }

  ngOnInit() {
    var doc = new jsPDF();
    doc.text(20, 20, 'Hello world!');
    doc.text(20, 30, 'This is client-side Javascript, pumping out a PDF.');
    doc.addPage();
    doc.text(20, 20, 'Do you like that?');
    // Save the PDF
    return doc;
  }

}
