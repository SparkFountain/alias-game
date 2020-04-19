import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'app-card',
  templateUrl: './card.component.html',
  styleUrls: ['./card.component.scss']
})
export class CardComponent implements OnInit {
  @Input() word: string;
  @Input() width: number;
  @Input() height: number;

  constructor() {}

  ngOnInit(): void {}

  select(): void {
    console.info('Card has been selected');
  }
}