import { Component, OnInit, Input, AfterViewInit } from '@angular/core';
import { ActiveSession } from '../interfaces/active-session';
import { RandomService } from 'src/services/random.service';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { Response } from '../interfaces/response';

@Component({
  selector: 'app-code-sheet',
  templateUrl: './code-sheet.component.html',
  styleUrls: ['./code-sheet.component.scss']
})
export class CodeSheetComponent implements OnInit, AfterViewInit {
  @Input() activeSession: ActiveSession;

  public cards: Array<string[]>;
  public cardSize: number;

  public term: string;
  public amount: number;
  public termDenied: boolean;

  constructor(private random: RandomService, private http: HttpClient) {}

  ngOnInit(): void {
    this.term = '';
    this.amount = 2;
    this.termDenied = false;
  }

  ngAfterViewInit(): void {
    const numberOfCards = this.activeSession.horizontal * this.activeSession.vertical;

    this.cards = [];

    let teamA: number;
    let teamB: number;
    let neutral: number;
    let black: number;

    switch (numberOfCards) {
      case 9:
        teamA = 3;
        teamB = 3;
        neutral = 2;
        black = 1;
        break;
      case 12:
        teamA = 4;
        teamB = 4;
        neutral = 3;
        black = 1;
        break;
      case 16:
        teamA = 5;
        teamB = 5;
        neutral = 5;
        black = 1;
        break;
      case 20:
        teamA = 7;
        teamB = 7;
        neutral = 5;
        black = 1;
        break;
      case 25:
        teamA = 8;
        teamB = 8;
        neutral = 8;
        black = 1;
        break;
      case 30:
        teamA = 10;
        teamB = 10;
        neutral = 8;
        black = 2;
        break;
      case 36:
        teamA = 11;
        teamB = 11;
        neutral = 11;
        black = 3;
        break;
    }

    const indexArray: number[] = this.random.shuffle([...Array(numberOfCards).keys()], this.activeSession.seed);
    const colorArray: string[] = [];
    for (let i = 0; i < teamA; i++) {
      colorArray.push(this.activeSession.teams[0].color);
    }
    for (let i = 0; i < teamB; i++) {
      colorArray.push(this.activeSession.teams[1].color);
    }
    for (let i = 0; i < neutral; i++) {
      colorArray.push('#ddd');
    }
    for (let i = 0; i < black; i++) {
      colorArray.push('#222');
    }

    for (let y = 0; y < this.activeSession.vertical; y++) {
      const row: Array<string> = [];
      for (let x = 0; x < this.activeSession.horizontal; x++) {
        const index = indexArray.pop();
        row.push(colorArray[index]);
      }

      this.cards.push(row);
    }

    this.cardSize = (window.innerWidth * 0.15) / 5;
  }

  requestTerm(): void {
    const body = new URLSearchParams();
    body.set('session', this.activeSession.name);
    body.set('word', this.term);
    body.set('amount', this.amount.toString());

    this.http
      .post(`${environment.server}/request-term`, body.toString(), environment.formHeader)
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
}
