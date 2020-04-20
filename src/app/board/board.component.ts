import { Component, OnInit, Input, AfterViewInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActiveSession } from '../interfaces/active-session';
import { environment } from 'src/environments/environment';
import { Player } from '../interfaces/player';
import { RandomService } from 'src/services/random.service';

@Component({
  selector: 'app-board',
  templateUrl: './board.component.html',
  styleUrls: ['./board.component.scss']
})
export class BoardComponent implements OnInit, AfterViewInit {
  @Input() activeSession: ActiveSession;
  @Input() participant: string;

  private wordPool: Array<string>;
  public words: Array<Array<any>>;

  public cardSize: {
    width: number;
    height: number;
  };

  constructor(private http: HttpClient, private random: RandomService) {
    this.words = [];
  }

  ngOnInit(): void {}

  ngAfterViewInit(): void {
    this.http
      .get(`/assets/themes/${this.activeSession.theme}.json`)
      .toPromise()
      .then((response: string[]) => {
        this.cardSize = {
          width: (window.innerWidth * 0.6) / this.activeSession.horizontal - 10,
          height: (window.innerHeight - 300) / this.activeSession.vertical
        };

        this.wordPool = this.random.shuffle(response, this.activeSession.seed);

        for (let y = 0; y < this.activeSession.vertical; y++) {
          const row: Array<string> = [];

          for (let x = 0; x < this.activeSession.horizontal; x++) {
            row.push(this.wordPool.pop());
          }

          this.words.push(row);
        }
      });
  }

  selectCard(x: number, y: number): void {
    // TODO: check if participant is in active team

    const body = new URLSearchParams();
    body.set('participant', this.participant);
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
