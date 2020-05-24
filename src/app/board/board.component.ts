import { Component, OnInit, Input, AfterViewInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActiveSession } from '../interfaces/active-session';
import { environment } from 'src/environments/environment';
import { Card } from '../interfaces/card';
import { Response } from '../interfaces/response';

@Component({
  selector: 'app-board',
  templateUrl: './board.component.html',
  styleUrls: ['./board.component.scss']
})
export class BoardComponent implements OnInit, AfterViewInit {
  @Input() activeSession: ActiveSession;
  @Input() participant: string;
  _iAmActivePlayer: boolean;
  @Input('iAmActivePlayer')
  set iAmActivePlayer(value: boolean) {
    this._iAmActivePlayer = value;
  }

  public cards: Array<Card[]>;

  public cardSize: {
    width: number;
    height: number;
  };

  constructor(private http: HttpClient) {}

  ngOnInit(): void {}

  ngAfterViewInit(): void {
    setTimeout(() => {
      this.cards = [];
      this.activeSession.cards.forEach((card: Card) => {
        if (!this.cards[card.y]) {
          this.cards.push([]);
        }
        this.cards[card.y][card.x] = card;
      });

      this.cardSize = {
        width: (window.innerWidth * 0.6) / this.activeSession.horizontal - 10,
        height: (window.innerHeight - 300) / this.activeSession.vertical
      };
    }, 10);

    setInterval(() => {
      this.http
        .get(`${environment.server}/fetch-cards`, { params: { session: this.activeSession.name } })
        .toPromise()
        .then((response: Response<Card[]>) => {
          response.data.forEach((card: Card) => {
            this.cards[card.y][card.x] = card;
          });
        });
    }, 1000);
  }

  selectCard(x: number, y: number): void {
    if (!this._iAmActivePlayer) {
      return;
    }

    // TODO: later check if participant is in active team

    const body = new URLSearchParams();
    body.set('session', this.activeSession.name);
    body.set('x', x.toString());
    body.set('y', y.toString());

    this.http
      .post(`${environment.server}/select-card`, body.toString(), environment.formHeader)
      .toPromise()
      .then(() => {
        console.info(`Selected card at ${x},${y}`);
      });
  }
}
