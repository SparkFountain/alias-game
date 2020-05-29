import { Component, OnInit, Input, Output, EventEmitter } from "@angular/core";

@Component({
  selector: "app-winner",
  templateUrl: "./winner.component.html",
  styleUrls: ["./winner.component.scss"],
})
export class WinnerComponent implements OnInit {
  _winnerTeam: string;
  @Input()
  set winnerTeam(value: string) {
    this._winnerTeam = value;
  }
  @Output() reset: EventEmitter<void> = new EventEmitter<void>();

  constructor() {}

  ngOnInit(): void {}

  resetSession(): void {
    this.reset.emit();
  }
}
