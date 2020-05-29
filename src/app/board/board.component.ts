import { Component, OnInit, Input, AfterViewInit } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { ActiveSession } from "../interfaces/active-session";
import { environment } from "src/environments/environment";
import { Card } from "../interfaces/card";
import { Response } from "../interfaces/response";

@Component({
  selector: "app-board",
  templateUrl: "./board.component.html",
  styleUrls: ["./board.component.scss"],
})
export class BoardComponent implements OnInit, AfterViewInit {
  _activeSession: ActiveSession;
  @Input("activeSession")
  set activeSession(session: ActiveSession) {
    this._activeSession = session;
    this.prepareCards();
  }
  @Input() participant: string;
  _iAmActivePlayer: boolean;
  @Input("iAmActivePlayer")
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
    setTimeout(() => this.prepareCards(), 10);
  }

  prepareCards(): void {
    this.cards = [];
    this._activeSession.cards.forEach((card: Card) => {
      if (!this.cards[card.y]) {
        this.cards.push([]);
      }
      this.cards[card.y][card.x] = card;
    });

    this.cardSize = {
      width: (window.innerWidth * 0.6) / this._activeSession.horizontal - 10,
      height: (window.innerHeight - 300) / this._activeSession.vertical,
    };
  }

  selectCard(x: number, y: number): void {
    if (!this._iAmActivePlayer) {
      return;
    }

    // TODO: check if participant is in active team

    const body = new URLSearchParams();
    body.set("session", this._activeSession.name);
    body.set("x", x.toString());
    body.set("y", y.toString());

    this.http
      .post(
        `${environment.server}/select-card`,
        body.toString(),
        environment.formHeader
      )
      .toPromise()
      .then(() => {
        console.info(`Selected card at ${x},${y}`);
      });
  }

  exchangeTerm(word: string): void {
    // get term
    const term = this._activeSession.cards.find(
      (card: Card) => card.word === word
    );

    const body = new URLSearchParams();
    body.set("session", this._activeSession.name);
    body.set("x", term.x.toString());
    body.set("y", term.y.toString());

    this.http.post(
      `${environment.server}/exchange-term`,
      body.toString(),
      environment.formHeader
    ).toPromise();
  }
}
