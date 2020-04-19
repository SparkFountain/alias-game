import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-code-sheet',
  templateUrl: './code-sheet.component.html',
  styleUrls: ['./code-sheet.component.scss']
})
export class CodeSheetComponent implements OnInit {
  public cards: Array<string[]>;
  public cardSize: number;

  constructor() {}

  ngOnInit(): void {
    this.cards = [
      ['red', 'red', 'blue', 'white', 'black'],
      ['red', 'red', 'blue', 'white', 'white'],
      ['red', 'red', 'blue', 'white', 'white'],
      ['red', 'red', 'blue', 'white', 'white'],
      ['red', 'red', 'blue', 'white', 'white']
    ];

    this.cardSize = (window.innerWidth * 0.15) / 5;
  }
}
