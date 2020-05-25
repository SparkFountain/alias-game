import { Component, OnInit, Input } from '@angular/core';

export interface RGB {
  r: number;
  g: number;
  b: number;
}

@Component({
  selector: 'app-card',
  templateUrl: './card.component.html',
  styleUrls: ['./card.component.scss']
})
export class CardComponent implements OnInit {
  @Input() word: string;
  @Input() color: string;
  @Input() uncovered: boolean;
  @Input() width: number;
  @Input() height: number;
  @Input() flipCard: boolean;
  _iAmActivePlayer: boolean;
  @Input('iAmActivePlayer')
  set iAmActivePlayer(value: boolean) {
    this._iAmActivePlayer = value;
  }
  @Input() exchangeCards: boolean;

  constructor() {}

  ngOnInit(): void {}

  getBackground(): string {
    let background = 'url(/assets/card-background.jpg)';
    if (this.color && this.uncovered) {
      const rgb: RGB = this.hexToRgb(this.color);
      let opacity: number;
      if(this.color === '#ffcc06') {
        opacity = 0.75;
      } else {
        opacity = 0.25;
      }
      background += `, rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, 0.25)`;
    }
    return background;
  }

  hexToRgb(hex: string): RGB {
    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result
      ? {
          r: parseInt(result[1], 16),
          g: parseInt(result[2], 16),
          b: parseInt(result[3], 16)
        }
      : null;
  }
}
