import { Component, OnInit, Input, AfterViewInit } from '@angular/core';
import { ActiveSession } from '../interfaces/active-session';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { Response } from '../interfaces/response';
import { Term } from '../interfaces/term';
import { Color } from '../interfaces/color';

@Component({
  selector: 'app-code-sheet',
  templateUrl: './code-sheet.component.html',
  styleUrls: ['./code-sheet.component.scss']
})
export class CodeSheetComponent implements OnInit, AfterViewInit {
  @Input() activeSession: ActiveSession;

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

    // setInterval(() => {
    //   this.http
    //     .get(`${environment.server}/fetch-terms`, { params: { session: this.activeSession.name } })
    //     .toPromise()
    //     .then((response: Response<Term>) => {
    //       console.info('Received Terms:', response);
    //     });
    // }, 3000);
  }

  ngAfterViewInit(): void {
    setTimeout(() => {
      this.colors = [];
      for (let y = 0; y < this.activeSession.vertical; y++) {
        this.colors.push([]);

        for (let x = 0; x < this.activeSession.horizontal; x++) {
          this.colors[y].push('');
        }
      }

      this.http
        .get(`${environment.server}/get-session-colors`, { params: { session: this.activeSession.name } })
        .toPromise()
        .then((response: Response<Color[]>) => {
          response.data.forEach((color: Color) => {
            this.colors[color.y][color.x] = color.color;
          });
          console.info('');
        });

      this.colorSize = (window.innerWidth * 0.15) / 5;
    }, 10);
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
