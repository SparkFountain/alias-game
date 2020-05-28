import { Component, OnInit, Input, AfterViewInit } from '@angular/core';
import { ActiveSession } from '../interfaces/active-session';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { Response } from '../interfaces/response';
import { Color } from '../interfaces/color';
import { Team } from '../interfaces/team';
import { Card } from '../interfaces/card';

@Component({
  selector: 'app-code-sheet',
  templateUrl: './code-sheet.component.html',
  styleUrls: ['./code-sheet.component.scss']
})
export class CodeSheetComponent implements OnInit, AfterViewInit {
  _activeSession: ActiveSession;
  @Input('activeSession')
  set activeSession(session: ActiveSession) {
    this._activeSession = session;
  }

  public colors: Array<string[]>;
  public colorSize: number;

  public term: string;
  public amount: number;
  public termDenied: boolean;

  constructor(private http: HttpClient) {}

  ngOnInit(): void {
    this.term = '';
    this.amount = 2;
    this.termDenied = false;
  }

  ngAfterViewInit(): void {
    setTimeout(() => {
      this.colors = [];
      for (let y = 0; y < this._activeSession.vertical; y++) {
        this.colors.push([]);

        for (let x = 0; x < this._activeSession.horizontal; x++) {
          this.colors[y].push('');
        }
      }

      this._activeSession.cards.forEach((card: Card) => {
        this.colors[card.y][card.x] = card.color;
      });

      this.colorSize = (window.innerWidth * 0.15) / 5;
    }, 10);
  }

  requestDescription(): void {
    const body = new URLSearchParams();
    body.set('session', this._activeSession.name);
    body.set('team', this._activeSession.teams.find((team: Team) => team.active).name);
    body.set('word', this.term);
    body.set('amount', this.amount.toString());

    this.http
      .post(`${environment.server}/request-description`, body.toString(), environment.formHeader)
      .toPromise()
      .then((response: Response<boolean>) => {
        if (response.data === true) {
          // term has been accepted
          this.termDenied = false;
        } else {
          // term has been denied
          this.term = '';
          this.termDenied = true;
        }
      });
  }

  fetchCurrentDescription() {}
}
