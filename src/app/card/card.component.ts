import { Component, OnInit, Input, EventEmitter, Output } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { RGB } from "../interfaces/rgb";
import { WordExchange } from "../interfaces/word-exchange";

@Component({
  selector: "app-card",
  templateUrl: "./card.component.html",
  styleUrls: ["./card.component.scss"],
})
export class CardComponent implements OnInit {
  _word: string;
  @Input()
  set word(value: string) {
    this._word = value;
  }
  @Input() color: string;
  _uncovered: boolean;
  @Input("uncovered")
  set uncovered(value: boolean) {
    this._uncovered = value;
  }
  @Input() width: number;
  @Input() height: number;
  @Input() flipCard: boolean;
  _iAmActivePlayer: boolean;
  @Input("iAmActivePlayer")
  set iAmActivePlayer(value: boolean) {
    this._iAmActivePlayer = value;
  }
  _allowExchangeCards: boolean;
  @Input()
  set allowExchangeCards(value: boolean) {
    this._allowExchangeCards = value;
  }

  @Output() exchangeWord: EventEmitter<string> = new EventEmitter();

  constructor(private http: HttpClient) {}

  ngOnInit(): void {}

  getBackground(): string {
    let background = "url(/assets/card-background.jpg)";
    if (this.color && this._uncovered) {
      const rgb: RGB = this.hexToRgb(this.color);
      let opacity: number;
      if (this.color === "#ffcc06") {
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
          b: parseInt(result[3], 16),
        }
      : null;
  }

  exchangeWordAction(): void {
    this.exchangeWord.emit(this._word);
  }
}
