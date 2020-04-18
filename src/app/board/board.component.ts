import { Component, OnInit, Input } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-board',
  templateUrl: './board.component.html',
  styleUrls: ['./board.component.scss']
})
export class BoardComponent implements OnInit {
  @Input() horizontal: number;
  @Input() vertical: number;
  @Input() wordCategory: 'corona' | 'jazzchor' | 'mixed' | 'over-18' | 'party';

  private wordPool: Array<string>;
  public words: Array<Array<any>>;

  constructor(private http: HttpClient) {
    this.words = [];
  }

  ngOnInit(): void {
    this.http
      .get(`/assets/themes/${this.wordCategory}.json`)
      .toPromise()
      .then((response: string[]) => {
        this.wordPool = this.shuffle(response);

        for (let y = 0; y < this.vertical; y++) {
          const row: Array<string> = [];

          for (let x = 0; x < this.horizontal; x++) {
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
