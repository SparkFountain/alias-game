import { Component, OnInit, Input, AfterViewInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActiveSession } from '../interfaces/active-session';

@Component({
  selector: 'app-board',
  templateUrl: './board.component.html',
  styleUrls: ['./board.component.scss']
})
export class BoardComponent implements OnInit, AfterViewInit {
  @Input() activeSession: ActiveSession;

  private wordPool: Array<string>;
  public words: Array<Array<any>>;

  public cardSize: {
    width: number;
    height: number;
  };

  constructor(private http: HttpClient) {
    this.words = [];
  }

  ngOnInit(): void {
    this.cardSize = {
      width: (window.innerWidth * 0.6) / this.activeSession.horizontal - 10,
      height: (window.innerHeight - 300) / this.activeSession.vertical
    };
  }

  ngAfterViewInit(): void {
    this.http
      .get(`/assets/themes/${this.activeSession.theme}.json`)
      .toPromise()
      .then((response: string[]) => {
        this.wordPool = this.shuffle(response);

        for (let y = 0; y < this.activeSession.vertical; y++) {
          const row: Array<string> = [];

          for (let x = 0; x < this.activeSession.horizontal; x++) {
            row.push(this.wordPool.pop());
          }

          this.words.push(row);
        }
      });
  }

  private shuffle(array: Array<any>) {
    return array.sort(() => Math.random() - 0.5);
  }
}
