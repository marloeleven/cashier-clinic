import { Component, OnInit, ViewChild, AfterViewInit } from '@angular/core';

@Component({
  selector: 'app-input',
  templateUrl: './input.component.html',
  styleUrls: ['./input.component.css']
})
export class InputComponent implements OnInit, AfterViewInit {
  
  @ViewChild('element') element;

  constructor() { }

  ngOnInit() {
  }

  ngAfterViewInit() {
  }

}
