import { Component, OnInit, Input, AfterViewInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActiveSession } from '../interfaces/active-session';
import { environment } from 'src/environments/environment';
import { Card } from '../interfaces/card';
import { UserService } from 'src/services/user.service';
import { User } from '../interfaces/user';
import { Team } from '../interfaces/team';

@Component({
  selector: 'app-board',
  templateUrl: './board.component.html',
  styleUrls: ['./board.component.scss']
})
export class BoardComponent implements OnInit, AfterViewInit {
  _activeSession: ActiveSession;
  @Input('activeSession')
  set activeSession(session: ActiveSession) {
    this._activeSession = session;
    this.prepareCards();
  }
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

  public selectedCards: {
    teamA: number;
    teamB: number;
    neutral: number;
    black: number;
  };

  public user: User;

  constructor(private http: HttpClient, private userService: UserService) {
    this.user = this.userService.getUser();
    this.selectedCards = {
      teamA: 0,
      teamB: 0,
      neutral: 0,
      black: 0
    };
  }

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
      height: (window.innerHeight - 300) / this._activeSession.vertical
    };
  }

  selectCard(x: number, y: number): void {
    if (
      !this._iAmActivePlayer ||
      !this._activeSession.started ||
      this.user.team === this._activeSession.teams.find((team: Team) => team.active).name
    ) {
      return;
    }

    let body = new URLSearchParams();
    body.set('session', this._activeSession.name);
    body.set('x', x.toString());
    body.set('y', y.toString());

    this.http
      .post(`${environment.server}/select-card`, body.toString(), environment.formHeader)
      .toPromise()
      .then(() => {
        // detect color of selected card
        const selectedCard: Card = this._activeSession.cards.find((card: Card) => card.x === x && card.y === y);
        switch (selectedCard.color) {
          case '#222222':
            this.selectedCards.black++;
            break;
          case '#ffcc06':
            this.selectedCards.neutral++;
            break;
          case '#c22b0c':
            this.selectedCards.teamA++;
            break;
          case '#0b6bca':
            this.selectedCards.teamB++;
            break;
        }

        // let totalCards = 0;
        // // tslint:disable-next-line: forin
        // for (const key in this.selectedCards) {
        //   totalCards += this.selectedCards[key];
        // }

        // if (
        //   selectedCard.color !== this._activeSession.teams.find((team: Team) => team.active).color ||
        //   totalCards === this._activeSession.description.amount
        // ) {
        //   body = new URLSearchParams();
        //   body.set('session', this._activeSession.name);
        //   body.set('team', this._activeSession.teams.find((team: Team) => team.active).name);
        //   body.set('description', this._activeSession.description.term);
        //   body.set('amount', this._activeSession.description.amount.toString());
        //   body.set('teamA', this.selectedCards.teamA.toString());
        //   body.set('teamB', this.selectedCards.teamB.toString());
        //   body.set('neutral', this.selectedCards.neutral.toString());
        //   body.set('black', this.selectedCards.black.toString());

        //   this.http
        //     .post(`${environment.server}/add-history-event`, body.toString(), environment.formHeader)
        //     .toPromise()
        //     .then(() => {
        //       body = new URLSearchParams();
        //       body.set('session', this._activeSession.name);

        //       this.http.post(`${environment.server}/next-round`, body.toString(), environment.formHeader).toPromise();
        //     });
        // }
      });
  }

  nextRound(): void {
    const body = new URLSearchParams();
    body.set('session', this._activeSession.name);
    body.set('team', this._activeSession.teams.find((team: Team) => team.active).name);
    body.set('description', this._activeSession.description.term);
    body.set('amount', this._activeSession.description.amount.toString());
    body.set('teamA', this.selectedCards.teamA.toString());
    body.set('teamB', this.selectedCards.teamB.toString());
    body.set('neutral', this.selectedCards.neutral.toString());
    body.set('black', this.selectedCards.black.toString());

    this.http
      .post(`${environment.server}/add-history-event`, body.toString(), environment.formHeader)
      .toPromise()
      .then(() => {
        const body = new URLSearchParams();
        body.set('session', this._activeSession.name);
        this.http.post(`${environment.server}/next-round`, body.toString(), environment.formHeader).toPromise();
      });
  }

  exchangeTerm(word: string): void {
    // get term
    const term = this._activeSession.cards.find((card: Card) => card.word === word);

    const body = new URLSearchParams();
    body.set('session', this._activeSession.name);
    body.set('x', term.x.toString());
    body.set('y', term.y.toString());

    this.http.post(`${environment.server}/exchange-term`, body.toString(), environment.formHeader).toPromise();
  }
}
